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
$cn = $fn = $ln = $em = $pn = $sa = $sal2 = $ct = $st = $zc = $cut = $pcm = $cw = $as = $ic = $ec = $bc = '';

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cn = trim($_POST["CustName"]);
    $fn = trim($_POST["Name_First"]);
	  $ln = trim($_POST["Name_Last"]);
		$em = trim($_POST["Email"]);
	  $pn = trim($_POST["PhoneNumber_countrycode"]);
	  $sa = trim($_POST["Address_AddressLine1"]);
	  $sal2 = trim($_POST["Address_AddressLine2"]);
	  $ct = trim($_POST["Address_City"]);
	  $st = trim($_POST["Address_Region"]);
	  $zc = trim($_POST["Address_ZipCode"]);
    $cut = trim($_POST["Address_Country"]);
	  $pcm = trim($_POST["Radio"]);
	  $cw = trim($_POST["Website"]);
	  $bc = trim($_POST["Date"]);
	  $as = trim($_POST["Dropdown"]);
	  $ic = trim($_POST["MultiLine"]);
	  $ec = trim($_POST["MultiLine1"]);
	 
    // Prepare an insert statement
    $sql = "INSERT INTO Address (AddressLine1, AddressLine2, City, State, PostalCode, Country) VALUES (?,?,?,?,?,?)";
    
    $stmter = $conn->prepare($sql);
    // Bind variables to the prepared statement as parameters
    $stmter->bind_param("ssssss", $param_a1, $param_a2, $param_c, $param_s, $param_z, $param_ct);
    // Set parameters
    $param_a1 = $sa;
    $param_a2 = $sal2;
    $param_c = $ct;
    $param_s = $st;
    $param_z = $zc;
    $param_ct = $cut;
    
    // Attempt to execute the prepared statement
    if ($stmter->execute()) {
			  $stmter->close();
				$sql = "INSERT INTO  PersonInfo (FirstName,LastName, Phone, Email, InternalComments, ExternalComments, AddressID) VALUES (?,?,?,?,?,?,?)";

				$stmt2 = $conn->prepare($sql);
				// Bind variables to the prepared statement as parameters
				$stmt2->bind_param("sssssss", $param_fn, $param_ln, $param_pn, $param_em, $param_ic,$param_ec, $param_lastID);
				// Set parameters
				$param_fn = $fn;
				$param_ln = $ln;
				$param_pn = $pn;
				$param_em = $em;
				$param_ic = $ic;
				$param_ec = $ec;
				$param_lastID = getLastId($conn, 'Address');
		    if ($stmt2->execute()) {
					$stmt2->close();
					$sql = "INSERT INTO  Customers (PersonID,CompanyInfo,PrimaryContactMethod,CustAcctStat,custWebsite,InternalComments, ExternalComments, PayTerms) VALUES (?,?,?,?,?,?,?,?)";
					$stmt3 = $conn->prepare($sql);
					// Bind variables to the prepared statement as parameters
					$stmt3->bind_param("ssssssss",$param_lastID2, $param_cn, $param_pcm, $param_as,$param_cw , $param_ic, $param_ec,$param_bc);
					// Set parameters
					$param_lastID2 = getLastId($conn, 'PersonInfo');
					$param_cn = $cn;
					$param_pcm = $pcm;
					$param_as = $as;
					$param_cw = $cw;
					$param_ic = $ic;
					$param_ec = $ec;
					$param_bc = $bc;
					if ($stmt3->execute()) {
								$stmt3->close();
					      header("location: success.php");
                exit;
					} else {echo "Something went wrong. Please try again later.";}
				} else {echo "Something went wrong. Please try again later.";}
    } else {echo "Something went wrong. Please try again later.";}
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
<?php require_once "menuAdmin.php"; ?>
<title>Client Details</title>
<link href="css/form.css" rel="stylesheet" type="text/css"><script src="js/validation.js"></script></head><body class="zf-backgroundBg"><!-- Change or deletion of the name attributes in the input tag will lead to empty values on record submission-->
<div class="zf-templateWidth"><form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' name='form' method='POST' onSubmit='javascript:document.charset="UTF-8"; return zf_ValidateAndSubmit();' accept-charset='UTF-8' enctype='multipart/form-data' id='form'>
<div class="zf-templateWrapper">
<ul class="zf-tempHeadBdr"><li class="zf-tempHeadContBdr"><h2 class="zf-frmTitle"><em>Client Details</em></h2>
<p class="zf-frmDesc">Enter the details of all incoming clients.</p>
<div class="zf-clearBoth"></div></li></ul>
<!---------template Container Starts Here---------->
<div class="zf-subContWrap zf-leftAlign"><ul>
<!---------Single Line Starts Here---------->
<li class="zf-tempFrmWrapper zf-medium"><label class="zf-labelName">Company 
<em class="zf-important">*</em>
</label>
<div class="zf-tempContDiv"><span> 
<input type="text" name="CustName" checktype="c1" value="" maxlength="255" invlovedinsalesiq=false /> </span><p id="SingleLine1_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Single Line Ends Here---------->
<!---------Name Starts Here----------> 
<li class="zf-tempFrmWrapper zf-name zf-namemedium"><label class="zf-labelName">Name
<em class="zf-important">*</em>
</label>
<div class="zf-tempContDiv"><div>				
<span> <input type="text" maxlength="255" name="Name_First" invlovedinsalesiq=false fieldType=7 checktype="c1"/><label>First</label> </span>
<span class="zf-last"> <input type="text" maxlength="255" invlovedinsalesiq=false fieldType=7 name="Name_Last" checktype="c1"/><label>Last</label> </span>
<div class="zf-clearBoth"></div></div><p id="Name_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Name Ends Here----------> 
<!---------Email Starts Here---------->  
<li class="zf-tempFrmWrapper zf-medium"><label class="zf-labelName">Email 
<em class="zf-important">*</em>
</label>
<div class="zf-tempContDiv"><span> <input fieldType=9 invlovedinsalesiq=false type="text" maxlength="255" name="Email" checktype="c5" value=""/></span> <p id="Email_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Email Ends Here---------->  
<!---------Phone Starts Here----------> 
<li  class="zf-tempFrmWrapper zf-small"><label class="zf-labelName">Phone
<em class="zf-important">*</em>
</label>
<div class="zf-tempContDiv"><div>
<input invlovedinsalesiq=false type="text" name="PhoneNumber_countrycode" compname="PhoneNumber" checktype="c7" phoneFormat="1" maxlength="10" value="" id="international_PhoneNumber_countrycode" fieldType="11"/>
<div class="zf-clearBoth"></div></div><p id="PhoneNumber_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Phone Ends Here----------> 
<!--address-->
<!---------Address Starts Here---------->  
<li class="zf-tempFrmWrapper zf-address zf-addrlarge " ><label class="zf-labelName">Company address
<em class="zf-important">*</em>
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
</div></div><div class="zf-eclearBoth"></div></li><!---------Address Ends Here---------->
<!---------Radio Starts Here---------->    
<li class="zf-radio zf-tempFrmWrapper zf-sideBySide"><label class="zf-labelName">Preferred contact method
<em class="zf-important">*</em>
</label>
<div class="zf-tempContDiv"><div class="zf-overflow">
<span class="zf-multiAttType"> 
<input class="zf-radioBtnType" type="radio" id="Radio_1" name="Radio" checktype="c1" value="1">
<label for="Radio_1" class="zf-radioChoice">Email</label> </span>
<span class="zf-multiAttType"> 
<input class="zf-radioBtnType" type="radio" id="Radio_2" name="Radio" checktype="c1" value="0">
<label for="Radio_2" class="zf-radioChoice">Phone</label> </span>
<div class="zf-clearBoth"></div></div><p id="Radio_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Radio Ends Here---------->    
<!---------Website Starts Here---------->
<li class="zf-tempFrmWrapper zf-medium"><label class="zf-labelName">Company website 
</label>
<div class="zf-tempContDiv"><span> <input type="text" maxlength="2083" name="Website" checktype="c6" value=""/></span> <p id="Website_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Website Ends Here---------->
<!---------Dropdown Starts Here---------->
<li class="zf-tempFrmWrapper zf-small"><label class="zf-labelName">Account Status
<em class="zf-important">*</em>
</label>
<div class="zf-tempContDiv"><select class="zf-form-sBox" name="Dropdown" checktype="c1"><option selected="true" value="-Select-">-Select-</option>
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
<option value="Pending">Pending</option>
</select><p id="Dropdown_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Dropdown Ends Here---------->
<!---------Date Starts Here----------> 
<li class="zf-tempFrmWrapper zf-date"><label class="zf-labelName">Billing Cycle 
<em class="zf-important">*</em>
</label>
<div class="zf-tempContDiv"><span> <input type="text" name="Date" checktype="c4" value="" maxlength="15" /><label>dd-MMM-yyyy</label> </span><div class="zf-clearBoth"></div><p id="Date_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Date Ends Here----------> 
<!---------Multiple Line Starts Here---------->
<li class="zf-tempFrmWrapper zf-medium"><label class="zf-labelName">Internal Comments 
</label>
<div class="zf-tempContDiv"><span> <textarea name="MultiLine" checktype="c1" maxlength="65535"></textarea> </span><p id="MultiLine_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Multiple Line Ends Here---------->
<!---------Multiple Line Starts Here---------->
<li class="zf-tempFrmWrapper zf-medium"><label class="zf-labelName">External Comments 
</label>
<div class="zf-tempContDiv"><span> <textarea name="MultiLine1" checktype="c1" maxlength="65535"></textarea> </span><p id="MultiLine1_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li><!---------Multiple Line Ends Here---------->
</ul></div><!---------template Container Starts Here---------->
<ul><li class="zf-fmFooter"><button class="zf-submitColor" >Submit</button></li></ul></div><!-- 'zf-templateWrapper' ends --></form></div><!-- 'zf-templateWidth' ends -->
<script type="text/javascript">var zf_DateRegex = new RegExp("^(([0][1-9])|([1-2][0-9])|([3][0-1]))[-](Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)[-](?:(?:19|20)[0-9]{2})$");
var zf_MandArray = [ "SingleLine1", "Name_First", "Name_Last", "Email", "PhoneNumber_countrycode", "Address_AddressLine1", "Address_City", "Address_Region", "Address_ZipCode", "Address_Country", "Radio", "Dropdown", "Date"]; 
var zf_FieldArray = [ "SingleLine1", "Name_First", "Name_Last", "Email", "PhoneNumber_countrycode", "Address_AddressLine1", "Address_City", "Address_Region", "Address_ZipCode", "Address_Country", "Radio", "Website", "Dropdown", "Date", "MultiLine", "MultiLine1"]; 
var isSalesIQIntegrationEnabled = false;</script></body></html>