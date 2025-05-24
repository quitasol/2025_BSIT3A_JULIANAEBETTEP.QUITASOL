<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

$supplier_id = $_SESSION['user_id'];

// Fetch total number of products
$sql_products = "SELECT COUNT(*) as total_products FROM products WHERE supplier_id = ?";
$stmt_products = $conn->prepare($sql_products);
$stmt_products->bind_param("i", $supplier_id);
$stmt_products->execute();
$result_products = $stmt_products->get_result();
$total_products = $result_products->fetch_assoc()['total_products'];

// Fetch revenue (if applicable)
$sql_revenue = "SELECT SUM(price) as total_revenue FROM products WHERE supplier_id = ?";
$stmt_revenue = $conn->prepare($sql_revenue);
$stmt_revenue->bind_param("i", $supplier_id);
$stmt_revenue->execute();
$result_revenue = $stmt_revenue->get_result();
$total_revenue = $result_revenue->fetch_assoc()['total_revenue'];

// Fetch products categorized by category (for bar chart)
$sql_category_count = "SELECT category, COUNT(*) as count FROM products WHERE supplier_id = ? GROUP BY category";
$stmt_category = $conn->prepare($sql_category_count);
$stmt_category->bind_param("i", $supplier_id);
$stmt_category->execute();
$result_category = $stmt_category->get_result();

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <li><a href="supplier.php">Dashboard</a></li>
            <li><a href="supplier.php">Manage Product</a></li>
            <li><a href="supplier_orders.php">Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Welcome to Your Dashboard</h1>

        <div class="stat-box">
            <div>
                <h3>Total Products</h3>
                <p><?= $total_products ?></p>
            </div>
            <div>
                <h3>Total Revenue</h3>
                <p>₱<?= number_format($total_revenue, 2) ?></p>
            </div>
        </div>

        <h2>Product Statistics</h2>
        <canvas id="categoryChart" width="400" height="200"></canvas>

        <h2>Revenue Over Time</h2>
        <canvas id="revenueChart" width="400" height="200"></canvas>

        <!-- Display No Products Message if there are no products -->
        <?php if (!empty($no_products_message)): ?>
            <p class="no-products-message"><?= $no_products_message ?></p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['product_name'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td>₱<?= $row['price'] ?></td>
                        <td><img src="../uploads/<?= $row['image'] ?>" width="80"></td>
                        <td>
                            <!-- Edit and Delete Links -->
                            <a href="edit_product.php?id=<?= $row['id'] ?>">Edit</a> | 
                            <a href="delete_product.php?id=<?= $row['id'] ?>" 
                               onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>

    <script>
        // Category distribution chart
        const categoryChartData = {
            labels: [<?php while($row = $result_category->fetch_assoc()) { echo '"' . $row['category'] . '",'; } ?>],
            datasets: [{
                label: 'Product Categories',
                data: [<?php 
                    $result_category->data_seek(0); // Reset result pointer
                    while($row = $result_category->fetch_assoc()) { echo $row['count'] . ','; } ?>],
                backgroundColor: '#3498db',
                borderColor: '#2980b9',
                borderWidth: 1
            }]
        };

        const categoryChartConfig = {
            type: 'bar',
            data: categoryChartData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Revenue over time chart (Example - data should be dynamically generated based on dates)
        const revenueChartData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], // Example months, replace with actual data
            datasets: [{
                label: 'Revenue (₱)',
                data: [5000, 7000, 8000, 6500, 9000, 12000], // Example revenue data
                borderColor: '#2980b9',
                backgroundColor: 'rgba(41, 128, 185, 0.2)',
                fill: true
            }]
        };

        const revenueChartConfig = {
            type: 'line',
            data: revenueChartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        };

        // Render charts
        new Chart(document.getElementById('categoryChart'), categoryChartConfig);
        new Chart(document.getElementById('revenueChart'), revenueChartConfig);
    </script>
</body>
</html>
