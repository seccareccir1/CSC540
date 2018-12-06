<?PHP
require_once "config.php";
$conn = get_db_connection();
$custid = 0;
//  // department id
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $custid = trim($_POST["cust"]);
}
//echo "<script> console.log(".$custid.");</script>";
$sql = "SELECT C.AddressID, A.PersonID, A.CompanyInfo, B.FirstName, B.LastName, C.AddressLine1, C.City, C.State, C.PostalCode, C.Country FROM Customers A LEFT JOIN PersonInfo B on A.PersonID = B.PersonID LEFT JOIN Address C ON B.AddressID = C.AddressID WHERE A.CustID = ?";
$stmt1 = $conn->prepare($sql);
$stmt1->bind_param("s", $param_cust);
// Set parameters
$param_cust = $custid;
// Attempt to execute the prepared statement
$stmt1->execute();
$res = $stmt1->get_result();
$cust_arr = [];
if ($res->num_rows > 0) {
    // output data of each row
    while ($row = $res->fetch_assoc()) {
          $company = $row['CompanyInfo'];
          $fname = $row['FirstName'];
          $lname = $row['LastName'];
          $a1 = $row['AddressLine1'];
          $city = $row['City'];
          $state = $row['State'];
          $pc = $row['PostalCode'];
          $ct = $row['Country'];
          $pid = $row['PersonID'];
          $aid = $row['AddressID'];
          //echo $company. ' | ' . $fname. ' | ' . $lname. ' | ' . $a1. ' | ' . $city. ' | ' . $state. ' | ' . $pc. ' | ' . $ct. ' | ' . $pid. ' | ' . $aid;
          $cust_arr = ["pid" => $pid, "aid" => $aid, "fname" => $fname, "lname" => $lname, "al" => $a1, "city" => $city, "state" => $state, "pc" => $pc, "ct" => $ct, "custID" => $custid];
    }
} else {
    //echo "0 results";
}
$stmt1->close();
$conn->close();
echo json_encode($cust_arr);
?>