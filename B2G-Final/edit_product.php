<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

$supplier_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $sql = "SELECT * FROM products WHERE product_id = ? AND supplier_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $product_id, $supplier_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "Product not found or you don't have permission to edit it.";
        exit();
    }
} else {
    echo "Product ID not specified.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $image_name = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "uploads/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($image_tmp, $target_file)) {
            // Delete the old image if it's different and not "0"
            $old_image_path = $target_dir . $product['image'];
            if (file_exists($old_image_path) && $product['image'] !== $image_name && $product['image'] !== "0") {
                unlink($old_image_path);
            }
        } else {
            echo "Failed to upload image.";
            exit();
        }
    } else {
        // Keep old image, but if it's "0", treat it as null/empty
        if ($image_name === "0") {
            $image_name = NULL;
        }
    }

    $sql = "UPDATE products SET product_name = ?, description = ?, price = ?, category = ?, image = ? WHERE product_id = ? AND supplier_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssii", $product_name, $description, $price, $category, $image_name, $product_id, $supplier_id);

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
    <style>
        /* Reset and base styles */
body {
    background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
    font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    margin: 0;
    padding: 0;
    min-height: 100vh;
}

/* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }


        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
        }

        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #3498db;
        }
/* Main content styles */
.main-content {
    margin-left: 240px;
    padding: 40px 30px;
    min-height: 100vh;
}

h1 {
    color: #2d3e50;
    font-size: 2.2em;
    margin-bottom: 30px;
    letter-spacing: 1px;
    font-weight: 700;
}

/* Form styles */
form {
    background: #fff;
    padding: 35px 40px 30px 40px;
    border-radius: 18px;
    box-shadow: 0 6px 32px rgba(44,62,80,0.10);
    max-width: 500px;
    margin: 0 auto;
}

label {
    display: block;
    margin-bottom: 8px;
    color: #34495e;
    font-weight: 600;
    font-size: 1.08em;
    margin-top: 18px;
}

input[type="text"],
input[type="number"],
select,
textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid #d1d8e0;
    border-radius: 8px;
    margin-bottom: 10px;
    font-size: 1em;
    background: #f8fafc;
    transition: border 0.2s;
    outline: none;
    resize: none;
}

input[type="text"]:focus,
input[type="number"]:focus,
select:focus,
textarea:focus {
    border-color: #1abc9c;
    background: #f0fdfa;
}

textarea {
    min-height: 90px;
    max-height: 220px;
}

input[type="file"] {
    margin-top: 10px;
    margin-bottom: 18px;
    font-size: 1em;
}

.btn {
    background: linear-gradient(90deg, #1abc9c 0%, #16a085 100%);
    color: #fff;
    border: none;
    padding: 13px 32px;
    border-radius: 8px;
    font-size: 1.08em;
    font-weight: 600;
    cursor: pointer;
    margin-top: 18px;
    box-shadow: 0 2px 8px rgba(44,62,80,0.10);
    transition: background 0.2s, box-shadow 0.2s;
}

.btn:hover {
    background: linear-gradient(90deg, #16a085 0%, #1abc9c 100%);
    box-shadow: 0 4px 16px rgba(44,62,80,0.13);
}

.preview {
    max-width: 180px;
    max-height: 180px;
    border-radius: 8px;
    margin-bottom: 10px;
}

@media (max-width: 900px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        padding-top: 15px;
        box-shadow: none;
    }
    .main-content {
        margin-left: 0;
        padding: 30px 10px;
    }
    form {
        padding: 25px 10px;
    }
}

@media (max-width: 600px) {
    h1 {
        font-size: 1.3em;
    }
    .sidebar h2 {
        font-size: 1.1em;
    }
    form {
        padding: 15px 3vw;
    }
}
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Supplier Dashboard</h2>
        <ul>
            <li><a href="supplier_dashboard.php">Dashboard</a></li>
            <li><a href="addproduct.php">Add Product</a></li>
            <li><a href="supplier_orders.php">Orders</a></li>
            <li><a href="quote_requests.php">Quote Requests</a></li>
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

            <?php if (!empty($product['image'])): ?>
    <p>Current Image:</p>
    <img src="uploads/<?= htmlspecialchars($product['image']) ?>" class="preview" alt="Product Image" style="max-width:180px;max-height:180px;">
<?php endif; ?>
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>

</body>
</html>
