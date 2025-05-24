<?php
header('Content-Type: application/json');
require 'db.php';

$sql = "SELECT notif_id, message, quote_id FROM notifications WHERE user_type = 'government' ORDER BY created_at DESC";
$result = $conn->query($sql);
$notifications = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            'notif_id' => $row['notif_id'],
            'message' => $row['message'],
            'quote_id' => $row['quote_id']
        ];
    }
}

echo json_encode($notifications);
$conn->close();
?>
