<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'phpconnect');
define('DB_PASSWORD', 'Thisiscool123*');

$conn = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if($conn->connect_error){
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SHOW DATABASES";

$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
  // output data of each row
  while($row = $res->fetch_assoc()) {
    //show results
   echo "<a href='showTable.php?a=".$row["Database"]."'>".$row["Database"]."</a><br>";
  }
}
else{echo "0 results";}
$stmt->close();
$conn->close();
?>