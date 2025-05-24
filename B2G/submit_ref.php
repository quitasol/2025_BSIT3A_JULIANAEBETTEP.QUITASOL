<?php
session_start();
require 'db.php';

if (!isset($_GET['order_id'])) {
    echo "<p>Order ID is missing.</p>";
    exit();
}

$order_id = $_GET['order_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref_number = trim($_POST['reference_number']);

    if ($ref_number === '') {
        $error = "Reference number is required.";
    } else {
        $stmt = $conn->prepare("UPDATE orders SET reference_number = ?, payment_status = 'Paid' WHERE id = ?");
        $stmt->bind_param("si", $ref_number, $order_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $success = "Reference number submitted successfully!";
        } else {
            $error = "Failed to update. Order not found or already updated.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Reference Number</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            padding: 40px;
            text-align: center;
        }
        form {
            display: inline-block;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        input[type="text"] {
            padding: 10px;
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        .message {
            margin-bottom: 15px;
            font-weight: bold;
        }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<h2>Submit Payment Reference Number</h2>

<?php if (!empty($error)) echo "<div class='message error'>$error</div>"; ?>
<?php if (!empty($success)) echo "<div class='message success'>$success</div>"; ?>

<form method="POST">
    <input type="text" name="reference_number" placeholder="Enter reference number" required>
    <button type="submit">Submit</button>
</form>

</body>
</html>
