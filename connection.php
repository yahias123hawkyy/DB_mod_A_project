<?php
$servername = "localhost:3306";
$username = "root";
$password = "12345678";
$name= "ecommerce";

// Create a connection
$conn = new mysqli($servername, $username, $password,$name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully";


return $conn;
// Close the connection
?>
