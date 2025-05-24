<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $supplier_id = $_SESSION['user_id'];
    
    // Fetch the product details first to check if the product belongs to the supplier
    $sql = "SELECT * FROM products WHERE product_id = ? AND supplier_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $product_id, $supplier_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
        // Delete the product
        $delete_sql = "DELETE FROM products WHERE product_id = ? AND supplier_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $product_id, $supplier_id);

        if ($delete_stmt->execute()) {
            // Optionally delete the product image if it exists on the server
            $image_path = "../uploads/" . $product['image'];
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the image file
            }
            // Redirect back to supplier dashboard after deletion
            header("Location: supplier.php");
            exit();
        } else {
            echo "Error deleting product.";
        }
    } else {
        echo "Product not found or you don't have permission to delete it.";
    }
} else {
    echo "Product ID not provided.";
}
?>
