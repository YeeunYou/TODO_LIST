<?php 
define("HOST", "localhost");
define("USER", "emily");
define("PASSWORD", "emilyyou3b");

$conn = new mysqli(HOST, USER, PASSWORD);

if($conn->connect_error)
{
	die("Connection failed" . $conn->connect_error);
}
?>