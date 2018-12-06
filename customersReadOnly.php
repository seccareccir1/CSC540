<!doctype html>
<html lang="en">
<html>
 <head>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Records</title>
    <link rel="stylesheet" href="css2/bootstrap.css">
    <link rel="stylesheet" href="css2/main.css">
 </head>
 <body>
    
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

    require_once "config.php";
    // get our db connection.
    $conn = get_db_connection();

    $custid = '';

    if($_SERVER["REQUEST_METHOD"]=="GET") {
      $custid = trim($_GET["CustID"]);
    }

    $query = "SELECT CustID, FirstName, LastName, Phone, Email, CustAcctStat
      FROM PersonInfo INNER JOIN Customers
      ON PersonInfo.PersonID = Customers.PersonID
      WHERE CustID = " . $custid;
    $result = $conn->query($query);
    $rows = $result->num_rows;
    
    echo '
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h4>Customers</h4>
        </div>
      </div>
      <div class="row">
        <div class="col-6">
          <h6>Customer ID:  </h6>
          <p>';
          $result->data_seek(0);
          echo htmlspecialchars($result->fetch_assoc()['CustID']);
          echo '</p>
          <h6>Customer Status:  </h6>
          <p>';
          $result->data_seek(0);
          echo htmlspecialchars($result->fetch_assoc()['CustAcctStat']);
          echo '</p>
          <h6>First Name:  </h6>
          <p>';
          $result->data_seek(0);
          echo htmlspecialchars($result->fetch_assoc()['FirstName']);
          echo '</p>
          <h6>Last Name:  </h6>
          <p>';
          $result->data_seek(0);
          echo htmlspecialchars($result->fetch_assoc()['LastName']);
          echo '</p>
          <h6>Phone Number:  </h6>
          <p>';
          $result->data_seek(0);
          echo htmlspecialchars($result->fetch_assoc()['Phone']);
          echo '</p>
          <h6>Email Address:  </h6>
          <p>';
          $result->data_seek(0);
          echo htmlspecialchars($result->fetch_assoc()['Email']);
          echo '</p>
        </div>
        <div class="col-6">
          <h6>Billing Address:  </h6>
          <p>';
#Placeholder until query pulls address
          echo 'Address Line 1</p>
          <p>Address Line 2</p>
          <p>City, State PostalCode</p>
          <p>Country</p>
          ';
#          $result->data_seek(0);
#          echo htmlspecialchars($result->fetch_assoc()['CustID']);
#          echo '</p>
        echo '</div>
      </div>
    </div>
    ';
  
    $conn->close();
   ?>
  </body>
</html>
