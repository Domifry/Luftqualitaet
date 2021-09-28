<?php
$Datenbank = "datenbank";
$Passwort = "passwort";
//$edition = "winter";
$edition = "sommer";

function werte() {
global $Datenbank, $Passwort;
	// Create connection
$con=mysqli_connect("localhost",$Datenbank,$Passwort,$Datenbank);

// Check connection
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// This SQL statement selects ALL from the table 'Locations'
$sql = "SELECT * FROM `co2` ORDER BY `ID` DESC LIMIT 0 , 20";

// Check if there are results
$counter = 1;
if ($result = mysqli_query($con, $sql))
{
	// Loop through each row in the result set
    while($row = $result->fetch_array()) {
		// Add each row into our results array
		$temp = $row['Zeit'];
		$datum1 = substr($temp,0,11);
		$datum = substr($datum1,8,-1).".".substr($datum1,5,-4).".".substr($datum1,0,-7);
		$zeit = substr($temp,10);
    $co2 = substr($row['co2'], 0, -2);
		$co21 = substr($co2,8,strlen($co2));
		echo('    <tr>
      <th scope="row">'.$counter.'</th>
      <td>'.$co21.'</td>
      <td>'.$datum.'</td>
      <td>'.$zeit.'</td>
    </tr>');
		$counter++;
	}
} return;
	}

  function wertetemperatur() {
    global $Datenbank, $Passwort;
    	// Create connection
    $con=mysqli_connect("localhost",$Datenbank,$Passwort,$Datenbank);

  // Check connection
  if (mysqli_connect_errno())
  {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  // This SQL statement selects ALL from the table 'Locations'
  $sql = "SELECT * FROM `Temperatur` ORDER BY `ID` DESC LIMIT 0 , 20";

  // Check if there are results
  $counter = 1;
  if ($result = mysqli_query($con, $sql))
  {
  	// Loop through each row in the result set
      while($row = $result->fetch_array()) {
  		// Add each row into our results array
  		$temp = $row['zeit'];
  		$datum1 = substr($temp,0,11);
  		$datum = substr($datum1,8,-1).".".substr($datum1,5,-4).".".substr($datum1,0,-7);
  		$zeit = substr($temp,10);
      $temperatur = substr($row['temperatur'], 0, -17);
      $temperatur = substr($temperatur,5,strlen($temperatur));
      $temperatur = (double) $temperatur -2;
  		echo('    <tr>
        <th scope="row">'.$counter.'</th>
        <td>'.$temperatur.'</td>
        <td>'.$datum.'</td>
        <td>'.$zeit.'</td>
      </tr>');
  		$counter++;
  	}
  } return;
  	}

    function wertefeuchtigkeit() {
      global $Datenbank, $Passwort;
      	// Create connection
      $con=mysqli_connect("localhost",$Datenbank,$Passwort,$Datenbank);

    // Check connection
    if (mysqli_connect_errno())
    {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    // This SQL statement selects ALL from the table 'Locations'
    $sql = "SELECT * FROM `Temperatur` ORDER BY `ID` DESC LIMIT 0 , 20";

    // Check if there are results
    $counter = 1;
    if ($result = mysqli_query($con, $sql))
    {
      // Loop through each row in the result set
        while($row = $result->fetch_array()) {
        // Add each row into our results array
        $temp = $row['zeit'];
        $datum1 = substr($temp,0,11);
        $datum = substr($datum1,8,-1).".".substr($datum1,5,-4).".".substr($datum1,0,-7);
        $zeit = substr($temp,10);
        $feuchtigkeit = $row['temperatur'];
        $feuchtigkeit = substr($feuchtigkeit,21,strlen($feuchtigkeit));
        $feuchtigkeit = (double) $feuchtigkeit+6;

        echo('    <tr>
          <th scope="row">'.$counter.'</th>
          <td>'.$feuchtigkeit.'</td>
          <td>'.$datum.'</td>
          <td>'.$zeit.'</td>
        </tr>');
        $counter++;
      }
    } return;
      }

function luftstatus() {
  global $Datenbank, $Passwort, $edition;
  	// Create connection
  $con=mysqli_connect("localhost",$Datenbank,$Passwort,$Datenbank);
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Suche die Anzahl der Tabellen
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
echo('<td>Aktuelle <b>Luftqualität: '.$row1.' ppm</b><br>Du musst aktuell nicht lüften!</td><td>&nbsp; &nbsp; &nbsp; <img src="https://agile-unternehmen.de/luft/img/ampel-gruen.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
}

if ($row1 > 800 && $row1 <= 1000) {
echo('<td>Aktuelle <b>Luftqualität: '.$row1.' ppm</b><br>Es ist noch akzeptabel!</td><td>&nbsp; &nbsp; &nbsp; <img src="https://agile-unternehmen.de/luft/img/ampel-gruen-gelb.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
}

if ($row1 > 1000 && $row1 < 1200) {
	echo('<td>Aktuelle <b>Luftqualität: '.$row1.' ppm</b><br>Du musst bald wieder lüften!</td><td>&nbsp; &nbsp; &nbsp; <img src="https://agile-unternehmen.de/luft/img/ampel-gelb.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
}

if ($row1 > 1200 && $row1 < 1400) {
	echo('<td>Aktuelle <b>Luftqualität: '.$row1.' ppm</b><br>Es ist langsam Zeit zu lüften</td><td>&nbsp; &nbsp; &nbsp; <img src="https://agile-unternehmen.de/luft/img/ampel-gelb-rot.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
}

if ($row1 >= 1400) {
	echo('<td>Aktuelle <b>Luftqualität: '.$row1.' ppm</b><br>Du musst dringend lüften!</td><td>&nbsp; &nbsp; &nbsp; <img src="https://agile-unternehmen.de/luft/img/ampel-rot.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
}
} else {
  if ($row1 <= 1000) {
  echo('<td>Aktuelle <b>Luftqualität: '.$row1.' ppm</b><br>Du musst aktuell nicht lüften!</td><td>&nbsp; &nbsp; &nbsp; <img src="https://agile-unternehmen.de/luft/img/ampel-gruen.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
  }

  if ($row1 > 1000 && $row1 < 1400) {
  	echo('<td>Aktuelle <b>Luftqualität: '.$row1.' ppm</b><br>Du musst bald wieder lüften!</td><td>&nbsp; &nbsp; &nbsp; <img src="https://agile-unternehmen.de/luft/img/ampel-gelb.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
  }

  if ($row1 >= 1400) {
  	echo('<td>Aktuelle <b>Luftqualität: '.$row1.' ppm</b><br>Du musst dringend lüften!</td><td>&nbsp; &nbsp; &nbsp; <img src="https://agile-unternehmen.de/luft/img/ampel-rot.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
  }
}
//$data=mysqli_fetch_assoc($result);
//echo($result['co2']);

return;
}

function temperatur() {
// 15 wird rot 16 - 19 Gelb und 23 - 25 Grad gelb - ab 25 rot
global $Datenbank, $Passwort;
	// Create connection
$con=mysqli_connect("localhost",$Datenbank,$Passwort,$Datenbank);
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// Suche die Anzahl der Tabellen
$result = mysqli_query($con, "SELECT COUNT(ID) as anzahl FROM `Temperatur`");
$data=mysqli_fetch_assoc($result);
$total = $data['anzahl']+3;
//suche nun den letzten Wert
$result = mysqli_query($con, 'SELECT * FROM `Temperatur` WHERE ID='.$total);
$row = $result->fetch_array();
$temperatur = substr($row['temperatur'], 0, -17);
$temperatur = substr($temperatur,5,strlen($temperatur));
$temperatur = (double) $temperatur-2;

if ($temperatur <= 15) {
echo('<td>Aktuelle <b>Temperatur: '.$temperatur.' Grad</b><br>Es ist zu kalt!</td><td>&nbsp; &nbsp; &nbsp;<img src="https://agile-unternehmen.de/luft/img/ampel-rot.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
}

if ($temperatur >= 25) {
echo('<td>Aktuelle <b>Temperatur: '.$temperatur.' Grad</b><br>Es ist zu warm!</td><td>&nbsp; &nbsp; &nbsp;<img src="https://agile-unternehmen.de/luft/img/ampel-rot.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
}

if ($temperatur <= 23 && $temperatur >= 19) {
echo('<td>Aktuelle <b>Temperatur: '.$temperatur.' Grad</b><br>Es ist alles ok!</td><td>&nbsp; &nbsp; &nbsp;<img src="https://agile-unternehmen.de/luft/img/ampel-gruen.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
}

if ($temperatur < 19 && $temperatur > 16) {
echo('<td>Aktuelle <b>Temperatur: '.$temperatur.' Grad</b><br>Es wird langsam kalt!</td><td>&nbsp; &nbsp; &nbsp;<img src="https://agile-unternehmen.de/luft/img/ampel-gelb.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
}

if ($temperatur > 23 && $temperatur < 25) {
echo('<td>Aktuelle <b>Temperatur: '.$temperatur.' Grad</b><br>Es wird langsam warm!</td><td>&nbsp; &nbsp; &nbsp;<img src="https://agile-unternehmen.de/luft/img/ampel-gelb.jpg" width="50px">&nbsp; &nbsp; &nbsp;</td>');
}

return;
}

function feuchtigkeit() {
// 40 - 60 ok - jeweils 35 und 65 bringt rot
global $Datenbank, $Passwort;
	// Create connection
$con=mysqli_connect("localhost",$Datenbank,$Passwort,$Datenbank);
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// Suche die Anzahl der Tabellen
$result = mysqli_query($con, "SELECT COUNT(ID) as anzahl FROM `Temperatur`");
$data=mysqli_fetch_assoc($result);
$total = $data['anzahl']+3;
//suche nun den letzten Wert
$result = mysqli_query($con, 'SELECT * FROM `Temperatur` WHERE ID='.$total);
$row = $result->fetch_array();
$feuchtigkeit = $row['temperatur'];
$feuchtigkeit = substr($feuchtigkeit,21,strlen($feuchtigkeit));
$feuchtigkeit = (double) $feuchtigkeit+6;

if ($feuchtigkeit >= 40 && $feuchtigkeit <= 60) {
  echo('<td>Aktuelle <b>Feuchtigkeit: '.$feuchtigkeit.'%</b><br>Es ist alles ok!</td><td>&nbsp; &nbsp; &nbsp;<img src="https://agile-unternehmen.de/luft/img/ampel-gruen.jpg" width="50px"></td>');
}
if ($feuchtigkeit >= 65) {
  echo('<td>Aktuelle <b>Feuchtigkeit: '.$feuchtigkeit.'%</b><br>Es ist zu feucht!</td><td>&nbsp; &nbsp; &nbsp;<img src="https://agile-unternehmen.de/luft/img/ampel-rot.jpg" width="50px"></td>');
}
if ($feuchtigkeit <= 35) {
  echo('<td>Aktuelle <b>Feuchtigkeit: '.$feuchtigkeit.'%</b><br>Es ist viel zu trocken!</td><td>&nbsp; &nbsp; &nbsp;<img src="https://agile-unternehmen.de/luft/img/ampel-rot.jpg" width="50px"></td>');
}
if ($feuchtigkeit < 40 && $feuchtigkeit > 35) {
  echo('<td>Aktuelle <b>Feuchtigkeit: '.$feuchtigkeit.'%</b><br>Es wird langsam trocken!</td><td>&nbsp; &nbsp; &nbsp;<img src="https://agile-unternehmen.de/luft/img/ampel-gelb.jpg" width="50px"></td>');
}
if ($feuchtigkeit < 65 && $feuchtigkeit > 60) {
  echo('<td>Aktuelle <b>Feuchtigkeit: '.$feuchtigkeit.'%</b><br>Es wird langsam zu feucht!</td><td>&nbsp; &nbsp; &nbsp;<img src="https://agile-unternehmen.de/luft/img/ampel-gelb.jpg" width="50px"></td>');
}
  return;
}
?>

<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="/main.css">
    <title>Domifrys Luftqualität Script</title>
  </head>
    <body>
<nav class=" navbar navbar-expand-md navbar-dark bg-dark mb-4">
      <a class="navbar-brand" href="#">Domifrys Luftqualität Script<?php if ($edition == "sommer") {echo("(Sommer Edition)");} else {echo("(Winter Edition)");}?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      </div>
    </nav>
<main role="main" class="container">
      <div class="jumbotron">
        <h1>Aktueller Luftstatus</h1>
        <p class="lead">
          <table>
            <tr> <?php echo(luftstatus()); echo(temperatur()); echo(feuchtigkeit());?></p> </tr>
          </table>

      </div>
    </main>
      <main role="main" class="container">
      <div>
      <h1>Alle CO2 Werte</h1>
        <p class="lead">Hier finden sich die letzten CO2 Werte</p>
  <table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">CO2</th>
      <th scope="col">Datum</th>
      <th scope="col">Zeit</th>
    </tr>
  </thead>
  <tbody>
  <?php echo(werte());?>
  </tbody>
</table>
      </div>
      <br /><br />
      <main role="main" class="container">
      <div>
      <h1>Alle Temperatur Werte</h1>
        <p class="lead">Hier finden sich die letzten Temperatur Werte</p>
  <table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Temperatur</th>
      <th scope="col">Datum</th>
      <th scope="col">Zeit</th>
    </tr>
  </thead>
  <tbody>
  <?php echo(wertetemperatur());?>
  </tbody>
</table>
      </div>
      <br /><br />
      <main role="main" class="container">
      <div>
      <h1>Alle Luftfeuchtigkeit Werte</h1>
        <p class="lead">Hier finden sich die letzten Feuchtigkeit Werte</p>
  <table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Feuchtigkeit</th>
      <th scope="col">Datum</th>
      <th scope="col">Zeit</th>
    </tr>
  </thead>
  <tbody>
  <?php echo(wertefeuchtigkeit());?>
  </tbody>
</table>
      </div>
</body>
</html>
