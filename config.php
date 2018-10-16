<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Ret%ret5ret');
define('DB_NAME', 'CSC540');
/* Function to return connection to DB. 
*/
function get_db_connection(){
	$conn = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	if($conn->connect_error){
		die("Connection failed: " . $conn->connect_error);
	}
	return $conn;
}

?>
