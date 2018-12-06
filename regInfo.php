<?php
// Initialize the session
session_start();
// Include config file
require_once "config.php";
// get our db connection.
$conn = get_db_connection();
// Check if the user is logged in, if no then redirect them to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$username = $_SESSION["username"];
$fn= $ln = $mp = $em = $adm = $sa = $sal2 = $ct = $st = $zc = $cut = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty(trim($_POST["name"]))) {
        echo "Please Enter First Name.";
    } else {
        $fn = trim($_POST["name"]);
    }
    if (empty(trim($_POST["name1"]))) {
        echo "Please Enter Last Name.";
    } else {
        $ln = trim($_POST["name1"]);
    }
    if (empty(trim($_POST["tel"]))) {
        echo "Please Enter Phone Number.";
    } else {
        $mp = trim($_POST["tel"]);
    }
    if (empty(trim($_POST["email"]))) {
        echo "Please Enter Email Address";
    } else {
        $em = trim($_POST["email"]);
    }
    $sa = trim($_POST["Address_AddressLine1"]);
	  $sal2 = trim($_POST["Address_AddressLine2"]);
	  $ct = trim($_POST["Address_City"]);
	  $st = trim($_POST["Address_Region"]);
	  $zc = trim($_POST["Address_ZipCode"]);
    $cut = trim($_POST["Address_Country"]);
    if(trim($_POST["accessCode"]) == '1111'){
      $adm = 1;
    }else{
      $adm = 0;
    }
      // Prepare an insert statement
    $sql = "INSERT INTO Address (AddressLine1, AddressLine2, City, State, PostalCode, Country) VALUES (?,?,?,?,?,?)";
    
    $stmt2 = $conn->prepare($sql);
    // Bind variables to the prepared statement as parameters
    $stmt2->bind_param("ssssss", $param_a1, $param_a2, $param_c, $param_s, $param_z, $param_ct);
    // Set parameters
    $param_a1 = $sa;
    $param_a2 = $sal2;
    $param_c = $ct;
    $param_s = $st;
    $param_z = $zc;
    $param_ct = $cut;
    
    // Attempt to execute the prepared statement
    if ($stmt2->execute()) {
        $stmt2->close();
        // Prepare an insert statement
        $sql = "INSERT INTO PersonInfo (FirstName, LastName, Phone,Email,AddressID) VALUES (?,?,?,?,?)";

        $stmt3 = $conn->prepare($sql);
        // Bind variables to the prepared statement as parameters
        $stmt3->bind_param("sssss", $param_fn, $param_ln, $param_mp, $param_em,$param_lastID);
        // Set parameters
        $param_fn = $fn;
        $param_mn = $mn;
        $param_ln = $ln;
        $param_mp = $mp;
        $param_em = $em;
        $param_lastID = getLastId($conn, 'Address');
        // Attempt to execute the prepared statement
        if ($stmt3->execute()) {
            $stmt3->close();
            // Prepare a select statement
            $sql1 = "SELECT PersonID FROM PersonInfo WHERE FirstName = ? and LastName = ? and Phone = ?";

            $stmt1 = $conn->prepare($sql1);
            // Bind variables to the prepared statement as parameters
            $stmt1->bind_param("sss", $param_fn1, $param_ln1, $param_mp1);
            // Set parameters
            $param_fn1 = $fn;
            $param_ln1 = $ln;
            $param_mp1 = $mp;
            // Attempt to execute the prepared statement
            $stmt1->execute();
            $res = $stmt1->get_result();
            if ($res->num_rows > 0) {
                // output data of each row
                while ($row = $res->fetch_assoc()) {
                    $updateSql = "UPDATE WebsiteUsers SET PersonID = ?, setupyn = ?, adminyn = ? WHERE username = ?";
                    $stmt4     = $conn->prepare($updateSql);
                    // Bind variables to the prepared statement as parameters
                    $stmt4->bind_param("ssss", $param_pid, $param_syn,$param_adyn, $param_un);
                    $param_pid               = $row["PersonID"];
                    $param_syn               = 1;
                    $param_un                = $username;
                    $param_adyn              = $adm;
                    $_SESSION["fullProfile"] = 1;
                    $stmt4->execute();
                    $stmt4->close();
                    $stmt1->close();
                    header("location: success.php");
                    exit;
                }
            } else {
                echo "0 results";
            }
        } else {
            echo "Something went wrong. Please try again later.";
        }
    } else {
        echo "Something went wrong. Please try again later.";
    }
    // Close statement
    $stmter->close();
    // Close connection
    $conn->close();
}

function getLastId($con,$table){
	$sql1 = "SELECT LAST_INSERT_ID() FROM ".$table;   
	$st1 = $con->prepare($sql1);
	$st1->execute();
	$res = $st1->get_result();
	if ($res->num_rows > 0) {
		while ($row = $res->fetch_assoc()) {          
				return $row["LAST_INSERT_ID()"];
		}
	}
	$st1->close();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <title>Regi</title>
      <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous"> 
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      <link href="css/form.css" rel="stylesheet" type="text/css"><script src="js/validation.js"></script></head><body class="zf-backgroundBg">
 </head>
  <body>
       <h1>
          Please Fill Out Your Employee Info Before Proceeding.
       </h1>
 <div class="form">
     <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <div class="form-group row">
        <label for="name" class="col-3 col-form-label">First Name:</label> 
        <div class="col-9">
          <input id="name" name="name" type="text" required="required" class="form-control here">
        </div>
      </div>
      <div class="form-group row">
        <label for="name1" class="col-3 col-form-label">Last Name:</label> 
        <div class="col-9">
          <input id="name1" name="name1" type="text" required="required" class="form-control here">
        </div>
      </div>
      <div class="form-group row">
        <label for="tel" class="col-3 col-form-label">Phone #:</label> 
        <div class="col-9">
          <input id="tel" name="tel" type="text" class="form-control here" required="required">
        </div>
      </div>
      <div class="form-group row">
        <label for="email" class="col-3 col-form-label">Email:</label> 
        <div class="col-9">
          <input id="email" name="email" type="text" class="form-control here" required="required">
        </div>
      </div> 
      <div class="form-group row">
        <li class="zf-tempFrmWrapper zf-address zf-addrlarge " >
        <label class="zf-labelName">Employee Address
        </label>
        <div class="zf-tempContDiv zf-address"><div class="zf-addrCont">
        <span class="zf-addOne"> <input type="text" maxlength="255" name="Address_AddressLine1" checktype="c1"/><label>Street Address</label> </span>
        <span class="zf-addOne"> <input type="text" maxlength="255" name="Address_AddressLine2" /><label>Address Line 2</label> </span>
        <span class="zf-flLeft zf-addtwo"> <input type="text" maxlength="255" name="Address_City" checktype="c1"/><label>City</label> </span>
        <span class="zf-flLeft zf-addtwo"> <input type="text" maxlength="255" name="Address_Region" checktype="c1"/><label>State/Region/Province</label> </span>
        <span class="zf-flLeft zf-addtwo"> <input type="text" maxlength="255" name="Address_ZipCode" checktype="c1"/><label>Postal / Zip Code</label> </span>
        <span class="zf-flLeft zf-addtwo"> <select class="zf-form-sBox" name="Address_Country" checktype="c1"><option>-Select-</option>
              <option>United States</option>
              <option>Canada</option>
        </select><label>Country</label> </span>
        <div class="zf-clearBoth"></div><p id="Address_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
        </div></div><div class="zf-eclearBoth"></div></li>
       </div>
      <div class="form-group row">
        <label for="AccessCode" class="col-3 col-form-label">AccessCode:</label> 
        <div class="col-9">
          <input id="email" name="accessCode" type="text" class="form-control here"><p>
          leave blank if unknown
          </p>
        </div>
      </div> 
      <div class="form-group row">
        <div class="offset-3 col-9">
          <button name="submit" type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </form>
    </div>
  </body>