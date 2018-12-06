<!doctype html>
<html lang="en">
<html>
 <head>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Records</title>
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
    <link rel="stylesheet" href="css2/bootstrap-iso.css">
    <link rel="stylesheet" href="css2/main.css">
 </head>
 <body>
    <div class="bootstrap-iso">
    <?php

    require_once "config.php";
    // get our db connection.
    $conn = get_db_connection();

    $custid = '';

    if($_SERVER["REQUEST_METHOD"]=="GET") {
      $custid = trim($_GET["CustID"]);
    }

    $query = "SELECT Customers.CustID, PersonInfo.FirstName, PersonInfo.LastName, PersonInfo.Phone, PersonInfo.Email, 
		Customers.CustAcctStat, Address.AddressLine1, Address.AddressLine2, Address.City, Address.State, Address.PostalCode,
		Address.Country FROM PersonInfo INNER JOIN Customers ON PersonInfo.PersonID = Customers.PersonID 
		LEFT JOIN Address ON Address.AddressID = PersonInfo.AddressID WHERE Customers.CustID = " . $custid;
    $result = $conn->query($query);
    $rows = $result->num_rows;
    
    echo '
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h4>Customers</h4>
        </div>
      </div>
      <form>
        <div class="row">
          <div class="col-6">            
            <h6>Customer Status:  </h6>';
            $result->data_seek(0);
            echo '<input type="text" value="' .
            htmlspecialchars($result->fetch_assoc()['CustAcctStat']) . '">
            
            <h6>First Name:  </h6>';
            $result->data_seek(0);
            echo '<input type="text" value="' .
            htmlspecialchars($result->fetch_assoc()['FirstName']) . '">
            
            <h6>Last Name:  </h6>';
            $result->data_seek(0);
            echo '<input type="text" value="' .
            htmlspecialchars($result->fetch_assoc()['LastName']) . '">
            
            <h6>Phone Number:  </h6>';
            $result->data_seek(0);
            echo '<input type="text" value="' .
            htmlspecialchars($result->fetch_assoc()['Phone']) . '">
            
            <h6>Email Address:  </h6>';
            $result->data_seek(0);
            echo '<input type="text" value="' .
            htmlspecialchars($result->fetch_assoc()['Email']) . '">
            
          </div>';
   #Placeholder until query pulls address
          echo '
          <div class="col-6">
            <h6>Billing Address:  </h6>
							<div class="containRight">';
            $result->data_seek(0);
            echo '<lable>Street Address: </lable><input type="text" value="' .
            htmlspecialchars($result->fetch_assoc()['AddressLine1']) . '"><br>';
	          $result->data_seek(0);
            echo '<lable>Street Address: </lable><input type="text" value="' .
            htmlspecialchars($result->fetch_assoc()['AddressLine1']) . '"><br>';
            $result->data_seek(0);
            echo '<lable>City: </lable><input type="text" value="' .
            htmlspecialchars($result->fetch_assoc()['City']) . '"><br>';
            $result->data_seek(0);
            echo '<lable>State: </lable><input type="text" value="' .
            htmlspecialchars($result->fetch_assoc()['State']) . '"><br>';
	             $result->data_seek(0);
            echo '<lable>Zip Code: </lable><input type="text" value="' .
            htmlspecialchars($result->fetch_assoc()['PostalCode']) . '"><br>';
            $result->data_seek(0);
            echo '<lable>Country: </lable><input type="text" value="' .
            htmlspecialchars($result->fetch_assoc()['Country']) . '">
           </div>   
          </div>
        </div>
        <div class="row">
		    	<div class="col-10">
		    	</div>
		    	<div class="col-2">
			      <input class="btn btn-success" type="submit" value="Save" id="save-button">
		    	</div>
		    </div>
       </form>
    </div>
		</div>
    ';
    
    $conn->close();
   ?>
	 </div>
  </body>
</html>
