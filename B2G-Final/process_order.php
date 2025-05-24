<?php
session_start();
include 'db.php'; // Ensure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $agency = trim($_POST['agency']);
    $bank = trim($_POST['bank']);
    $bank_name = trim($_POST['bank_name']);

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($agency) || empty($bank) || empty($bank_name)) {
        die("Error: Please fill out all required fields.");
    }

    // Ensure cart is not empty
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        die("Error: Your cart is empty. Please add items before checkout.");
    }

    // Debugging: Print cart data
    echo "<pre>Cart Data:\n";
    print_r($_SESSION['cart']);
    echo "</pre>";

    // Generate a Unique Order ID
    $order_id = uniqid("ORD-");

    // Initialize supplier-wise total tracking
    $supplier_totals = [];

    // Start transaction to ensure consistency
    $conn->begin_transaction();
    try {
        foreach ($_SESSION['cart'] as $index => $item) {
            // Ensure 'product_id' exists
            if (!isset($item['product_id']) && isset($item['id'])) {
                $item['product_id'] = $item['id']; // Fix: Rename 'id' to 'product_id'
            }

            // Debugging: Check each item
            echo "<pre>Checking item $index:\n";
            print_r($item);
            echo "</pre>";

            // Ensure required fields exist
            if (!isset($item['product_id']) || !isset($item['quantity']) || !isset($item['price'])) {
                throw new Exception("Error: Invalid cart data. Missing required fields.");
            }

            $product_id = intval($item['product_id']);
            $quantity = intval($item['quantity']);
            $price = floatval($item['price']);

            // Fetch supplier_id for the product
            $supplierQuery = "SELECT supplier_id FROM products WHERE product_id = ?";
            $supplierStmt = $conn->prepare($supplierQuery);
            $supplierStmt->bind_param("i", $product_id);
            $supplierStmt->execute();
            $supplierResult = $supplierStmt->get_result();

            if ($supplierResult->num_rows > 0) {
                $supplierRow = $supplierResult->fetch_assoc();
                $supplier_id = $supplierRow['supplier_id'];
            } else {
                throw new Exception("Error: Supplier not found for product ID " . $product_id);
            }

            // Accumulate total price for each supplier
            if (!isset($supplier_totals[$supplier_id])) {
                $supplier_totals[$supplier_id] = 0;
            }
            $supplier_totals[$supplier_id] += $price * $quantity;
        }

        // Insert order per supplier with total amount
        foreach ($supplier_totals as $supplier_id => $total) {
            $orderSQL = "INSERT INTO orders (order_id, supplier_id, name, email, phone, agency, bank, bank_name, total, status, created_at)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
            $orderStmt = $conn->prepare($orderSQL);
            $orderStmt->bind_param("sissssssd", $order_id, $supplier_id, $name, $email, $phone, $agency, $bank, $bank_name, $total);
            
            if (!$orderStmt->execute()) {
                throw new Exception("Error: Order insertion failed for supplier ID " . $supplier_id);
            }
        }

        // Commit transaction
        $conn->commit();

        // Clear cart after successful checkout
        unset($_SESSION['cart']);

        // Store order details in session for confirmation page
        $_SESSION['order'] = [
            'order_id' => $order_id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'agency' => $agency,
            'bank' => $bank,
            'bank_name' => $bank_name,
            'total' => array_sum($supplier_totals)
        ];

        // Redirect to success page
        header("Location: order_success.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }
}
?>
