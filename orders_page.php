
<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = include "connection.php";


echo '<body>';
echo '<h1>Orders</h1>';
echo '</body>';

$user_Id = $_SESSION["userId"];


$sql = "SELECT order_id, user_id, total_amount, order_date, status FROM `Order` where user_id= $user_Id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderID = $row["order_id"];
        $userID = $row["user_id"];
        $price = $row["total_amount"];
        $orderDate = $row["order_date"];
        $status = $row["status"];

       
        echo '<div class="order">';
        echo '<h2>Order ID: ' . $orderID . '</h2>';
        echo '<p>User ID: ' . $userID . '</p>';
        echo '<p>Price: ' . $price . '</p>';
        echo '<p>Order Date: ' . $orderDate . '</p>';
        echo '<p>Status: ' . $status . '</p>';
        echo '</div>';
    }
} else {
    echo "No orders found.";
}

$conn->close();
?>






<!DOCTYPE html>
<html>

<head>
    <title>Orders Page</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .order {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }

        .order h2 {
            margin-bottom: 5px;
        }

        .order p {
            margin: 0;
        }
    </style>
</head>

<body>
    <a href="products_page.php">back to products page</a>
</body>

</html>
