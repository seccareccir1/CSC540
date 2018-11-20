<?php
$table = $desc = '';
if(isset($_GET['a'])){
  $table = $_GET['a'];
 }
if(isset($_GET['b'])){
  $desc = $_GET['b'];
 }


define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'phpconnect');
define('DB_PASSWORD', 'Thisiscool123*');

$conn = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, $table);

if($conn->connect_error){
  die("Connection failed: " . $conn->connect_error);
}

$sql = "DESCRIBE ".$desc;
$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->get_result();

echo "<table border='1px'>";
//showing all data dynamically
while ($row =$res->fetch_assoc()) {
    echo "<tr>";
    foreach ($row as $v) {
        echo '<td>' .$v. '</td>'; 
    }
    echo '</tr>';
}
echo "</table>";

$stmt->close();
$conn->close();
echo "</table>";
?>