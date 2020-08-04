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

function exportDatabase(){

$sql = "SELECT * FROM tempLog";
$results = $conn->query($sql);
	
$rows = results->fetch_assoc();
 
 //The name of the Excel file that we want to force the
//browser to download.
$filename = 'members.xls';
 
//Send the correct headers to the browser so that it knows
//it is downloading an Excel file.
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename");  
header("Pragma: no-cache"); 
header("Expires: 0");

//Define the separator line
$separator = "\t";
 
//If our query returned rows
if(!empty($rows)){
    
    //Dynamically print out the column names as the first row in the document.
    //This means that each Excel column will have a header.
    echo implode($separator, array_keys($rows[0])) . "\n";
    
    //Loop through the rows
    foreach($rows as $row){
        
        //Clean the data and remove any special characters that might conflict
        foreach($row as $k => $v){
            $row[$k] = str_replace($separator . "$", "", $row[$k]);
            $row[$k] = preg_replace("/\r\n|\n\r|\n|\r/", " ", $row[$k]);
            $row[$k] = trim($row[$k]);
        }
        
        //Implode and print the columns out using the 
        //$separator as the glue parameter
        echo implode($separator, $row) . "\n";
    }
}
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
