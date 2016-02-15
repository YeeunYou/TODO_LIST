<?php 
define("HOST", "localhost");
define("USER", "username");
define("PASSWORD", "password");

$conn = new mysqli(HOST, USER, PASSWORD);

if($conn->connect_error)
{
	die("Connection failed" . $conn->connect_error);
}
?>