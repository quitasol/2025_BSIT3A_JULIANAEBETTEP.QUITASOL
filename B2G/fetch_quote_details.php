<?php

require 'db.php';
header('Content-Type: application/json');

if (!isset($_GET['quote_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$quote_id = intval($_GET['quote_id']);

$sql = "SELECT * FROM quotes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quote_id);
$stmt->execute();
$result = $stmt->get_result();

if ($quote = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'quote' => $quote]);
} else {
    echo json_encode(['success' => false]);
}
$conn->close();
?>