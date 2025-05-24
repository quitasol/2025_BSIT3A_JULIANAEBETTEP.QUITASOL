<?php
session_start();
require 'db.php';

// Ensure that the user is logged in and is a supplier
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

$supplier_id = $_SESSION['user_id'];

// Fetch quote requests for this supplier
$quote_sql = "SELECT q.*, u.full_name AS agency_name
              FROM quotes q
              LEFT JOIN users u ON q.id = u.user_id
              WHERE q.supplier_id = ?
              ORDER BY q.created_at DESC";

$quote_stmt = $conn->prepare($quote_sql);
$quote_stmt->bind_param("i", $supplier_id);
$quote_stmt->execute();
$quote_result = $quote_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quote Requests</title>
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

        .main-content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }

        h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
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

        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup-content {
            background: white;
            padding: 20px 30px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .popup-content h3 {
            margin: 10px 0;
            font-size: 20px;
            color: #333;
        }

        .popup-content p {
            font-size: 16px;
            color: #555;
        }

        .popup-btn {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .popup-btn:hover {
            background-color: #0056b3;
        }

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
            <li><a href="quote_requests.php">Quote Requests</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Quote Requests</h1>
        <?php if ($quote_result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Agency</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Total</th>
                    <th>Received At</th>
                    <th>Action</th>
                </tr>
                <?php while ($quote = $quote_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($quote['agency_name'] ?? $quote['agency']) ?></td>
                        <td><?= htmlspecialchars($quote['contact_person']) ?></td>
                        <td><?= htmlspecialchars($quote['email']) ?></td>
                        <td><?= htmlspecialchars($quote['phone']) ?></td>
                        <td>₱<?= number_format($quote['total'], 2) ?></td>
                        <td><?= htmlspecialchars(date("M d, Y h:i A", strtotime($quote['created_at']))) ?></td>
                        <td>
                            <a href="view_quote.php?id=<?= $quote['id'] ?>">View</a> | 
                            <a href="accept_quote.php?id=<?= $quote['id'] ?>" onclick="return confirm('Are you sure you want to accept this quote?')">Accept</a> | 
                            <a href="reject_quote.php?id=<?= $quote['id'] ?>" onclick="return confirm('Are you sure you want to reject this quote?')">Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No quote requests received yet.</p>
        <?php endif; ?>
    </div>

    <div id="success-popup" class="popup">
        <div class="popup-content">
            <h3>Quote Submitted Successfully!</h3>
            <p>Your quote request has been submitted successfully.</p>
            <button class="popup-btn" onclick="closePopup()">OK</button>
        </div>
    </div>

    <script>
        // Function to show the pop-up
        function showPopup() {
            document.getElementById('success-popup').style.display = 'flex';
        }

        // Function to close the pop-up
        function closePopup() {
            document.getElementById('success-popup').style.display = 'none';
        }

        // Show the pop-up if success=true is in the URL
        <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
            window.onload = showPopup;
        <?php endif; ?>
    </script>

</body>
</html>
