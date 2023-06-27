<?php
$servername = "localhost:3306";
$username = "root";
$password = "12345678";
$name = "ecommerce";

$conn = new mysqli($servername, $username, $password, $name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



return $conn;
?>