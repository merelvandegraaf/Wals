<?php
$servername = "localhost";
$username = "root";
$password = "merel";
$dbname = "wals_database";

//create connection
$conn = new mysqli($servername, $username, $password, $dbname);
//check conn
if($conn->connect_error){
	die("connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tempLog";
$result = $conn->query($sql);

function cleanData(&$str)
  {
    if($str == 't') $str = 'TRUE';
    if($str == 'f') $str = 'FALSE';
    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
      $str = "'$str";
    }
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

function exportDatabase(){

  // filename for download
  $filename = "website_data_" . date('Ymd') . ".csv";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: text/csv");

  $out = fopen("php://output", 'w');

  $flag = false;
  while(false !== ($row = pg_fetch_assoc($result))) {
    if(!$flag) {
      // display field/column names as first row
      fputcsv($out, array_keys($row), ',', '"');
      $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    fputcsv($out, array_values($row), ',', '"');
  }

  fclose($out);
  exit;
}

if(isset($_POST[export])){
exportDatabase();
}

?>
<!DOCTYPE html>
<html>
<head>
<Title><?php echo "Wals data"; ?></Title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<h1> <?php echo "Wals data"; ?></h1>

<form action="" method="post">
<button type="submit" id="btnExport" name='export'
value="Export to Excel" class="btn btn-info">Export to Excel</button>
</form>

<?php
if ($result->num_rows > 0){
	//output data
	echo "<table> <tr><th>GPS lon</th><th>GPS lat</th><th>air temp</th><th>air pressure</th>
	<th>air humidity</th><th>temp</th></tr>";
	while($row = $result->fetch_assoc()){
		echo "<tr><td>" . $row[GPS_lon]. "</td><td>" . $row[GPS_lat]. 
		"</td><td>" . $row[air_temp]. "</td><td>" . $row[air_pressure]. 
		"</td><td>" . $row[air_humidity]. "</td><td>" . $row[temp]."</td></tr>";
	}
	echo "</table>";
} else{
	echo "0 results";
}
$conn->close();
?>
</body></html>
