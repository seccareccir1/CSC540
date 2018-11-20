<?php
$table = '';
if(isset($_GET['a'])){
  $table = $_GET['a'];
 }


define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'phpconnect');
define('DB_PASSWORD', 'Thisiscool123*');

$conn = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, $table);

if($conn->connect_error){
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SHOW TABLES";

$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
  // output data of each row
  while($row = $res->fetch_assoc()) {
    //show results
    echo "<a href='showDesc.php?a=".$table."&b=".$row["Tables_in_".$table]."'>".$row["Tables_in_".$table]."</a><br>";
  }
}
else{echo "0 results";}
$stmt->close();
$conn->close();
?>