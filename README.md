# Wals
python code voor een weerstaton op de pi
Laat temperatuur zien in een bewegende grafiek

## i2c installeren voor bme280

kijk of de sensor goed is aangesloten met:  
`$ sudo i2cdetect -y 1`  
`$ pip install smbus2`  
`$ sudo pip install RPi.bme280`  


## Voor grafiek

voor nodige programma's doe:  
`$ sudo apt-get install libatlas3-base libffi-dev at-spi2-core python3-gi-cairo`  
`$ pip install cairocffi`  
`$ pip install matplotlib`  
`$ sudo apt install python-backports.functools-lru-cache`  
`$ sudo apt-get install python-gi-cairo`

## Webserver

Installeren:  
`$ sudo apt-get install apache2 php libapache2-mod-php`  
`$ sudo apt install mariadb-server php-mysql`  
`$ sudo mysql --user=root`  
`DROP USER 'root'@'localhost';`  
`CREATE USER 'root'@'localhost' IDENTIFIED BY  'WACHTWOORD';`  
`GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;`  
`$ sudo apt-get install python-mysqldb`  
`$ mysql -u root -p`  

volg link: https://wingoodharry.wordpress.com/2015/01/05/raspberry-pi-temperature-sensor-web-server-part-2-setting-up-and-writing-to-a-mysql-database/  
database heeft de volgende velden:  
* GPS_lon Decimal(9,6)
* GPS_lat Decimal(9,6)
* air_temp Decimal(5,3)
* air_pressure Decimal(6,2)
* air_humidity Decimal(5,2)
* temp Decimal(6,3)
* id 

## GPS  
startup script runnen
