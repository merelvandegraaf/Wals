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
$sql2 = "SELECT * FROM tempLog Order By id desc limit 1";
$result = $conn->query($sql);
$result2 = $conn->query($sql2);


function ExportFile($records) {
	$filename = "websiteData.xls";		 
            header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"$filename\"");
	$heading = false;
		if(!empty($records))
		  foreach($records as $row) {
			if(!$heading) {
			  // display field/column names as a first row
			  echo implode("\t", array_keys($row)) . "\n";
			  $heading = true;
			}
			echo implode("\t", array_values($row)) . "\n";
		  }
		exit;
}

if(isset($_POST[export])){
ExportFile($result);
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
value="Export to Excel">Export to Excel</button>
</form>

<?php
if ($result->num_rows > 0){
	//output data
	while($row = $result2->fetch_assoc()){
		echo "<p>".$row[temp]."</p>";
	}

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
