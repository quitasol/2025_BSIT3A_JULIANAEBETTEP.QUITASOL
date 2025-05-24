<?php
session_start();
require 'db.php';

if (!isset($_GET['quote_id'])) {
    die("Invalid request: No quote ID provided.");
}

$quote_id = intval($_GET['quote_id']);

// Fetch the quote details
$quote = $conn->query("SELECT * FROM quotes WHERE id = $quote_id")->fetch_assoc();
if (!$quote) {
    die("Quote not found.");
}

// Check if an order already exists for this quote
$orderCheck = $conn->query("SELECT * FROM orders WHERE quote_id = $quote_id");
if ($orderCheck->num_rows > 0) {
    header("Location: order_success.php?order_id=" . urlencode($orderCheck->fetch_assoc()['order_id']));
    exit();
}

// Prepare order data from quote
$order_id = uniqid("ORD-");
$name = $quote['contact_person'];
$email = $quote['email'];
$phone = $quote['phone'];
$agency = $quote['agency'];
$total = $quote['total'];
$status = "Pending";
$supplier_id = $quote['supplier_id'];
$created_at = date('Y-m-d H:i:s');

// Store extra details as JSON in the 'details' column
$details = json_encode([
    'delivery_address' => $quote['delivery_address'],
    'reference_number' => $quote['reference_number'],
    'special_instructions' => $quote['special_instructions']
]);

// Insert order into database
$sql = "INSERT INTO orders (order_id, supplier_id, name, email, phone, agency, total, status, created_at, details, quote_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sissssdssss",
    $order_id,
    $supplier_id,
    $name,
    $email,
    $phone,
    $agency,
    $total,
    $status,
    $created_at,
    $details,
    $quote_id
);

if ($stmt->execute()) {
    // Update quote status to 'Ordered'
    $conn->query("UPDATE quotes SET status = 'Ordered' WHERE id = $quote_id");
    header("Location: order_success.php?order_id=" . urlencode($order_id));
    exit();
} else {
    echo "Error: " . $conn->error;
    exit();
}
?>
