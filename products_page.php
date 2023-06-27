<?php

session_start(); // Start the session




error_reporting(E_ALL);
ini_set('display_errors', 1);


$conn = include "connection.php";
function getProductsByCategory($category)
{
    $conn = include "connection.php";

    $sql = "SELECT * FROM Product WHERE category_id = $category";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            // Access the product data
            $productID = $row["product_id"];
            $productName = $row["name"];
            $productPrice = $row["price"];
            $categoryId = $row["category_id"];
            $productQ = $row["quantity"];
            $productDes = $row["description"];


            echo '<div class="product">';
            echo '<div class="product-image">';
            // echo '<img src="images/laptop.jpeg" alt="Product 1">';
            echo '</div>';
            echo '<div class="product-details">';
            echo '<h2 class="product-title">' . $productName . '</h2>';
            echo '<p class="product-description">' . $productDes;
            echo '.</p>';
           
            echo '<div class="product-add-to-cart">';
            echo '<form method="post">';
            echo '<p class="price">' . $productPrice . '</p>';
            echo '<input type="hidden" name="price" value ="' . $productPrice . '">';
            echo '<input type="hidden" name="product_id" value ="' . $productID . '">';
            echo '<input type="hidden"  name="name" value="' . $productName . '">';
            echo '<input type="number" name="quantity" value="1" min="1" max="10">';
            echo '<button name="add_to_cart" type="submit"> Add to cart</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';




        }
    } else {
        echo "No products found.";
    }
}


$conn = include "connection.php";


if (isset($_POST['add_to_cart'])) {
    // Get the product ID from the form submission


    if (isset($_SESSION["userId"])) {
        $userId = $_SESSION["userId"];
        echo $userId;
    }

    $productID = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $productName = $_POST['name'];
    $price = $_POST['price'];

    $cartID = null;
    $checkCartQuery = "SELECT cart_id FROM Cart WHERE user_id = $userId";
    $checkCartResult = $conn->query($checkCartQuery);

    if ($checkCartResult->num_rows > 0) {
        $cartRow = $checkCartResult->fetch_assoc();
        $cartID = $cartRow['cart_id'];
    } else {
        // Create a new cart for the user if no cart exists
        $createCartQuery = "INSERT INTO Cart (user_id) VALUES ($userId)";
        $conn->query($createCartQuery);
        $cartID = $conn->insert_id;
    }

    $_SESSION["cart_id"] = $cartRow['cart_id'];




    $sql = "INSERT INTO CartItem (cart_id,product_id, quantity, name,price) VALUES ($cartID,$productID, $quantity, '$productName',$price)";
    
    
    

    

    if ($conn->query($sql) == TRUE) {
        echo "Cart item added successfully.";
    } else {
        echo "Error adding cart item: " . $conn->error;
    }
}


function getUserName()
{

    if (isset($_SESSION["username"])) {
        $usernamee = $_SESSION["username"];

        echo $usernamee;
    } else {
        // Redirect to the sign-in page if the username is not available
        header("Location: login_page.php");
        exit;
    }
}

?>



<!DOCTYPE html>
<html>

<head>
    <title>Products Page</title>
    <style>
        .h {
            text-align: center;
        }

        .toLetf {
            float: right;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 20px;
        }

        .product {
            display: flex;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .product-image {
            flex: 0 0 200px;
            margin-right: 10px;
        }

        .product-image img {
            max-width: 100%;
        }

        .product-details {
            flex-grow: 1;
        }

        .product-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-description {
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-add-to-cart {
            margin-top: 10px;
        }

        .product-add-to-cart button {
            padding: 8px 16px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .product-add-to-cart button:hover {
            background-color: #45a049;
        }

        /* CSS Styling for the Products Page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .tabs {
            background-color: #f2f2f2;
            overflow: hidden;
        }

        .tabs button {
            background-color: inherit;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        .tabs button:hover {
            background-color: #ddd;
        }

        .tabs button.active {
            background-color: #ccc;
        }

        .tab-content {
            display: none;
            padding: 20px;
        }

        .tab-content.active {
            display: block;
        }
    </style>
</head>

<body>
    <div class="tabs">
        <button class="tab-link active" onclick="openTab(event, 'home')">Home</button>
        <button class="tab-link" onclick="openTab(event, 'electronics')">Electronics</button>
        <button class="tab-link" onclick="openTab(event, 'clothing')">Clothing</button>
        <a class="toLetf" href="orders_page.php">
            <button class="tab-link">
                <h3>Orders page</h3>
            </button>
        </a>
        <br>
        <br>
        <br>
        <p class="h">HI
            <?php getUserName() ?>
        </p>

    </div>

    <div id="home" class="tab-content active">
        <!-- Products under Home category -->
        <h2>Home Products</h2>
        <div class="container">
            <?php getProductsByCategory(3); ?>
            <a href="cart_page.php">
                <button>Go to cart</button>
            </a>

        </div>
    </div>

    <div id="electronics" class="tab-content">
        <!-- Products under Electronics category -->
        <h2>Electronics Products</h2>
        <div class="container">
            <?php getProductsByCategory(1); ?>
            <a href="cart_page.php">
                <button>Go to cart</button>
            </a>
        </div>
    </div>

    <div id="clothing" class="tab-content">
        <!-- Products under Clothing category -->
        <h2>Clothing Products</h2>
        <div class="container">
            <?php getProductsByCategory(2); ?>

            <a href="cart_page.php">
                <button>Go to cart</button>
            </a>

        </div>

    </div>
    </div>

    <script>
        // JavaScript to handle tab switching
        function openTab(evt, tabName) {
            var i, tabContent, tabLinks;
            tabContent = document.getElementsByClassName('tab-content');
            for (i = 0; i < tabContent.length; i++) {
                tabContent[i].style.display = 'none';
            }
            tabLinks = document.getElementsByClassName('tab-link');
            for (i = 0; i < tabLinks.length; i++) {
                tabLinks[i].className = tabLinks[i].className.replace(' active', '');
            }
            document.getElementById(tabName).style.display = 'block';
            evt.currentTarget.className += ' active';
        }
    </script>
</body>




</html>