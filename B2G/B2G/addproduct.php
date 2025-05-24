<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category']; // Capture the category field
    $supplier_id = $_SESSION['user_id'];

    // Image upload
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $target_dir = "../uploads/";

    // Ensure the uploads directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
    }

    $target_file = $target_dir . basename($image_name);
    
    if (move_uploaded_file($image_tmp, $target_file)) {
        // Insert product into database
        $sql = "INSERT INTO products (supplier_id, product_name, description, price, category, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdss", $supplier_id, $product_name, $description, $price, $category, $image_name);

        if ($stmt->execute()) {
            header("Location: supplier.php");
            exit();
        } else {
            echo "Error adding product.";
        }
    } else {
        echo "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="css/add.css">
</head>
<body>

    <div class="sidebar">
        <h2>Supplier Dashboard</h2>
        <ul>
            <li><a href="supplier.php">Dashboard</a></li>
            <li><a href="add_product.php">Add Product</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Add New Product</h1>

        <form method="POST" enctype="multipart/form-data">
            <label>Product Name:</label>
            <input type="text" name="name" required>

            <label>Description:</label>
            <textarea name="description" required></textarea>

            <label>Price (₱):</label>
            <input type="number" name="price" step="0.01" required>

            <label>Category:</label>
<select name="category" required>
    <option value="writing-instruments">Writing Instruments</option>
    <option value="paper-products">Paper Products</option>
    <option value="office-tools">Office Tools</option>
    <option value="binders-organizers">Binders and Organizers</option>
    <option value="technology-supplies">Technology Supplies</option>
    <option value="desk-accessories">Desk Accessories</option>
    <option value="mailing-supplies">Mailing Supplies</option>
</select>


            <label>Upload Image:</label>
            <input type="file" name="image" required>

            <button type="submit" class="btn">Save Product</button>
        </form>
    </div>

</body>
</html>
