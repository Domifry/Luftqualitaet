# Luftqualität, Temperatur, Feuchtigkeit messen inkl. Empfehlung zum lüften - Raspberry PI Luftmesser
Modul zur Messung von Temperatur und Luftfeuchtigkeit mit Speicherung in einer MySQL Datenbank. Über Pushover werden dir Veränderungen gezeigt und über eine Website siehst du alle Veränderungen im Detail.

# Funktionsweise
Über 2 Sensoren misst ein Python Script auf dem Raspberry die Temperatur, Feuchtigkeit und CO2 Gehalt. Diese Daten werden in eine Tabelle eingetragen und auf einer Seite übersichtlich gezeigt. Du siehst dann genau anhand von Ampeln ob es zu warm/kalt, trocken/feucht und stickig ist.
Weiterhin wirst du bei Veränderungen der Ampelfarben über dein Handy benachrichtigt und kannst so entsprechende Schritte einleiten. Es zeugt sowihl positive Veränderungen als auch negative Veränderungen an. Somit weist du z.B. wann du Lüften musst und wann du das Fenster wieder zumachen kannst. <br>
<img src="https://agile-unternehmen.de/stuff/luftquali-notifcation.jpg" width="500px">
<img src="https://agile-unternehmen.de/stuff/luftquali-dashboard.JPG" width="1000px">

# Teileliste
Du brauchst dazu: 

* <a href="https://amzn.to/3zHNZnH" target="_blank">Raspberry PI 3</a>
* <a href="https://amzn.to/3kIAD6k" target="_blank">Sensor MHZ19 </a>
* <a href="https://amzn.to/3AKkhjq" target="_blank">Sensor DHT22 </a>
* <a href="https://amzn.to/3EPyr5f" target="_blank">Widerstand 4,7 - 4,8 Ohm </a>
* <a href="https://amzn.to/3EWiieb" target="_blank">Jumperkabel </a>
* <a href="https://all-inkl.com/PA3BB517416727D" target="_blank"> Eigene SQL Datenbank bei einem Provider</a>

<img src="https://agile-unternehmen.de/stuff/luftquali-raspberry.jpg" width="500px">

# Anschluss der Sensoren
Ich zeige dir anhand der Raspberry Nummern, wie du die Verlinkungen machen musst. Falls du diese nicht kennst nutze Google: "raspberry gpio pins".

## Sensor MHZ19 - CO2
* VIN -> 5V (PIN4)
* GND -> GND (PIN6)
* TXD -> GPIO 15 (PIN 10 RXD)
* RXD -> GPIO 14 (PIN 8 TXD)

Siehe auch: <a href="https://github.com/IT-Berater/mh-z19">Github von IT-Berater</a>

## Sensor DHT22 - Temperatur/Feuchtigkeit
* PIN1 (der erste von Links) ->  3,3V (PIN1)
* PIN2 -> Widerstand und GPIO4 (PIN7) (Wichtig - sonst geht der Sensor direkt kaputt)
* PIN3 -> bleibt frei
* PIN4 -> GND (PIN 9)

Wichtig ist, dass ihr den Widerstand richtig setzt. Er ist zwischen Pin 1 und 2. Schaut auf die Links - dort ist es genau beschrieben. 
Siehe auch: <a href="https://test-wetterstation.de/temperaturmessung-mit-dem-raspberry-pi"> Blog von Test-Wetterstation.de </a>
oder auch: <a href="https://buyzero.de/blogs/news/tutorial-dht22-dht11-und-am2302-temperatursensor-feuchtigkeitsensor-am-raspberry-pi-anschliessen-und-ansteuern"> Buyzero Blog</a>. 

Du musst nicht unbedingt ein Breadboard nehmen. Ohne Breadboard steckst du einfach den Widerstand in Pin 1 und Pin 2 wie im Bild.
<img src="https://agile-unternehmen.de/stuff/raspberry-widerstand.jpg" width="300px">

# Luft.py
Lade die Datei auf deinen Raspberry PI. Diese Datei liest die Temperatur, Luftfeuchtigkeit und auch den CO2 Gehalt aus und speichert diesem in eine SQL Tabelle. Richten wir als erstes den CO2 Sensor und dann den Temperatur/Feuchtigkeit Sensor ein.

## Sensor MHZ19 - CO2

* sudo apt install python3-pip
* pip install mysql-connector-python-rf
* sudo apt-get install python3-mysql.connector
* pip install mh-z19
* pip3 install mh-z19
* sudo apt install i2c-tools
* außerdem solltest du Python installiert haben
* teste kurz ob eine Ausgabe erfolgt: sudo python -m mh_z19 
* Ausgabe sollte sein: {'co2': 468}
* Du brauchst nun eine SQL Datenbank auf einen Provider wie <a href="https://all-inkl.com/PA3BB517416727D" target="_blank">all-inkl</a> - Name der Datenbank: d03794ba und auch Name des Nutzers (sonst musst es mit Suchen und ersetzen in jeder Datei ändern)
* Trage in die Datei die Zugangsdaten deiner SQL Datenbank in die Datei ein
* Das macht du bei mysql.connector.connect - hier brauchst du die IP, Datenbankname, Passwort und Tabellenname - denk dran, dass du es an zwei Stellen eintragen musst
* Lege in der Datenbank 2 Tabellen an. 
* Die erste Tabelle (mhz19) hat folgende Struktur:  Name "co2" mit ID (auto increment und INT), co2 (text) und Zeit (Timestamp)
* Die zweite Tabelle (DHT22) hat diese Struktur: Name: Temperatur mit ID (auto increment und INT), temperatur (text) und zeit (Timestamp)
* Mehr Informationen siehe unten bei SQL Struktur (dies brauchst du für den Sensor DHT22)

## Sensor DHT22 - Temperatur/Feuchtigkeit

* sudo apt-get update
* sudo apt-get install build-essential python-dev python-openssl git-core
* sudo python3 -m pip install --upgrade pip setuptools wheel
* sudo pip3 install Adafruit_DHT
* sudo python3 setup.py install
* sudo Adafruit_Python_DHT/python setup.py install
* sudo ./Adafruit_Python_DHT/examples/AdafruitDHT.py 22 4
* Die Ausgabe sollte sein: Temp=21.0* Humidity=59.8%
* Damit weist du, dass dein Sensor funktioniert
* Du musst nichts weiter tun, weil in der luft.py ist bereits der ganze Ausleseprozess eingebaut

# fehler.py
* Diese Datei sendet dir eine Push-Nachricht wenn das Script neustartet, weil es fehlgeschlagen ist. 
* Trage hier dein API Token und dein USER Token ein und speichere die Datei ab.
* Du brauchst den Pushhover Account auch noch später deswegen kannst du ihn hier schon einrichten. Die Kosten sind 5 Euro einmalig - 30 Tage kostenlos zum Test.
* Mache außerdem beim Raspberry: sudo apt-get install -y python-httplib2


# luft.service
Dieses Script stellt sich, dass sich alle 15 Minuten das Script startet. 
* Lege die Datei in /etc/systemd/system
* sudo systemctl enable luft.service
* sudo systemctl start wsgiserver.service 
* Teste ob es läuft: sudo systemctl status luft.service 

# index.php
Diese Datei ist das Dashboard und zeigt alle Werte mit Ampel visuell auf. Es gibt hiervon eine Sommer und Winterediton. Im Winter kannst du wegen der Kälte nicht ständig die Fenster öffnen, weswegen der Alarm nicht ganz so streng ist. Im Sommer ist es leichter möglich die Fenster zu öffnen, weswegen der Alarm strenger ist. Wähle einfach deine Version in der Datei index.php Zeile 5 indem du $edition = "sommer" auskommentierst (mit //) oder indem du Winter auskommentiert lässt. Eine von beiden Werten sollte keine // enthalten. Du kannst die Werte für den Alarm am Ende des Artikels unter der Überschrift Werte einsehen. 
* Lade die Datei auf deinem Webspace
* Downloade die Datei img.zip - entpacke diese auf deinem Webspace (Ordner img mit 5 Ampelbildern - eigene Darstellung und zur Nutzung freigegeben)
* Trage deine SQL Daten der Datenbank in die Datei ein - das musst du nur in der ersten Zeile machen
* Durch die Erhitzung des Materials und der Nähe zum Raspberry ist die Temperatur und Feuchtigkeit etwas anders als die zum Raum. Ich habe es nachgeprüft und ziehe deswegen 2 Grad von der Temperatur ab und rechne 6% auf die Feuchtigkeit drauf. Ich empfehle dir auch, dass du es prüfen solltest. Verändere gerne noch die Werte unter:
* Zeile 70: $temperatur = (double) $temperatur -2; (bearbeite die 2)
* Zeile 179: $temperatur = (double) $temperatur-2;
* Zeile 109: $feuchtigkeit = (double) $feuchtigkeit+6;
* Zeile 222: $feuchtigkeit = (double) $feuchtigkeit+6;

# cronjob.php
Lasse diese Datei mit einem Cronjob alle 15 Minuten laufen.
* Trage deine SQL Daten der Datenbank in die Datei ein - das musst du nur in der ersten Zeile machen
* Erstelle eine Tabelle wie folgt: ID (auto increment und INT), Temperatur, Feuchtigkeit, CO2 (alle Text)
* Mache 2 Testeinträge: 1,Gruen,Gruen,Gruen und 2, Gelb,Gelb,Gelb - sonst bricht das Script ab - Vorteil ist auch, dass du eine Notification bekommst und weist, dass das Script funktioniert.
* Mache dir einen Account bei Pushover und trage deine Daten ein (Kosten 5 Euro einmalig) - 30 Tage kostenlos zum Test
* Trage deine Daten bei Token und UserID ein (Pushover)
* Installiere die Pushover App auf deinem Handy
* Wichtig: Sobald du das Script luft.py auf den Raspberry gestartet hast, solltest du 1 Minute später den Cronjob für diese Datei starten. So hat es immer aktuelle Werte.
* Am Ende der Datei findest sich eine Sicherung, welche prüft ob alle 15 Minuten Daten eingeliefert werden. Du bekommst eine Push wenn etwas nicht stimmt.
* Lege für die Sicherung eine Tabelle an: Name Sicherung mit ID (Auto Increment), Temperatur und CO2 (beides INT)
* Trage für den Anfang zwei Dummywerte ein: 1 und 1 sowie 0 und 0 damit du 2 Einträge hast - sonst stürzt das Script direkt ab. So bekommst du auch einmalig eine Fehlermeldung und siehst, dass das Script funktioniert. Die zweite Zeile dient dazu, dass es dir bei einem Fehler nur eine Fehlermeldung schickt und nicht alle 15 Minuten eine Fehlermeldung. 
* Auch hier ziehe ich wieder 2 Grad von der Temperatur ab und rechne 6% auf die Feuchtigkeit. Ändere auch dies gerne ab!
* Vergiss nicht in Zeile 7 hier ebenfalls zu wählen ob es eine Sommer oder Winterediton sein soll!

# Werte
Ich nutze folgende Werte für die Ampeln. Für CO2 gibt es eine Sommer und Winterediton. Im Winter kannst du wegen der Kälte nicht ständig die Fenster öffnen, weswegen der Alarm nicht ganz so streng ist. Im Sommer ist es leichter möglich die Fenster zu öffnen, weswegen der Alarm strenger ist. Wähle einfach deine Version in der Datei index.php Zeile 5 indem du $edition = "sommer" auskommentierst (mit //) oder indem du Winter auskommentiert lässt. Eine von beiden Werten sollte keine // enthalten. Du kannst die Werte für den Alarm am Ende des Artikels unter der Überschrift Werte sehen.

## Temperatur
* Grün: 19-23 Grad
* Gelb: 15-19 Grad und 23-25 Grad
* Rot: Unter 15 Grad und über 25 Grad

## Feuchtigkeit
* Grün: 40-60%
* Gelb: 35-40% und 60-65%
* Rot: unter 35% und über 65%

## CO2 (Winter Edition)
* Grün: Unter 1000
* Gelb: 1000 - 1400
* Rot: Über 1400

## CO2 (Sommer Edition)
* Grün: Unter 800
* Grün-Gelb: 800 - 1000
* Gelb: 1000 - 1200
* Gelb-Rot: 1200 - 1400
* Rot: Über 1400

# SQL Struktur
Hier noch eine Übersicht über die SQL Struktur. Achte hier auch auf Groß und Kleinschreibung sowie die Datentypen.

<br><img src="https://agile-unternehmen.de/stuff/SQL-Struktur.jpg" width="300px">
# Disclaimer

Ich weis, dass man die Applikation sicher schöner programmieren kann und bspw. den String in Python direkt kürzen kann. Allerdings habe ich die Software programmiert bevor die Sensoren da waren. Da es ein privates Projekt ist und funktioniert, lasse ich es so. Aber falls mir jemand helfen möchte, kannst du gerne den Code noch sauber ziehen. Ich freue mich über jede Hilfe!
