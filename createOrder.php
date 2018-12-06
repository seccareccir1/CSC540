<?PHP
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare an insert statement
    $sql    = "INSERT INTO Orders (QuoteID, TotalVal,Comments) VALUES (?,?,?)";
    $qid = trim($_POST["quoteid"]);
    $oc  = trim($_POST["orderComments"]);
    $tval   = trim($_POST["totvalue"]);
    $stmter = $conn->prepare($sql);
    // Bind variables to the prepared statement as parameters
    $stmter->bind_param("sss", $param_qid, $param_tval, $param_com);
    
    // Set parameters
    $param_qid = $qid;
    $param_tval= $tval;
    $param_com = $oc;
    
    // Attempt to execute the prepared statement
    if ($stmter->execute()) {
        // Redirect to login page
        header("location: success.php");
    } else {
        echo "Something went wrong. Please try again later.";
    }
    // Close statement
    $stmter->close();
    
    // Close connection
    $conn->close();
    
    
}

?> 

<!DOCTYPE html>
  <?php
if ($_SESSION["ayn"] == 1) {
    require_once "menuAdmin.php";
} else {
    require_once "menuReadOnly.php";
}
?>
    <title>Create Quote</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
     function checkReadOnly(){
       var checkbox = document.getElementById('addCheck');
        if (checkbox.checked == true){
          document.getElementById('totvalue').removeAttribute('readonly');
        }
       else{
         document.getElementById('totvalue').setAttribute('readonly','true');
       }
     }
      $(document).ready(function() {

        $("#cust-choice").change(function() {
          var custid = $(this).val();
          console.log(custid);
          $.ajax({
            url: 'getQuoteInfo.php',
            type: 'POST',
            data: {
              cust: custid
            },
            dataType: 'json',
            success: function(response) {
              var len = response.length;
               console.log(len);
              $("#quote-choice").empty();
              for( var i = 0; i<len; i++){
                  var id = response[i]['qid'];
                  var blah = response[i]['comboAddress'];

                  $("#quote-choice").append("<option value='"+id+"'>"+blah+"</option>");

              }
            },
          });
        });
        $("#quote-choice").change(function(){
          var custSel = $(this).val();

          $.ajax({
              url: 'getQuoteExtra.php',
              type: 'post',
              data: {qut:custSel},
              dataType: 'json',
              success:function(response){
                var len = response.length;
                console.log(response);
                $("#firstName").val(response['fname']);
                $("#lastName").val(response['lname']);
                $("#sa").val(response['al']);
                $("#city").val(response['city']);
                $("#state").val(response['state']);
                $("#zip").val(response['pc'])
                $("#quoteid").val(response['qid']);
                $("#custID").val(response['custID']);
                $("#totvalue").val(response['tval']);
              }
          });
      });

      });
    </script>
    <link href="css/form.css" rel="stylesheet" type="text/css">
    <script src="js/validation.js"></script>
    </head>

    <body class="zf-backgroundBg">
      <!-- Change or deletion of the name attributes in the input tag will lead to empty values on record submission-->
      <div class="zf-templateWidth">
        <form action='<?php
echo htmlspecialchars($_SERVER["PHP_SELF"]);
?>' name='form' method='POST' onSubmit='javascript:document.charset="UTF-8"; return zf_ValidateAndSubmit();' accept-charset='UTF-8' enctype='multipart/form-data' id='form'>
          <div class="zf-templateWrapper">
            <ul class="zf-tempHeadBdr">
              <li class="zf-tempHeadContBdr">
                <h2 class="zf-frmTitle"><em>Create New Order</em></h2>
                <p class="zf-frmDesc">Create a Customer Order</p>
                <div class="zf-clearBoth"></div>
              </li>
            </ul>
            <!---------template Container Starts Here---------->
            <div class="zf-subContWrap zf-leftAlign">
              <ul>
                <!---------Select Line Starts Here---------->
                <li class="zf-tempFrmWrapper zf-medium">
                  <label class="zf-labelName">Select Customer
                    <em class="zf-important">*</em>
                    </label>

                  <?php
                  $conn = get_db_connection();

                  $sql   = "SELECT * FROM Customers";
                  $stmt1 = $conn->prepare($sql);
                  // Bind variables to the prepared statement as parameters
                  //$stmt1->bind_param("s", $param_un);
                  // Set parameters
                  //$param_un = ucfirst(htmlspecialchars($_SESSION["username"]));
                  // Attempt to execute the prepared statement
                  $stmt1->execute();
                  $res    = $stmt1->get_result();
                  $select = '<div class="zf-tempContDiv"><span class="zf-flLeft zf-addtwo"> <select id="cust-choice" class="zf-form-sBox" name="select"  checktype="c1">';
                  $select .= '<option value="">-Select-</option>';
                  if ($res->num_rows > 0) {
                      // output data of each row
                      while ($row = $res->fetch_assoc()) {
                          $select .= '<option value="' . $row['CustID'] . '">' . $row['CompanyInfo'] . '</option>';
                      }
                  } else {
                      echo "0 results";
                  }
                  $select .= '</select></span></div>';
                  echo $select;
                  $stmt1->close();
                  $conn->close();
                  ?>
                    <div class="zf-clearBoth"></div>
                </li>

                <!---------Select Line Ends Here---------->
                <!---------Select Line Starts Here---------->
                <li class="zf-tempFrmWrapper zf-addlarge">
                  <label class="zf-labelName">Select Quote for Order
                                    <em class="zf-important">*</em>
                                    </label>
                  <div class="zf-tempContDiv">
                    <span class="zf-flLeft zf-addtwo"> 
                      <select id="quote-choice" class="zf-form-sBox" name="select"  checktype="c1">
                        <option>--Select--</option>
                      </select>
                    </span>
                  </div>
                  <div class="zf-clearBoth"></div>
                </li>

                <!---------Select Line Ends Here---------->
                <!---------Name Starts Here---------->
                <li class="zf-tempFrmWrapper zf-name zf-namemedium"><label class="zf-labelName">Name</label>
                  <div class="zf-tempContDiv">
                    <div>
                      <span> <input type="text" maxlength="255" name="Name_First" id="firstName" invlovedinsalesiq=false fieldType=7 checktype="c1" value="" readonly/><label>First</label> </span>
                      <span class="zf-last"> <input type="text" maxlength="255"   id="lastName" invlovedinsalesiq=false fieldType=7 name="Name_Last" checktype="c1" readonly/><label>Last</label> </span>
                      <div class="zf-clearBoth"></div>
                    </div>
                    <p id="Name_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                  </div>
                  <div class="zf-clearBoth"></div>
                </li>
                <!---------Name Ends Here---------->

                <!--address-->
                <!---------Address Starts Here---------->
                <li class="zf-tempFrmWrapper zf-address zf-addrlarge "><label class="zf-labelName">Quote Address</label>
                  <div class="zf-tempContDiv zf-address">
                    <div class="zf-addrCont">
                      <span class="zf-addOne"> <input type="text" maxlength="255" name="Address_AddressLine1" checktype="c1" id="sa" readonly/><label>Street Address</label> </span>
                      <span class="zf-flLeft zf-addtwo"> <input type="text" maxlength="255" name="Address_City" checktype="c1" id="city" readonly/><label>City</label> </span>
                      <span class="zf-flLeft zf-addtwo"> <input type="text" maxlength="255" name="Address_Region" checktype="c1" id="state" readonly/><label>State/Region/Province</label> </span>
                      <span class="zf-flLeft zf-addtwo"> <input type="text" maxlength="255" name="Address_ZipCode" checktype="c1" id="zip" readonly/><label>Postal / Zip Code</label> </span>
                      <input type="hidden" id="custID" name="custID" value="">
                      <input type="hidden" id="quoteid" name="quoteid" value="">
                    </div>
                    <div class="zf-clearBoth"></div>
                </li>
                <!---------Quote Starts Here---------->
                <li class="zf-tempFrmWrapper zf-name zf-namemedium"><label class="zf-labelName">Order Cost <em class="zf-important">*</em></label>
                  <div class="zf-tempContDiv">
                    <div>
                      <span> <input type="text" maxlength="255" name="totvalue" invlovedinsalesiq=false id="totvalue" readonly/><label>$ Amount</label></span>
                    </div>
                    <div class="zf-clearBoth"></div>
                </li>
                <!---------Quote Ends Here---------->
                <!---------Check Box Starts Here---------->
                <li class="zf-tempFrmWrapper zf-name zf-namemedium"><label class="zf-labelName">Change Order Total <em class="zf-important">*</em></label>
                  <div class="zf-tempContDiv">
                    <div>
                      <span> <input type="checkbox" id="addCheck" onclick="checkReadOnly()"/><label>Check to change Order Total</label></span>
                    </div>
                    <div class="zf-clearBoth"></div>
                </li>
                <!---------Check Box Ends Here---------->
                <!---------Multiple Line Starts Here---------->
                <li class="zf-tempFrmWrapper zf-medium"><label class="zf-labelName">Order Comments
                </label>
                <div class="zf-tempContDiv"><span> <textarea name="orderComments" id="orderComments" checktype="c1" maxlength="65535"></textarea> </span>
                </div><div class="zf-clearBoth"></div></li>
                <!---------Multiple Line Ends Here---------->
                <ul>
                  <li class="zf-fmFooter"><button class="zf-submitColor">Submit</button></li>
                </ul>
                </div>
                <!-- 'zf-templateWrapper' ends -->
        </form>
        </div>
        <!-- 'zf-templateWidth' ends -->
    </body>

    </html>