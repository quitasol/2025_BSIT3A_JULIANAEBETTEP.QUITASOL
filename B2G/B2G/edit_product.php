<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

$supplier_id = $_SESSION['user_id'];

// Check if the product ID is passed in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch the product details based on product ID and supplier ID
    $sql = "SELECT * FROM products WHERE product_id = ? AND supplier_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $product_id, $supplier_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        // If the product does not exist or does not belong to the current supplier
        echo "Product not found or you don't have permission to edit it.";
        exit();
    }
} else {
    // If no product ID is provided in the URL
    echo "Product ID not specified.";
    exit();
}

// Handle form submission for editing the product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category']; // Capture the category field

    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $target_dir = "../uploads/";

        // Ensure the uploads directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
        }

        $target_file = $target_dir . basename($image_name);
        
        if (move_uploaded_file($image_tmp, $target_file)) {
            // Optionally, delete the old image from the server
            $old_image_path = "../uploads/" . $product['image'];
            if (file_exists($old_image_path)) {
                unlink($old_image_path); // Delete the old image file
            }
        } else {
            echo "Failed to upload image.";
            exit();
        }
    } else {
        // Keep the old image if no new one is uploaded
        $image_name = $product['image'];
    }

    // Update the product in the database
    $sql = "UPDATE products SET product_name = ?, description = ?, price = ?, category = ?, image = ? WHERE product_id = ? AND supplier_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsisi", $product_name, $description, $price, $category, $image_name, $product_id, $supplier_id);

    if ($stmt->execute()) {
        header("Location: supplier.php");
        exit();
    } else {
        echo "Error updating product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
        <h1>Edit Product</h1>

        <form method="POST" enctype="multipart/form-data">
            <label>Product Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['product_name']) ?>" required>

            <label>Description:</label>
            <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

            <label>Price (₱):</label>
            <input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>" step="0.01" required>

            <label>Category:</label>
<select name="category" required>
    <option value="writing-instruments" <?= ($product['category'] == 'writing-instruments') ? 'selected' : '' ?>>Writing Instruments</option>
    <option value="paper-products" <?= ($product['category'] == 'paper-products') ? 'selected' : '' ?>>Paper Products</option>
    <option value="office-tools" <?= ($product['category'] == 'office-tools') ? 'selected' : '' ?>>Office Tools</option>
    <option value="binders-organizers" <?= ($product['category'] == 'binders-organizers') ? 'selected' : '' ?>>Binders and Organizers</option>
    <option value="technology-supplies" <?= ($product['category'] == 'technology-supplies') ? 'selected' : '' ?>>Technology Supplies</option>
    <option value="desk-accessories" <?= ($product['category'] == 'desk-accessories') ? 'selected' : '' ?>>Desk Accessories</option>
    <option value="mailing-supplies" <?= ($product['category'] == 'mailing-supplies') ? 'selected' : '' ?>>Mailing Supplies</option>
</select>


            <label>Upload Image:</label>
            <input type="file" name="image">

            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>

</body>
</html>
