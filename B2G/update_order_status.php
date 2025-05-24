<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Mark as paid
    if (isset($_POST['mark_paid'])) {
        $stmt = $conn->prepare("UPDATE orders SET payment_status = 'Paid' WHERE order_id = ?");
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
    }

    // Confirm order
    if (isset($_POST['confirm'])) {
        $stmt = $conn->prepare("UPDATE orders SET status = 'Confirmed' WHERE order_id = ?");
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
    }

    // Reject order
    if (isset($_POST['reject'])) {
        $stmt = $conn->prepare("UPDATE orders SET status = 'Rejected' WHERE order_id = ?");
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
    }
}

header("Location: supplier_orders.php");
exit();
?>
