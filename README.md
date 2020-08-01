# Wals
python code voor een weerstaton op de pi
Laat temperatuur zien in een bewegende grafiek

## i2c installeren voor bme280

kijk of de sensor goed is aangesloten met:  
`$ sudo i2cdetect -y 1`

## voor grafiek

voor nodige programma's doe:  
`$ sudo apt-get install libatlas3-base libffi-dev at-spi2-core python3-gi-cairo`  
`$ pip install cairocffi`  
`$ pip install matplotlib`
