from gps import *
import time
import MySQLdb

running = True
latitude = 0
longitude = 0
id = 0

def getPositionData(gps):
    nx = gpsd.next()
    # For a list of all supported classes and fields refer to:
    # https://gpsd.gitlab.io/gpsd/gpsd_json.html
    if nx['class'] == 'TPV':
        global latitude
        global longitude
        latitude = getattr(nx,'lat', "Unknown")
        longitude = getattr(nx,'lon', "Unknown")
        print( "Your position: lon = " + str(longitude) + ", lat = " + str(latitude))


gpsd = gps(mode=WATCH_ENABLE|WATCH_NEWSTYLE)

db = MySQLdb.connect(host="localhost", user="root",passwd="merel", db="wals_database")
cur = db.cursor()

sql_delete = ("""DELETE FROM tempLog""")
cur.execute(sql_delete)
db.commit()

try:
    print( "Application started!")
    while running:
        getPositionData(gpsd)
        sql = ("""INSERT INTO tempLog (GPS_lon,GPS_lat, id) VALUES (%s,%s,%s)""", (longitude, latitude, id))
        print(longitude)
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
        id = id + 1
        time.sleep(1.0)

except (KeyboardInterrupt):
    running = False
    print ("Applications closed!")
