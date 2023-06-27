<?php

session_start();



echo '<h1>Cart</h1>';


error_reporting(E_ALL);
ini_set('display_errors', 1);

function getCartItems()
{
    $conn = include "connection.php";


    if (isset($_SESSION["userId"])) {
        $userId = $_SESSION["userId"];
        // echo $userId;
    }

    $sqlCart = "SELECT cart_id FROM Cart WHERE user_id = $userId";
    $resultCart = $conn->query($sqlCart);

    if ($resultCart->num_rows > 0) {
        $rowCart = $resultCart->fetch_assoc();
        $cartID = $rowCart["cart_id"];

        $sql = "SELECT name, cart_item_id, product_id, quantity, price FROM CartItem WHERE cart_id = $cartID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $name = $row["name"];
                $cartID = $row["cart_item_id"];
                $productID = $row["product_id"];
                $quantity = $row["quantity"];
                $price = $row["price"];

                echo '<div class="cart-item">';
                echo '<img src="images/t-shirt.jpeg" alt="Item 1" class="item-image">';
                echo '<div class="item-details">';
                echo '<div class="item-name">' . $name . '</div>';
                echo '<div class="item-price">' . $quantity . '</div>';
                echo '<div class="item-name">' . $price . '</div>';
                echo '</div>';
                echo '<form method="post">';
                echo '<input type="hidden" name="cart_item_id" value="' . $cartID . '">';
                echo '<button type="submit" name="delete_button" class="delete-button">Delete</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "No items found in the cart.";
        }

    } else {
        echo "you do not have any";
    }


    $conn->close();
}

getCartItems();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["delete_button"])) {
        $cartItemID = $_POST["cart_item_id"];




        $conn = include "connection.php";

        $sql = "DELETE FROM CartItem WHERE cart_item_id = $cartItemID";

        if ($conn->query($sql) === TRUE) {
            echo "Cart item deleted successfully.";
        } else {
            echo "Error deleting cart item: " . $conn->error;
        }

        $conn->close();
    }
}


if (isset($_POST['add_to_orders'])) {

    // Connect to the MySQL database
    $conn = include "connection.php";

    $cart_id = $_SESSION["cart_id"];
    $userID = $_SESSION["userId"];

    echo $cart_id;

    $sql = "SELECT * FROM CartItem WHERE cart_id = $cart_id";


    $result = $conn->query($sql);

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch the data from the result
        $row = $result->fetch_assoc();

        // Access the retrieved data
        $name = $row["name"];
        $productID = $row["product_id"];
        $quantity = $row["quantity"];
        $price = $row["price"];

        $addOrderItemSQL = "INSERT INTO `OrderItem` (product_id, quantity, price) VALUES ($productID, $quantity, $price)";

        if ($conn->query($addOrderItemSQL) == TRUE) {
            echo "Order item added successfully.";

            $orderDate = date("Y-m-d H:i:s");
            $totalAmount = $price * $quantity;


            $sql = "INSERT INTO `Order` (user_id, order_date, total_amount, status)
            VALUES ($userID, '$orderDate', $totalAmount, 'pending')";


            if ($conn->query($sql) === TRUE) {
                // Query executed successfully
            } else {
                echo "Error inserting order: " . $conn->error;
            }


            header("Location: orders_page.php");


        } else {
            echo "Error adding order item: " . $conn->error;
        }


        $conn->close();


    }
}

?>




<!DOCTYPE html>
<html>

<head>
    <title>Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .item-details {
            flex: 1;
            padding-left: 10px;
        }

        .item-name {
            font-weight: bold;
        }

        .item-price {
            color: #888;
        }

        .delete-button {
            background-color: #ff0000;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .checkout-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            background-color: #008000;
            color: #fff;
            border: none;
            padding: 10px;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <form method="post">
        <button class="checkout-button" type="submit" name="add_to_orders">
            Place the order?
        </button>
    </form>
    <a href="products_page.php"> back to products page </a>
</body>

</html>