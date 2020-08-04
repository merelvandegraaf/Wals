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

function exportDatabase($result){
	
	$result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }
		$productResult = $resultset;
        $timestamp = time();
        $filename = 'Export_excel_' . $timestamp . '.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        $isPrintHeader = false;
        foreach ($productResult as $row) {
            if (! $isPrintHeader) {
                echo implode("\t", array_keys($row)) . "\n";
                $isPrintHeader = true;
            }
            echo implode("\t", array_values($row)) . "\n";
        }
        exit();
    }
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
