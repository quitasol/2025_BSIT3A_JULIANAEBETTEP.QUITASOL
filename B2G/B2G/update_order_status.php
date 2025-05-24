<?php
session_start();
include 'db.php';

// Ensure the supplier is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $status = isset($_POST['confirm']) ? "Confirmed" : "Rejected"; // Determine status

    // Update order status
    $sql = "UPDATE orders SET status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        header("Location: supplier_orders.php"); // Redirect back to dashboard
        exit();
    } else {
        echo "Error updating order: " . $conn->error;
    }
}
?>
