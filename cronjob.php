<?php
$Datenbank = "datenbank";
$Passwort = "pass";
$token = "token";
$UserID = "userid";
//$edition = "winter";
$edition = "sommer";

//starte den Cron alle 15 Minuten - 1 Minute nach dem Raspberry, setze jedoch auch auf dem Raspberry die 15 min
$change = false;
$message = "Neue Ampelwerte";
// lese den aktuellen Status aus ID ist 1
$con=mysqli_connect("localhost",$Datenbank,$Passwort,$Datenbank);
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$sql = 'SELECT * FROM `Status` WHERE ID = "1"';
$result = mysqli_query($con, $sql);
$row = $result->fetch_array();
$feuchtigkeitalt = $row['Feuchtigkeit'];
$temperaturalt = $row['Temperatur'];
$co2alt = $row['CO2'];

//übertrage Status auf ID 2
$sql1 = "UPDATE `Status` SET `Temperatur` = '".$temperaturalt."', `Feuchtigkeit` = '".$feuchtigkeitalt."', `CO2` = '".$co2alt."' WHERE `Status`.`ID` = 2";
$result = mysqli_query($con, $sql1);

//setze den Status in ID 1 neu
$result = mysqli_query($con, "SELECT COUNT(ID) as anzahl FROM `co2`");
$data=mysqli_fetch_assoc($result);
$total = $data['anzahl'] + 17;
//suche nun den letzten Wert
$result = mysqli_query($con, 'SELECT * FROM `co2` WHERE ID='.$total);
$row = $result->fetch_array();
$row4 = substr($row['co2'], 0, -2);
$row1 = substr($row4,8,strlen($row4));

if ($edition == "sommer") {
  if ($row1 <= 800) {
    $co2neu = "Gruen";
    $co2neu1 = "Grün";
  }

  if ($row1 > 800 && $row1 <= 1000) {
    $co2neu = "Gruen-Gelb";
    $co2neu1 = "Grün-Gelb";
  }

  if ($row1 > 1000 && $row1 < 1200) {
    $co2neu = "Gelb";
    $co2neu1 = "Gelb";
  }

  if ($row1 > 1200 && $row1 < 1400) {
    $co2neu = "Gelb-Rot";
    $co2neu1 = "Gelb-Rot";
  }

  if ($row1 >= 1400) {
    $co2neu = "Rot";
    $co2neu1 = "Rot";
  }
} else {
if ($row1 <= 1000) {
  $co2neu = "Gruen";
  $co2neu1 = "Grün";
}

if ($row1 > 1000 && $row1 < 1400) {
	$co2neu = "Gelb";
  $co2neu1 = "Gelb";
}

if ($row1 >= 1400) {
  $co2neu = "Rot";
  $co2neu1 = "Rot";
  }
}
  //Suche nun noch Temperatur und Feuchtigkeit und weise die Ampel zu
$result = mysqli_query($con, "SELECT COUNT(ID) as anzahl FROM `Temperatur`");
$data=mysqli_fetch_assoc($result);
$total = $data['anzahl'] + 3;
//suche nun den letzten Wert
$result = mysqli_query($con, 'SELECT * FROM `Temperatur` WHERE ID='.$total);
$row = $result->fetch_array();
$tempfeucht = $row['temperatur'];
$zahltempneu = substr($tempfeucht,0, -17);
$zahltempneu = substr($zahltempneu,5,strlen($zahltempneu));
$zahltempneu = (double) $zahltempneu-2;
$zahlfeuchtneu = substr($tempfeucht,21,strlen($tempfeucht));
$zahlfeuchtneu = (double) $zahlfeuchtneu+6;

if ($zahltempneu <= 15) {
$temperaturneu = "Rot";
$temperaturneu1 = "Rot";
}

if ($zahltempneu >= 25) {
$temperaturneu = "Rot";
$temperaturneu1 = "Rot";
}

if ($zahltempneu <= 23 && $zahltempneu >= 19) {
$temperaturneu = "Gruen";
$temperaturneu1 = "Grün";
}

if ($zahltempneu < 19 && $zahltempneu > 16) {
$temperaturneu = "Gelb";
$temperaturneu1 = "Gelb";
}

if ($zahltempneu > 23 && $zahltempneu < 25) {
$temperaturneu = "Gelb";
$temperaturneu1 = "Gelb";
}

//Feuchtigkeit Ampelfarbe

if ($zahlfeuchtneu >= 40 && $zahlfeuchtneu <= 60) {
  $feuchtigkeitneu = "Gruen";
  $feuchtigkeitneu1 = "Grün";
}
if ($zahlfeuchtneu >= 65) {
  $feuchtigkeitneu = "Rot";
  $feuchtigkeitneu1 = "Rot";

}
if ($zahlfeuchtneu <= 35) {
  $feuchtigkeitneu = "Rot";
  $feuchtigkeitneu1 = "Rot";
}
if ($zahlfeuchtneu < 40 && $zahlfeuchtneu > 35) {
  $feuchtigkeitneu = "Gelb";
  $feuchtigkeitneu1 = "Gelb";
}
if ($zahlfeuchtneu < 65 && $zahlfeuchtneu > 60) {
  $feuchtigkeitneu = "Gelb";
  $feuchtigkeitneu1 = "Gelb";
}

// hier noch das Update für die neuen Werte Temperatur und Feuchtigkeit

$sql2 = "UPDATE `Status` SET `Temperatur` = '".$temperaturneu."', `Feuchtigkeit` = '".$feuchtigkeitneu."', `CO2` = '".$co2neu."' WHERE `Status`.`ID` = 1";
$result = mysqli_query($con, $sql2);

// Wandle Grün um
if ($feuchtigkeitalt == "Gruen") {
$feuchtigkeitalt1 = "Grün";
} else {
$feuchtigkeitalt1 = $feuchtigkeitalt;
}
if ($temperaturalt == "Gruen") {
$temperaturalt1 = "Grün";
} else {
$temperaturalt1 = $temperaturalt;
}

switch ($co2alt) {
  case "Gruen":
    $co2alt1 = "Grün";
    break;
  case "Gruen-Gelb":
    $co2alt1 = "Grün-Gelb";
    break;
  default:
    $co2alt1 = $co2alt;
}

//werte den Status aus

if ($feuchtigkeitalt != $feuchtigkeitneu) {
$change = true;
$message = $message."\nDie Ampel zur Feuchtigkeit springt von ".$feuchtigkeitalt1." auf ".$feuchtigkeitneu1."!";
}
if ($temperaturalt != $temperaturneu) {
$change = true;
$message = $message."\nDie Ampel zur Temperatur springt von ".$temperaturalt1." auf ".$temperaturneu1."!";
}
if ($co2alt != $co2neu) {
$change = true;
$message = $message."\nDie Ampel zum CO2 springt von ".$co2alt1." auf ".$co2neu1."!";
}

// hier ein if was verändert dann sende Push
if ($change == true) {
curl_setopt_array($ch = curl_init(), array(
  CURLOPT_URL => "https://api.pushover.net/1/messages.json",
  CURLOPT_POSTFIELDS => array(
	"token" => $token,
    "user" => $UserID,
    "message" => $message,
  ),
  CURLOPT_SAFE_UPLOAD => true,
  CURLOPT_RETURNTRANSFER => true,
));
curl_exec($ch);
curl_close($ch);
}

//Sicherung ob die Sensoren noch funktionieren
//Sicherung, dass es nicht alle 15 Minuten schreibt
$kaputt = false;
$message1 = "Fehler!";

$result = mysqli_query($con, "SELECT COUNT(ID) as anzahl FROM `co2`");
$data=mysqli_fetch_assoc($result);
$anzahlco2neu = $data['anzahl'];

$result = mysqli_query($con, "SELECT COUNT(ID) as anzahl FROM `Temperatur`");
$data=mysqli_fetch_assoc($result);
$anzahltemperaturneu = $data['anzahl'];

$result = mysqli_query($con, 'SELECT * FROM `Sicherung` WHERE ID=1');
$row4=mysqli_fetch_assoc($result);
$anzahlco2alt = $row4['CO2'];

$result = mysqli_query($con, 'SELECT * FROM `Sicherung` WHERE ID=1');
$row3=mysqli_fetch_assoc($result);
$anzahltemperaturalt = $row3['Temperatur'];

if ($anzahlco2alt != $anzahlco2neu-1) {
$message1 = $message1."\nDer CO2 Sensor liefert keine Daten ein!";
$kaputt = true;
}

if ($anzahltemperaturalt != $anzahltemperaturneu-1) {
$message1 = $message1."\nDer Temperatursensor liefert keine Daten ein!";
$kaputt = true;
}

//hole den Stand ob eine Notification gesendet werden sollte
$result = mysqli_query($con, "SELECT * FROM `Sicherung` WHERE `ID` = 2");
$row4=mysqli_fetch_assoc($result);
$sicherung = $row4['CO2'];

if ($kaputt == true && $sicherung == 0) {
curl_setopt_array($ch = curl_init(), array(
  CURLOPT_URL => "https://api.pushover.net/1/messages.json",
  CURLOPT_POSTFIELDS => array(
	"token" => $token,
    "user" => $UserID,
    "message" => $message1,
  ),
  CURLOPT_SAFE_UPLOAD => true,
  CURLOPT_RETURNTRANSFER => true,
));
curl_exec($ch);
curl_close($ch);
}

if ($kaputt == true && $sicherung == 0) {
$result = mysqli_query($con, "UPDATE `Sicherung` SET `Temperatur` = '1', `CO2` = '1' WHERE `Sicherung`.`ID` = 2");
}
if ($kaputt == false && $sicherung == 1) {
$result = mysqli_query($con, "UPDATE `Sicherung` SET `Temperatur` = '0', `CO2` = '0' WHERE `Sicherung`.`ID` = 2");
$message2 = "Der Sensor liefert wieder Daten!";
curl_setopt_array($ch = curl_init(), array(
  CURLOPT_URL => "https://api.pushover.net/1/messages.json",
  CURLOPT_POSTFIELDS => array(
	"token" => $token,
    "user" => $UserID,
    "message" => $message2,
  ),
  CURLOPT_SAFE_UPLOAD => true,
  CURLOPT_RETURNTRANSFER => true,
));
curl_exec($ch);
curl_close($ch);
}
$result = mysqli_query($con, "UPDATE `Sicherung` SET `CO2` = '".$anzahlco2neu."' WHERE `Sicherung`.`ID` = 1");
$result = mysqli_query($con, "UPDATE `Sicherung` SET `Temperatur` = '".$anzahltemperaturneu."' WHERE `Sicherung`.`ID` = 1");
?>
