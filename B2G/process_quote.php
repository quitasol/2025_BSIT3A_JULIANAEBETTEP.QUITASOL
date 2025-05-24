<?php
session_start();
include 'db.php'; // Include database connection

// Ensure the cart is not empty
if (empty($_SESSION['cart'])) {
    die("Error: Your cart is empty. Please add items to your cart before requesting a quote.");
}

// Extract supplier ID from the cart
$supplier_id = null;
foreach ($_SESSION['cart'] as $item) {
    if ($supplier_id === null) {
        $supplier_id = $item['supplier_id'];
    } elseif ($item['supplier_id'] != $supplier_id) {
        die("Error: All products in your quote request must be from the same supplier.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✅ Collect POST data safely
    $agency = trim($_POST['agency']);
    $contact_person = trim($_POST['contact_person']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $delivery_address = trim($_POST['delivery_address']);
    $reference_number = $_POST['reference_number'] ?? null;
    $special_instructions = $_POST['special_instructions'] ?? null;
    $total = floatval($_POST['total']);

    // ✅ Use supplier ID from session instead of form input
    // $supplier_id = $_SESSION['user_id']; // This line is no longer needed

    // 🛠 Optional: Debugging
    // echo "Supplier ID being inserted: " . $supplier_id . "<br>";

    // ✅ Prepare and execute the SQL query
    $sql = "INSERT INTO quotes (
                agency, contact_person, email, phone, delivery_address,
                reference_number, special_instructions, supplier_id, total, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssds",
        $agency,
        $contact_person,
        $email,
        $phone,
        $delivery_address,
        $reference_number,
        $special_instructions,
        $supplier_id,
        $total
    );

    if ($stmt->execute()) {
        $quote_id = $conn->insert_id; // Get the new quote ID

        // Insert each cart item into quote_items
        $item_sql = "INSERT INTO quote_items (quote_id, product_id, product_name, image, quantity, price) VALUES (?, ?, ?, ?, ?, ?)";
        $item_stmt = $conn->prepare($item_sql);

        foreach ($_SESSION['cart'] as $item) {
            $item_stmt->bind_param(
                "iissid",
                $quote_id,
                $item['product_id'],
                $item['product_name'],
                $item['product_image'], // <-- Use this key
                $item['quantity'],
                $item['price']
            );
            $item_stmt->execute();
        }
        $item_stmt->close();

        unset($_SESSION['cart']);

        // Redirect to view_quote.php with the new quote ID
        header('Location: checkout.php?id=' . $quote_id);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // ✅ Clean up
    $stmt->close();
    $conn->close();
}

// Code to add item to cart
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['add_to_cart'])) {
    $product_id = $_GET['product_id'];
    $product_name = $_GET['product_name'];
    $quantity = $_GET['quantity'];
    $price = $_GET['price'];
    $supplier_id = $_GET['supplier_id'];

    // Validate and sanitize input as needed

    // Add item to session cart
    $_SESSION['cart'][] = [
        'product_id' => $product_id,
        'product_name' => $product_name,
        'quantity' => $quantity,
        'price' => $price,
        'supplier_id' => $supplier_id
    ];

    // Optionally, redirect or respond with success message
    header('Location: cart.php');
    exit();
}
?>