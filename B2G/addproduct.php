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
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
/* Reset and base styles */
body {
    background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
    font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
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
    width: 100%;
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
            <li><a href="supplier.php">Manage Product</a></li>
            <li><a href="supplier_orders.php">Orders</a></li>
            <li><a href="quote_requests.php">Quote Requests</a></li>
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
