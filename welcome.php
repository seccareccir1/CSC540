<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
else if($_SESSION["fullProfile"]==0){
  header("location: regInfo.php");
  exit;
} 
require_once "config.php";
// get our db connection.
$conn = get_db_connection();

$sql = "SELECT A.FirstName, A.LastName FROM PersonInfo A LEFT JOIN WebsiteUsers B ON A.PersonID = B.PersonID WHERE B.username = ?";
$fn = $ln = " ";
$stmt1 = $conn->prepare($sql);
// Bind variables to the prepared statement as parameters
$stmt1->bind_param("s", $param_un);
// Set parameters
$param_un = ucfirst(htmlspecialchars($_SESSION["username"]));
// Attempt to execute the prepared statement
$stmt1->execute();
$res = $stmt1->get_result();
if ($res->num_rows > 0) {
    // output data of each row
    while ($row = $res->fetch_assoc()) {
        $fn = $row["FirstName"];
        $ln = $row["LastName"];
    }
} else {
    echo "0 results";
}
  $stmt1->close();
$conn->close();

?>
<!DOCTYPE html>
<?php 
if($_SESSION["ayn"] == 1){
  require_once "menuAdmin.php";
}else{
  require_once "menuReadOnly.php";
}
?>
<link href="css/form.css" rel="stylesheet" type="text/css">
<body>
      <div class="page-header">
        <p style="font-size: 30px;">Hi, <b><?php echo $fn .' '. $ln ?></b>. Welcome.</p>
    </div>
</body>
</html>

