<!DOCTYPE html>
<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if($_SESSION["ayn"] == 1){
  require_once "menuAdmin.php";
}else{
  require_once "menuReadOnly.php";
}
?>
<body>
  <h2>Customers List</h2>

<?php
require_once "config.php";
// get our db connection.
$conn = get_db_connection();

$query = "SELECT CustID, FirstName, LastName, Phone, Email FROM PersonInfo INNER JOIN Customers ON PersonInfo.PersonID = Customers.PersonID";
$result = $conn->query($query);

$rows = $result->num_rows;

echo '<table>
  <tr>
    <th>CustID</th>
    <th>FirstName</th>
    <th>LastName</th>
    <th>Phone</th>
    <th>Email</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>';
for ($i = 0; $i < $rows; ++$i)
{
	$result->data_seek($i);
  echo '<tr>';
  echo '<td>' . htmlspecialchars($result->fetch_assoc()['CustID']) . '</td>';
	$result->data_seek($i);
	echo '<td>' . htmlspecialchars($result->fetch_assoc()['FirstName']) . '</td>';
	$result->data_seek($i);
  echo '<td>' . htmlspecialchars($result->fetch_assoc()['LastName']) . '</td>';
  $result->data_seek($i);
  echo '<td>' . htmlspecialchars($result->fetch_assoc()['Phone']) . '</td>';
  $result->data_seek($i);
  echo '<td>' . htmlspecialchars($result->fetch_assoc()['Email']) . '</td>';
  $result->data_seek($i);
  echo '<td><a href="customersReadOnly.php?CustID=' . htmlspecialchars($result->fetch_assoc()['CustID']) . '">View</a></td>';
  $result->data_seek($i);
  echo '<td><a href="editCustomers.php?CustID=' . htmlspecialchars($result->fetch_assoc()['CustID']) . '">Edit</a></td>';
}
echo '</table>';
$conn->close();

?>
  
</body>
</html>