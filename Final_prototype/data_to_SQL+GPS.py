import smbus2
import bme280
import MySQLdb
from gps import *
import os, glob, time

class DS18B20(object):
    def __init__(self):
        self.device_file = glob.glob("/sys/bus/w1/devices/28*")[0] + "/w1_slave"

    def read_temp_raw(self):
        f = open(self.device_file, "r")
        lines = f.readlines()
        f.close()
        return lines

    def crc_check(self, lines):
        return lines[0].strip()[-3:] == "YES"

    def read_temp(self):
        temp_c = -255
        attempts = 0

        lines = self.read_temp_raw()
        success = self.crc_check(lines)

        while not success and attempts < 3:
            time.sleep(.2)
            lines = self.read_temp_raw()
            success = self.crc_check(lines)
            attempts += 1

        if success:
            temp_line = lines[1]
            equal_pos = temp_line.find("t=")
            if equal_pos != -1:
                temp_string = temp_line[equal_pos + 2:]
                temp_c = float(temp_string) / 1000.0

        return temp_c


def getPositionData(gps):
    nx = gpsd.next()
    # For a list of all supported classes and fields refer to:
    # https://gpsd.gitlab.io/gpsd/gpsd_json.html
    if nx['class'] == 'TPV':
        latitude = getattr(nx,'lat', "Unknown")
        longitude = getattr(nx,'lon', "Unknown")
        print( "Your position: lon = " + str(longitude) + ", lat = " + str(latitude))


gpsd = gps(mode=WATCH_ENABLE|WATCH_NEWSTYLE)

running = True
id = 0
#params for bme)280
port = 1
address = 0x76
bus = smbus2.SMBus(port)
calibration_params = bme280.load_calibration_params(bus, address)

tempsensor = DS18B20()

# Variables for MySQL
db = MySQLdb.connect(host="localhost", user="root",passwd="merel", db="wals_database")
cur = db.cursor()

sql_delete = ("""DELETE FROM tempLog""")
cur.execute(sql_delete)
db.commit()


try:
    while running:
        data = bme280.sample(bus, address, calibration_params)
        getPositionData(gpsd)
        sql = ("""INSERT INTO tempLog (GPS_lon,GPS_lat,air_temp,air_pressure,air_humidity,temp, id) VALUES (%s,%s,%s,%s,%s,%s, %s)""", (0, 0, data.temperature, data.pressure, data.humidity, tempsensor.read_temp(), id))
        try:
            print("Writing to database...")
            # Execute the SQL command
            cur.execute(*sql)
            # Commit your changes in the database
            db.commit()
            print("Write Complete")
        except:
         # Rollback in case there is any error
            db.rollback()
            print("Failed writing to database")
        time.sleep(10)
        id = id + 1

except (KeyboardInterrupt):
    running = False
    cur.close()
    db.close()





