<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        /* Style for the modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            text-align: left;
        }

        .modal-header {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .modal-footer {
            margin-top: 20px;
            text-align: right;
        }

        .close-btn {
            background-color: #f44336;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .close-btn:hover {
            background-color: #d32f2f;
        }

        .download-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .download-btn:hover {
            background-color: #45a049;
        }
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
                    <li><a href="logout.php" class="sidebar-btn">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header>
                Admin Dashboard
            </header>

            <!-- Payment Processing Table -->
            <section class="table-section">
                <h2>Payment Processing</h2>
                <table>
                    <tr>
                        <th>Order ID</th>
                        <th>Supplier</th>
                        <th>Customer</th>
                        <th>Agency</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    require 'db.php';

                    // Fetch payment data from orders table, grouped by supplier
                    $sql = "SELECT o.order_id, o.name AS customer_name, o.agency, o.total, o.payment_status, o.created_at, u.user_name AS supplier_name
                            FROM orders o
                            LEFT JOIN users u ON o.supplier_id = u.user_id
                            ORDER BY o.created_at DESC";
                    $result = $conn->query($sql);
                    ?>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['agency']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                            <td>₱<?php echo number_format($row['total'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <button class="btn" onclick="showReceipt('<?php echo htmlspecialchars($row['order_id']); ?>', '<?php echo htmlspecialchars($row['supplier_name']); ?>', '₱<?php echo number_format($row['total'], 2); ?>')">Receipt</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8">No payment records found.</td></tr>
                    <?php endif; ?>
                </table>
            </section>

            <!-- Modal for Receipt -->
            <div id="receiptModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">Payment Receipt</div>
                    <p><strong>Payment ID:</strong> <span id="receiptPaymentID"></span></p>
                    <p><strong>Supplier:</strong> <span id="receiptSupplier"></span></p>
                    <p><strong>Amount:</strong> <span id="receiptAmount"></span></p>
                    <div class="modal-footer">
                        <button class="download-btn" id="downloadBtn" onclick="downloadReceipt()">Download Receipt</button>
                        <button class="close-btn" onclick="closeReceipt()">Close</button>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script>
        // Function to show the receipt modal
        function showReceipt(paymentID, supplier, amount) {
            document.getElementById("receiptPaymentID").textContent = paymentID;
            document.getElementById("receiptSupplier").textContent = supplier;
            document.getElementById("receiptAmount").textContent = amount;

            // Enable the download button
            document.getElementById("downloadBtn").onclick = function() {
                downloadReceipt(paymentID, supplier, amount);
            };

            // Display the modal
            document.getElementById("receiptModal").style.display = "flex";
        }

        // Function to close the receipt modal
        function closeReceipt() {
            document.getElementById("receiptModal").style.display = "none";
        }

        // Function to download the receipt as a .txt file
        function downloadReceipt(paymentID, supplier, amount) {
            const receiptContent = `
                Payment Receipt:
                ---------------------------
                Payment ID: ${paymentID}
                Supplier: ${supplier}
                Amount: ${amount}
            `;

            const blob = new Blob([receiptContent], { type: 'text/plain' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `receipt_${paymentID}.txt`;
            link.click();
        }

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
    </script>
</body>
</html>
