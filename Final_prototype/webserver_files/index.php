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

function exportDatabase($setRec){
	$columnHeader = '';  
$columnHeader = "User Id" . "\t" . "First Name" . "\t" . "Last Name" . "\t" 
 . "Test" . "\t" . "test 2" . "\t"  . "test 3" . "\t";  
$setData = '';  
  while ($rec = mysqli_fetch_row($setRec)) {  
    $rowData = '';  
    foreach ($rec as $value) {  
        $value = '"' . $value . '"' . "\t";  
        $rowData .= $value;  
    }  
    $setData .= trim($rowData) . "\n";  
}  
  
header("Content-type: application/octet-stream");  
header("Content-Disposition: attachment; filename=User_Detail.xls");  
header("Pragma: no-cache");  
header("Expires: 0");  

  echo ucwords($columnHeader) . "\n" . $setData . "\n";  
}

if(isset($_POST[export])){
exportDatabase($result);
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
