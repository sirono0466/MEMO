<?php


$host = "localhost";
$username = "yourrootname";
$password = "yourpassword";
$db = "memo";


$con = mysqli_connect($host, $username, $password);
mysqli_select_db($con, $db) or die(mysqli_error());
?>
