<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
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
                    <li><a href="report.php" class="sidebar-btn">Reports</a></li>
                    <li><a href="#" class="sidebar-btn">Compliance</a></li>
                    <li><a href="#" class="sidebar-btn">Settings</a></li>
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
                    <p>1,234</p>
                </div>
                <div class="card">
                    <h3>Total Suppliers</h3>
                    <p>567</p>
                </div>
                <div class="card">
                    <h3>Pending Orders</h3>
                    <p>89</p>
                </div>
                <div class="card">
                    <h3>Payments Processed</h3>
                    <p>₱5,000,000</p>
                </div>
            </section>

            
            <!-- Orders Management Table -->
            <section class="table-section">
                <h2>Order Management</h2>
                <table>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                    <tr>
                        <td>#1001</td>
                        <td>John Doe</td>
                        <td class="status">Pending</td>
                        <td>₱1,500</td>
                        <td><button class="btn approve-btn" data-id="1001">Approve</button> <button class="btn btn-reject reject-btn" data-id="1001">Reject</button></td>
                    </tr>
                    <tr>
                        <td>#1002</td>
                        <td>Jane Smith</td>
                        <td class="status">Completed</td>
                        <td>₱2,200</td>
                        <td><button class="btn">View</button></td>
                    </tr>
                </table>
            </section>

            <!-- Payment Processing Table -->
            <section class="table-section">
                <h2>Payment Processing</h2>
                <table>
                    <tr>
                        <th>Payment ID</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                    <tr>
                        <td>#P1001</td>
                        <td>ABC Corp</td>
                        <td class="status">Pending</td>
                        <td>₱1,500</td>
                        <td><button class="btn verify-btn" data-id="P1001">Verify</button></td>
                    </tr>
                    <tr>
                        <td>#P1002</td>
                        <td>XYZ Ltd</td>
                        <td class="status">Completed</td>
                        <td>₱3,200</td>
                        <td><button class="btn">Receipt</button></td>
                    </tr>
                </table>
            </section>

            <!-- Reports Section -->
            <section class="table-section">
                <h2>Reports</h2>
                <table>
                    <tr>
                        <th>Report Type</th>
                        <th>Generated Date</th>
                        <th>Actions</th>
                    </tr>
                    <tr>
                        <td>Procurement</td>
                        <td>January 2025</td>
                        <td><button class="btn">View</button></td>
                    </tr>
                    <tr>
                        <td>Financial</td>
                        <td>December 2024</td>
                        <td><button class="btn">View</button></td>
                    </tr>
                </table>
            </section>

            <!-- Compliance Section -->
            <section class="table-section">
                <h2>Compliance Monitoring</h2>
                <table>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <tr>
                        <td>#T1001</td>
                        <td>Pending</td>
                        <td><button class="btn">Review</button></td>
                    </tr>
                    <tr>
                        <td>#T1002</td>
                        <td>Approved</td>
                        <td><button class="btn">View</button></td>
                    </tr>
                </table>
            </section>

        </main>
    </div>

    <script>
        // JavaScript to add the active class on sidebar button click
        const sidebarButtons = document.querySelectorAll('.sidebar-btn');
        sidebarButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                sidebarButtons.forEach(btn => btn.parentElement.classList.remove('active'));
                // Add active class to the clicked button's parent
                this.parentElement.classList.add('active');
            });
        });

        // Order Approval/Reject functionality
        const approveButtons = document.querySelectorAll('.approve-btn');
        const rejectButtons = document.querySelectorAll('.reject-btn');
        
        approveButtons.forEach(button => {
            button.addEventListener('click', function() {
                updateOrderStatus(this.dataset.id, 'Approved');
            });
        });

        rejectButtons.forEach(button => {
            button.addEventListener('click', function() {
                updateOrderStatus(this.dataset.id, 'Rejected');
            });
        });

        function updateOrderStatus(orderId, status) {
            fetch("update_order_status.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${orderId}&status=${status}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === "success") {
                    // Update the status text
                    const row = document.querySelector(`[data-id='${orderId}']`).closest("tr");
                    row.querySelector(".status").textContent = status;
                } else {
                    alert("Failed to update order status");
                }
            });
        }

        // Payment verification functionality
        const verifyButtons = document.querySelectorAll('.verify-btn');
        
        verifyButtons.forEach(button => {
            button.addEventListener('click', function() {
                updatePaymentStatus(this.dataset.id, 'Verified');
            });
        });

        function updatePaymentStatus(paymentId, status) {
            fetch("update_payment_status.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${paymentId}&status=${status}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === "success") {
                    // Update the status text
                    const row = document.querySelector(`[data-id='${paymentId}']`).closest("tr");
                    row.querySelector(".status").textContent = status;
                } else {
                    alert("Failed to update payment status");
                }
            });
        }

    </script>
</body>
</html>
