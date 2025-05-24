<?php
session_start();
require 'db.php';

// Ensure that the user is logged in and is a supplier
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

$supplier_id = $_SESSION['user_id']; // Ensure this matches the logged-in supplier's ID

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

        /* Main blue button style */
        .btn {
            background: linear-gradient(90deg, #3498db 0%, #2980b9 100%);
            color: #fff;
            padding: 12px 28px;
            border: none;
            border-radius: 7px;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.08);
            transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
            display: inline-block;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        .btn:hover, .btn:focus {
            background: linear-gradient(90deg, #2980b9 0%, #3498db 100%);
            box-shadow: 0 4px 16px rgba(52, 152, 219, 0.13);
            color: #fff;
            transform: translateY(-2px) scale(1.03);
            text-decoration: none;
        }

        .stat-box {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
        }

        .stat-box div {
            background-color: #3498db;
            color: white;
            padding: 20px;
            border-radius: 10px;
            width: 30%;
            text-align: center;
        }

        .stat-box div h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #3498db;
            color: white;
            font-size: 16px;
            text-transform: uppercase;
        }

        table td {
            font-size: 14px;
            color: #333;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table img {
            max-width: 80px;
            border-radius: 5px;
        }

        .no-products-message {
            font-size: 18px;
            color: #555;
            margin-top: 20px;
        }

        /* Action buttons in the table */
        .action-btn {
            display: inline-block;
            padding: 7px 18px;
            border-radius: 5px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            margin-right: 6px;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 4px rgba(52, 152, 219, 0.08);
        }

        .action-btn.edit {
            background: linear-gradient(90deg, #1abc9c 0%, #16a085 100%);
            color: #fff;
            border: none;
        }

        .action-btn.edit:hover {
            background: linear-gradient(90deg, #16a085 0%, #1abc9c 100%);
            color: #fff;
        }

        .action-btn.delete {
            background: linear-gradient(90deg, #e74c3c 0%, #c0392b 100%);
            color: #fff;
            border: none;
        }

        .action-btn.delete:hover {
            background: linear-gradient(90deg, #c0392b 0%, #e74c3c 100%);
            color: #fff;
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

            .stat-box {
                flex-direction: column;
            }

            .stat-box div {
                width: 100%;
                margin-bottom: 20px;
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
        <h1>Manage Your Products</h1>
        <a href="addproduct.php" class="btn"> + Add New Product</a>

        <!-- Display No Products Message if there are no products -->
        <?php if (!empty($no_products_message)): ?>
            <p class="no-products-message"><?= $no_products_message ?></p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['product_name'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td>₱<?= $row['price'] ?></td>
                        <td><?= $row['category'] ?></td>
                        <td><img src="uploads/<?= htmlspecialchars($row['image']) ?>" width="80" alt="Product Image">
</td>
                        <td>
                            <a href="edit_product.php?id=<?= $row['product_id'] ?>" class="action-btn edit">Edit</a> 
                            <a href="delete_product.php?id=<?= $row['product_id'] ?>" class="action-btn delete"
                               onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>
