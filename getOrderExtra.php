<?PHP
require_once "config.php";
$conn = get_db_connection();
$custid = 0;
//  // department id
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $custid = trim($_POST["qut"]);
}
//echo "<script> console.log(".$custid.");</script>";
$sql = "SELECT C.AddressID, C.PersonID, B.CompanyInfo, C.FirstName, C.LastName, D.AddressLine1, D.City, D.State, D.PostalCode, D.Country, A.QuoteID, Z.TotalVal, Z.OrderID
FROM Orders Z LEFT JOIN Quote A ON Z.QuoteID = A.QuoteID LEFT JOIN Customers B ON A.CustID = B.CustID LEFT JOIN PersonInfo C ON B.PersonID = C.PersonID LEFT JOIN Address D ON C.AddressID = D.AddressID WHERE Z.OrderID = ?";
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
          $qid = $row['QuoteID'];
          $oid = $row['OrderID'];
          $ftval = $row['TotalVal'];
          //echo $company. ' | ' . $fname. ' | ' . $lname. ' | ' . $a1. ' | ' . $city. ' | ' . $state. ' | ' . $pc. ' | ' . $ct. ' | ' . $pid. ' | ' . $aid;
          $cust_arr = ["pid" => $pid, "aid" => $aid, "fname" => $fname, "lname" => $lname, "al" => $a1, "city" => $city, "state" => $state, "pc" => $pc, "ct" => $ct, "custID" => $custid, "qid" =>$qid,"oid" => $oid, "ftval" => $ftval];
    }
} else {
    //echo "0 results";
}
$stmt1->close();
$conn->close();
echo json_encode($cust_arr);
?>