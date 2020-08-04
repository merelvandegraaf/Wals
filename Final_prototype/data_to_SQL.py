import smbus2
import bme280
import MySQLdb
import time

running = True
id = 0
#params for bme)280
port = 1
address = 0x76
bus = smbus2.SMBus(port)
calibration_params = bme280.load_calibration_params(bus, address)

# Variables for MySQL
db = MySQLdb.connect(host="localhost", user="root",passwd="merel", db="wals_database")
cur = db.cursor()

sql_delete = ("""DELETE FROM tempLog""")
cur.execute(sql_delete)
db.commit()


try:
    while running:
        data = bme280.sample(bus, address, calibration_params)
        sql = ("""INSERT INTO tempLog (GPS_lon,GPS_lat,air_temp,air_pressure,air_humidity,temp, id) VALUES (%s,%s,%s,%s,%s,%s, %s)""", (0, 0, data.temperature, data.pressure, data.humidity, 0, id))
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

