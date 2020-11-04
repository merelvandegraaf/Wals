sudo systemctl stop gpsd.socket
sudo gpsd /dev/ttyACM0 -F /var/run/gpsd.sock