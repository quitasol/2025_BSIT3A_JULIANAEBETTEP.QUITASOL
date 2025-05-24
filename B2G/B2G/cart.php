<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add_to_cart'])) {
    $productId = $_GET['add_to_cart'];
    $quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

    require 'db.php'; // Include DB connection

    $sql = "SELECT * FROM products WHERE product_id = $productId LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Check if product is already in cart using array keys
        $productInCart = false;

        foreach ($_SESSION['cart'] as $index => $cartItem) {
            if ($cartItem['id'] == $productId) {
                // Directly update the session array using its key
                $_SESSION['cart'][$index]['quantity'] += $quantity;
                $productInCart = true;
                break;
            }
        }

        // If the product wasn't in the cart, add it
        if (!$productInCart) {
            $_SESSION['cart'][] = [
                'id' => $product['product_id'], // Make sure 'product_id' is used
                'name' => $product['product_name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        }
    }

    // Redirect to the main page after adding product
    header('Location: government.php');
    exit();
}


// Remove product from cart if remove_item is set
if (isset($_GET['remove_item'])) {
    $index = (int)$_GET['remove_item'];
    // Remove the item from the cart by its index
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        // Reindex the array to avoid gaps in the array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    // Redirect to cart page after removing product
    header('Location: cart.php');
    exit();
}

// Handle removal of selected items from the cart
if (isset($_POST['remove_selected']) && isset($_POST['selected_items'])) {
    foreach ($_POST['selected_items'] as $index) {
        // Ensure valid index is selected
        if (isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
        }
    }
    // Reindex the array after removal to avoid gaps
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    // Redirect to avoid resubmission
    header('Location: cart.php');
    exit();
}

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
// Calculate total for cart display
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Basic styles for the cart page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #0073e6;
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .logo {
            font-size: 28px;
            font-weight: bold;
        }

        header .logout {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .container {
            padding: 30px;
        }

        h1 {
            font-size: 28px;
            text-align: center;
            margin-bottom: 20px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .cart-table th, .cart-table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .cart-table th {
            background-color: #0073e6;
            color: white;
        }

        .cart-table td {
            background-color: white;
        }

        .remove-btn {
            color: red;
            cursor: pointer;
            text-decoration: none;
        }

        .checkout-btn {
            background-color: #28a745;
            color: white;
            padding: 15px 30px;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            text-align: center;
            display: block;
            margin: 30px auto;
            text-decoration: none;
        }

        .checkout-btn:hover {
            background-color: #218838;
        }

        .back-btn {
            background-color: #f0ad4e;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background-color: #ec971f;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">GovShop</div>
    <a href="logout.php" class="logout">Logout</a>
</header>

<div class="container">
    <h1>Your Cart</h1>

    <!-- Back Button -->
    <a href="government.php" class="back-btn">Back to Shop</a>

    <?php if (count($_SESSION['cart']) > 0): ?>
    <form method="POST" action="cart.php"> <!-- Form to handle selected checkboxes -->
    <table class="cart-table">
        <tr>
            <th>Select</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>

        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
            <tr>
                <td><input type="checkbox" name="selected_items[]" value="<?php echo $index; ?>"></td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td>₱<?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><a href="cart.php?remove_item=<?php echo $index; ?>" class="remove-btn">Remove</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Total: ₱<?php echo number_format($total, 2); ?></h2>

    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>

    <div>
        <button type="submit" name="remove_selected" class="remove-btn">Remove Selected Items</button>
    </div>
    </form>
<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>
</div>

</body>
</html>
