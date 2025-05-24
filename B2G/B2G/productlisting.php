<?php
include 'db.php';

// Fetch product listings from the database
$sql = "SELECT p.product_id, p.product_name, u.user_type AS supplier, p.status 
        FROM products p
        JOIN users u ON p.supplier_id = u.user_id";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listings</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        /* Add your admin.css styles or custom styles here */
    </style>
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
                    <li><a href="#" class="sidebar-btn">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header>
                Product Listings Management
            </header>

            <section class="table-section">
                <h2>Product Listings</h2>

                <table>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                    <?php if ($result->num_rows > 0) { ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td>#P<?php echo $row['product_id']; ?></td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['supplier']; ?></td>
                                <td class="status"><?php echo $row['status']; ?></td>
                                <td>
                                    <button class="btn approve-btn" data-id="<?php echo $row['product_id']; ?>">Approve</button>
                                    <button class="btn btn-reject reject-btn" data-id="<?php echo $row['product_id']; ?>">Reject</button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No products found or available.</td>
                        </tr>
                    <?php } ?>
                </table>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const approveButtons = document.querySelectorAll(".approve-btn");
            const rejectButtons = document.querySelectorAll(".reject-btn");

            // When approve button is clicked
            approveButtons.forEach(button => {
                button.addEventListener("click", function() {
                    updateProductStatus(this.dataset.id, "Approved");
                });
            });

            // When reject button is clicked
            rejectButtons.forEach(button => {
                button.addEventListener("click", function() {
                    updateProductStatus(this.dataset.id, "Rejected");
                });
            });

            // Function to update the product status
            function updateProductStatus(productId, status) {
                fetch("update_product_status.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${productId}&status=${status}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        document.querySelector(`[data-id='${productId}']`).closest("tr").querySelector(".status").textContent = status;
                    } else {
                        alert("Failed to update product status");
                    }
                });
            }
        });
    </script>
</body>
</html>
