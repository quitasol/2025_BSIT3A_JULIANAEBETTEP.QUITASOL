<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Count total users
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users"))['count'];

// Count total suppliers
$totalSuppliers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users WHERE user_type = 'supplier'"))['count'];

// Count pending orders
$pendingOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM orders WHERE status = 'Pending'"))['count'];

// Total payments processed
$totalPayments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments WHERE status = 'Completed'"))['total'];
$totalPayments = $totalPayments ? "₱" . number_format($totalPayments, 2) : "₱0.00";

// Fetch order list
$orderQuery = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");

// Fetch payment list
$paymentQuery = mysqli_query($conn, "SELECT * FROM payments ORDER BY po_id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <ul>
                    <li><a href="admin.php" class="sidebar-btn">Dashboard</a></li>
                    <li><a href="usermanagement.php" class="sidebar-btn">User Management</a></li>
                    <li><a href="productlisting.php" class="sidebar-btn">Product Listings</a></li>
                    <li><a href="orders.php" class="sidebar-btn">Orders</a></li>
                    <li><a href="payment.php" class="sidebar-btn">Payments</a></li>
                    <li><a href="logout.php" class="sidebar-btn">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header>
                Admin Dashboard
            </header>
            <section class="cards">
                <div class="card">
                    <h3>Total Users</h3>
                    <p><?php echo number_format($totalUsers); ?></p>
                </div>
                <div class="card">
                    <h3>Total Suppliers</h3>
                    <p><?php echo number_format($totalSuppliers); ?></p>
                </div>
                <div class="card">
                    <h3>Pending Orders</h3>
                    <p><?php echo number_format($pendingOrders); ?></p>
                </div>
                <div class="card">
                    <h3>Payments Processed</h3>
                    <p><?php echo $totalPayments; ?></p>
                </div>
            </section>

            <!-- New section for bar graphs -->
            <section class="bar-graphs">
                <h2>Bar Graphs</h2>
                <div class="bar-graph">
                    <h3>Users Overview</h3>
                    <canvas id="usersBarChart"></canvas>
                </div>
                <div class="bar-graph">
                    <h3>Suppliers Overview</h3>
                    <canvas id="suppliersBarChart"></canvas>
                </div>
                <div class="bar-graph">
                    <h3>Orders Overview</h3>
                    <canvas id="ordersBarChart"></canvas>
                </div>
                <div class="bar-graph">
                    <h3>Payments Overview</h3>
                    <canvas id="paymentsBarChart"></canvas>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Data for the charts
        const totalUsers = <?php echo $totalUsers; ?>;
        const totalSuppliers = <?php echo $totalSuppliers; ?>;
        const pendingOrders = <?php echo $pendingOrders; ?>;
        const totalPayments = <?php echo str_replace(',', '', filter_var($totalPayments, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)); ?>;

        // Bar Chart for Users
        new Chart(document.getElementById('usersBarChart'), {
            type: 'bar',
            data: {
                labels: ['Users', 'Others'],
                datasets: [{
                    label: 'Users Overview',
                    data: [totalUsers, 1000 - totalUsers], // Example: 1000 is the total capacity
                    backgroundColor: ['#4CAF50', '#E0E0E0']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Bar Chart for Suppliers
        new Chart(document.getElementById('suppliersBarChart'), {
            type: 'bar',
            data: {
                labels: ['Suppliers', 'Others'],
                datasets: [{
                    label: 'Suppliers Overview',
                    data: [totalSuppliers, 500 - totalSuppliers], // Example: 500 is the total capacity
                    backgroundColor: ['#2196F3', '#E0E0E0']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Bar Chart for Orders
        new Chart(document.getElementById('ordersBarChart'), {
            type: 'bar',
            data: {
                labels: ['Pending Orders', 'Completed Orders'],
                datasets: [{
                    label: 'Orders Overview',
                    data: [pendingOrders, 100 - pendingOrders], // Example: 100 is the total orders
                    backgroundColor: ['#FFC107', '#E0E0E0']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Bar Chart for Payments
        new Chart(document.getElementById('paymentsBarChart'), {
            type: 'bar',
            data: {
                labels: ['Processed Payments', 'Remaining'],
                datasets: [{
                    label: 'Payments Overview',
                    data: [totalPayments, 10000000 - totalPayments], // Example: 10M is the total capacity
                    backgroundColor: ['#FF5722', '#E0E0E0']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</body>
</html>