<?php
session_start();
require 'db.php';

if (isset($_GET['id'])) {
    $quoteId = intval($_GET['id']);

    // Accept the quote
    $updateSql = "UPDATE quotes SET status = 'accepted' WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("i", $quoteId);

    if ($stmt->execute()) {
        // Insert notification for government
        $notifMsg = "A quote has been accepted and is ready for checkout.";
        $notifSql = "INSERT INTO notifications (user_type, message, quote_id, created_at) VALUES ('government', ?, ?, NOW())";
        $notifStmt = $conn->prepare($notifSql);
        $notifStmt->bind_param("si", $notifMsg, $quoteId);
        $notifStmt->execute();

        echo "<script>alert('Quote accepted successfully.'); window.history.back();</script>";
    } else {
        echo "<script>alert('Failed to accept quote.'); window.history.back();</script>";
    }

    $stmt->close();
    $notifStmt->close();
}

$conn->close();
?>
