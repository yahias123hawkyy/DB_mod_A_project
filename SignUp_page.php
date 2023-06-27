<?php


session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


$conn = include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username=  $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $country = $_POST["country"];
    $phone = $_POST["phone"];

    $sql = "INSERT INTO User (username ,email, password, first_name, last_name, address, city, state, country, phone) VALUES ('$username','$email', '$password', '$firstName', '$lastName', '$address', '$city', '$state', '$country', '$phone')";



    if ($conn->query($sql) === TRUE) {
        echo "New record added successfully";
        header("Location: products_page.php");

        $_SESSION["username"]= $username;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();


}

?>