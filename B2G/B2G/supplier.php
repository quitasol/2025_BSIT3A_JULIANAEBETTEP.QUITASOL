<?php
session_start();
require 'db.php';

// Ensure that the user is logged in and is a supplier
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

$supplier_id = $_SESSION['user_id'];

// Fetch products for the supplier
$sql = "SELECT * FROM products WHERE supplier_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if no products are found
if ($result->num_rows === 0) {
    $no_products_message = "No products found. Please check the product insertion process.";
} else {
    $no_products_message = ""; // No message if products exist
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Dashboard</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f6f9;
            display: flex;
            min-height: 100vh;
            justify-content: flex-start;
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

        /* Main Content Styles */
        .main-content {
            margin-left: 270px; /* Sidebar width */
            padding: 20px;
            width: calc(100% - 270px);
        }

        h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
        }

        .btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            text-decoration: none;
            margin-bottom: 20px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #3498db;
            color: white;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table td img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        /* Action Links */
        a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* No products message */
        .no-products-message {
            font-size: 18px;
            color: #e74c3c;
            margin-top: 20px;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            table th, table td {
                padding: 10px;
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
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Manage Your Products</h1>
        <a href="addproduct.php" class="btn">Add New Product</a>

        <!-- Display No Products Message if there are no products -->
        <?php if (!empty($no_products_message)): ?>
            <p class="no-products-message"><?= $no_products_message ?></p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th> <!-- Added Category Column -->
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['product_name'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td>₱<?= $row['price'] ?></td>
                        <td><?= $row['category'] ?></td> <!-- Display Category -->
                        <td><img src="../uploads/<?= $row['image'] ?>" width="80"></td>
                        <td>
                            <!-- Edit and Delete Links -->
                            <a href="edit_product.php?id=<?= $row['product_id'] ?>">Edit</a> | 
                            <a href="delete_product.php?id=<?= $row['product_id'] ?>" 
                               onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>
