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

    // Update your SQL to fetch the image
    $sql = "SELECT product_id, product_name, price, supplier_id, image FROM products WHERE product_id = $productId LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $newSupplierId = $product['supplier_id']; // get supplier ID from product

    // Enforce single-supplier rule
    if (!empty($_SESSION['cart'])) {
        $existingSupplierId = $_SESSION['cart'][0]['supplier_id'];
        if ($newSupplierId != $existingSupplierId) {
            // Reject adding product from a different supplier
            echo "<script>alert('You can only add items from one supplier per quote.'); window.location.href='government.php';</script>";
            exit();
        }
    }

        // Check if product is already in cart
        $productInCart = false;
        foreach ($_SESSION['cart'] as $index => $cartItem) {
            if ($cartItem['product_id'] == $productId) {
                $_SESSION['cart'][$index]['quantity'] += $quantity;
                $productInCart = true;
                break;
            }
        }

        // Add product if not already in cart
        if (!$productInCart) {
            $_SESSION['cart'][] = [
                'product_id' => $product['product_id'],
                'product_name' => $product['product_name'],
                'quantity' => $quantity,
                'price' => $product['price'],
                'supplier_id' => $newSupplierId,
                'product_image' => $product['image'] // <-- Add this line
            ];
        }

        // Set session supplier_id for use in the quote
        $_SESSION['supplier_id'] = $newSupplierId;
    }


    // Redirect to the main page after adding product
    header('Location: government.php');
    exit();
}

// Add this block to handle quantity updates
if (isset($_POST['update_quantity'])) {
    $index = (int)$_POST['index'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$index])) {
        if ($action === 'increase') {
            $_SESSION['cart'][$index]['quantity']++;
        } elseif ($action === 'decrease' && $_SESSION['cart'][$index]['quantity'] > 1) {
            $_SESSION['cart'][$index]['quantity']--;
        }
    }
    // Redirect to avoid form resubmission
    header('Location: cart.php');
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

        .quantity-btn {
            background-color: #0073e6;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }

        .quantity-btn:hover {
            background-color: #005bb5;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">GovShop</div>
    <a href="logout.php" class="logout">Logout</a>
</header>

<div class="container">
    <h1>Your Quote List</h1>

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
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td>₱<?php echo number_format($item['price'], 2); ?></td>
                <td>
                    <form method="POST" action="cart.php" style="display: inline;">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <input type="hidden" name="action" value="decrease">
                        <button type="submit" name="update_quantity" class="quantity-btn">-</button>
                    </form>
                    <?php echo $item['quantity']; ?>
                    <form method="POST" action="cart.php" style="display: inline;">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <input type="hidden" name="action" value="increase">
                        <button type="submit" name="update_quantity" class="quantity-btn">+</button>
                    </form>
                </td>
                <td><a href="cart.php?remove_item=<?php echo $index; ?>" class="remove-btn">Remove</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Total: ₱<?php echo number_format($total, 2); ?></h2>

    <a href="checkout.php" class="checkout-btn">Proceed</a>

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
