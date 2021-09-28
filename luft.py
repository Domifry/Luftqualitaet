#!/usr/bin/env python3

import mysql.connector
import time
import os
import sys
import Adafruit_DHT

while True:
   wert = os.popen("sudo python -m mh_z19").read()
   connection = mysql.connector.connect(host="IP", user="USER", passwd="PASS", db="USER")
   cursor = connection.cursor()
   statement = "INSERT INTO `d03794ba`.`co2` (`ID`, `co2`, `Zeit`) VALUES (NULL, '" + wert  + "', current_timestamp())"
   cursor.execute(statement)
   humidity, temperature = Adafruit_DHT.read_retry(22, 4)
   wert1 = 'Temp={0:0.1f}*  Humidity={1:0.1f}%'.format(temperature, humidity)
   statement1 = "INSERT INTO `d03794ba`.`Temperatur` (`ID`, `temperatur`, `zeit`) VALUES (NULL, '"+ wert1  +"', current_timestamp())"
   cursor.execute(statement1)
   connection.commit()
   cursor.close()
   time.sleep(900)

