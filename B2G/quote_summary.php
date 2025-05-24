<?php
session_start();
require 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the latest quote submitted by the user
$supplier_id = $_SESSION['user_id'];
$sql = "SELECT * FROM quotes WHERE supplier_id = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$result = $stmt->get_result();
$quote = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quote Summary</title>
</head>
<body>
    <h1>Quote Summary</h1>
    <?php if ($quote): ?>
        <p><strong>Agency:</strong> <?= htmlspecialchars($quote['agency']) ?></p>
        <p><strong>Contact Person:</strong> <?= htmlspecialchars($quote['contact_person']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($quote['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($quote['phone']) ?></p>
        <p><strong>Delivery Address:</strong> <?= htmlspecialchars($quote['delivery_address']) ?></p>
        <p><strong>Total:</strong> ₱<?= number_format($quote['total'], 2) ?></p>
        <p><strong>Submitted At:</strong> <?= htmlspecialchars($quote['created_at']) ?></p>
    <?php else: ?>
        <p>No quote found.</p>
    <?php endif; ?>
</body>
</html>