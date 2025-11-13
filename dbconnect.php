<?php
// Create connection to database
$mysqli = new mysqli("localhost","2584800","Corben072025!","db2584800");

//if connection fails, show error message
if ($mysqli -> connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();
}		
?>