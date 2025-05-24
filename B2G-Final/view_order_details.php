<?php
session_start();
require 'db.php';

// Only allow suppliers to view their own orders
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    echo "No order selected.";
    exit();
}

$order_id = $_GET['order_id'];
$supplier_id = $_SESSION['user_id'];

// Fetch the order from the database
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND supplier_id = ?");
$stmt->bind_param("si", $order_id, $supplier_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "Order not found or you do not have permission to view this order.";
    exit();
}

// Decode details JSON if present
$details = [];
if (!empty($order['details'])) {
    $details = json_decode($order['details'], true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 40px; }
        .details-container { background: #fff; max-width: 600px; margin: 40px auto; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px #ccc; }
        h2 { color: #007bff; }
        .row { margin-bottom: 12px; }
        .label { font-weight: bold; }
        .back-btn { margin-top: 30px; padding: 10px 24px; background: #007bff; color: #fff; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; text-decoration: none; }
        .back-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="details-container">
        <h2>Order Details</h2>
        <div class="row"><span class="label">Order ID:</span> <?php echo htmlspecialchars($order['order_id']); ?></div>
        <div class="row"><span class="label">Customer Name:</span> <?php echo htmlspecialchars($order['name']); ?></div>
        <div class="row"><span class="label">Email:</span> <?php echo htmlspecialchars($order['email']); ?></div>
        <div class="row"><span class="label">Phone:</span> <?php echo htmlspecialchars($order['phone']); ?></div>
        <div class="row"><span class="label">Agency:</span> <?php echo htmlspecialchars($order['agency']); ?></div>
        <div class="row"><span class="label">Total:</span> ₱<?php echo number_format($order['total'], 2); ?></div>
        <div class="row"><span class="label">Status:</span> <?php echo htmlspecialchars($order['status']); ?></div>
        <div class="row"><span class="label">Payment Status:</span> <?php echo htmlspecialchars($order['payment_status']); ?></div>
        <div class="row"><span class="label">Created At:</span> <?php echo htmlspecialchars($order['created_at']); ?></div>
        <?php if (!empty($details)): ?>
            <div class="row"><span class="label">Delivery Address:</span> <?php echo htmlspecialchars($details['delivery_address'] ?? ''); ?></div>
            <div class="row"><span class="label">Reference Number:</span> <?php echo htmlspecialchars($details['reference_number'] ?? ''); ?></div>
            <div class="row"><span class="label">Special Instructions:</span> <?php echo htmlspecialchars($details['special_instructions'] ?? ''); ?></div>
        <?php endif; ?>
        <a href="supplier_orders.php" class="back-btn">Back to Orders</a>
    </div>
</body>
</html>