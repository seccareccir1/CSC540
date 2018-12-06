<?PHP
require_once "config.php";
$conn = get_db_connection();
$custid = 0;
//  // department id
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $custid = trim($_POST["cust"]);
}
//echo "<script> console.log(".$custid.");</script>";
$sql = "SELECT A.OrderID, A.TotalVal, C.AddressID, CONCAT('$','',CONCAT(A.TotalVal,' = ',CONCAT(CONCAT(B.AddressLine1,', ',B.City),', ',B.State))) as comboAddress FROM Orders A LEFT JOIN Quote C ON A.QuoteID = C.QuoteID LEFT JOIN Address B on C.AddressID = B.AddressID WHERE C.CustID = ?";
$stmt1 = $conn->prepare($sql);
$stmt1->bind_param("s", $param_cust);
// Set parameters
$param_cust = $custid;
// Attempt to execute the prepared statement
$stmt1->execute();
$res = $stmt1->get_result();
$quote_arr = array();
if ($res->num_rows > 0) {
    // output data of each row
    while ($row = $res->fetch_assoc()) {
          $oid = $row['OrderID'];
          $totV = $row['TotalVal'];
          $aid = $row['AddressID'];
          $combo = $row['comboAddress'];
          //echo $company. ' | ' . $fname. ' | ' . $lname. ' | ' . $a1. ' | ' . $city. ' | ' . $state. ' | ' . $pc. ' | ' . $ct. ' | ' . $pid. ' | ' . $aid;
          $quote_arr[] = array("oid"=>$oid, "aid" => $aid, "totVal"=> $totV, "comboAddress" =>$combo);
    }
} else {
    //echo "0 results";
}
$stmt1->close();
$conn->close();
echo json_encode($quote_arr);
?>