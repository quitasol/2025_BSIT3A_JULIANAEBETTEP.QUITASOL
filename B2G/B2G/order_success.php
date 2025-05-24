<?php
session_start();
if (!isset($_SESSION['order'])) {
    header("Location: index.php"); // Redirect to homepage if no order found
    exit();
}

// Retrieve order details
$order = $_SESSION['order'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding: 50px;
        }

        .success-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            width: 60%;
            max-width: 500px;
        }

        h2 {
            color: #28a745;
        }

        .order-details {
            text-align: left;
            margin-top: 20px;
        }

        .order-details p {
            margin: 5px 0;
            font-size: 16px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            background: #007bff;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 15px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <div class="success-container">
        <h2>🎉 Order Successfully Placed!</h2>
        <p>Your order has been received. Below are your order details:</p>

        <div class="order-details">
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
            <p><strong>Agency:</strong> <?php echo htmlspecialchars($order['agency']); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['bank']); ?></p>
            <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($order['bank_name']); ?></p>
            <p><strong>Total Amount:</strong> ₱<?php echo number_format($order['total'], 2); ?></p>
        </div>

        <a href="government.php" class="btn">Go to Homepage</a>
    </div>

</body>
</html>
