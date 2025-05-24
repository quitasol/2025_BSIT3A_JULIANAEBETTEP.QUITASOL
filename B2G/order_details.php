<?php
session_start();
require 'db.php';

if (!isset($_GET['order_id'])) {
    echo "Order ID is missing.";
    exit;
}

$order_id = intval($_GET['order_id']);
$stmt = $conn->prepare("
    SELECT o.*, q.product_list, q.total_price AS quote_price, g.gov_name 
    FROM orders o
    JOIN quotes q ON o.quote_id = q.quote_id
    JOIN government g ON o.government_id = g.gov_id
    WHERE o.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Order not found.";
    exit;
}

$order = $result->fetch_assoc();
?>

<h2>Order Summary</h2>
<p><strong>Order ID:</strong> <?= $order['order_id'] ?></p>
<p><strong>Government:</strong> <?= htmlspecialchars($order['gov_name']) ?></p>
<p><strong>Quote ID:</strong> <?= $order['id'] ?></p>
<p><strong>Items:</strong> <?= nl2br(htmlspecialchars($order['product_list'])) ?></p>
<p><strong>Total Price:</strong> ₱<?= number_format($order['quote_price'], 2) ?></p>
<p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
<p><strong>Created At:</strong> <?= $order['created_at'] ?></p>

<a href="dashboard.php">Back to Dashboard</a>

<?php
$conn->close();
?>
