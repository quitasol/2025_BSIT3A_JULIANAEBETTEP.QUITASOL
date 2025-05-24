<?php
include 'db.php';

// Check if the necessary data is provided
if (isset($_POST['id']) && isset($_POST['status'])) {
    $product_id = $_POST['id'];
    $status = $_POST['status'];

    // Update the product status in the database
    $sql = "UPDATE products SET status = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $product_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }
} else {
    echo "Missing parameters";
}
?>
