<?php


session_start(); 

error_reporting(E_ALL);
ini_set('display_errors', 1);


$post = $_SERVER['REQUEST_METHOD'];



if ($post == 'POST') {



    $username1 = $_POST['username'];
    $password1 = $_POST['password'];
    $connection = include "connection.php";



    $_SESSION["username"] = $username1;



    $sql = "SELECT * FROM User WHERE username = '$username1' AND password = '$password1'";
    $result = $connection->query($sql);


    if ($result->num_rows > 0) {


        $row = $result->fetch_assoc();
        $userId = $row["user_id"];


        $_SESSION["userId"] = $userId;



        echo "Login successful!";
        header("Location: products_page.php");
        exit;
    } else {

        echo "Invalid username or password.";

    }

    $connection->close();

}

?>