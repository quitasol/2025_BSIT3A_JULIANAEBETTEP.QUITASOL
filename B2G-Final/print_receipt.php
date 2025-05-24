<?php
session_start();
require 'db.php';

// Get order_id from URL
if (!isset($_GET['order_id'])) {
    echo "<p>Invalid request. No order ID provided.</p>";
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order details using the correct column 'id'
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "<p>Order not found.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - Order #<?php echo htmlspecialchars($order['id']); ?></title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding: 40px;
            background-color: #fff;
            color: #333;
        }

        .receipt-container {
            max-width: 600px;
            margin: auto;
            border: 1px solid #ccc;
            padding: 30px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #28a745;
        }

        .receipt-details {
            margin-top: 20px;
        }

        .receipt-details p {
            font-size: 16px;
            margin: 5px 0;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
        }

        .print-button {
            display: block;
            margin: 30px auto 0;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .print-button:hover {
            background-color: #0056b3;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <h2>Order Receipt</h2>

    <div class="receipt-details">
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
        <p><strong>Agency:</strong> <?php echo htmlspecialchars($order['agency']); ?></p>
        <p class="total"><strong>Total Amount:</strong> ₱<?php echo number_format($order['total'], 2); ?></p>
    </div>

    <button class="print-button" onclick="window.print()">Print Receipt</button>
</div>

</body>
</html>
