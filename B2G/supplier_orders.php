<?php
session_start();
include 'db.php';

// Ensure the user is logged in and is a supplier
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

$supplier_id = $_SESSION['user_id'];

// Fetch only the orders that belong to the supplier
$sql = "
    SELECT o.order_id, o.name AS customer_name, o.email, o.phone, o.agency, 
           o.total, o.status, o.payment_status, o.created_at 
    FROM orders o
    WHERE o.supplier_id = ?
    ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Orders Dashboard</title>
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
        margin-left: 250px; /* Adjusted for sidebar */
        flex-direction: column;
    }

    h1 {
        text-align: center;
        margin: 20px 0;
        color: #333;
    }

    h2 {
        text-align: center;
        margin: 20px 0;
        color: white;
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
        overflow-y: auto;
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

    /* Table Styles */
    table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e9f5ff;
    }

    /* Button Styles */
    .btn {
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: opacity 0.3s ease, transform 0.2s ease;
    }

    .confirm {
        background: #28a745;
        color: white;
    }

    .reject {
        background: #dc3545;
        color: white;
    }

    .btn:hover {
        opacity: 0.9;
        transform: scale(1.05);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        body {
            margin-left: 0;
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        table {
            width: 100%;
        }

        th, td {
            font-size: 14px;
            padding: 8px;
        }

        .btn {
            padding: 8px;
            font-size: 14px;
        }
    }

    .view-details {
        background: #17a2b8;
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 5px;
        display: inline-block;
        transition: opacity 0.3s ease, transform 0.2s ease;
    }

    .view-details:hover {
        opacity: 0.9;
        transform: scale(1.05);
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

<h1>Supplier Orders Dashboard</h1>
<table>
    <tr>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Agency</th>
        <th>Total</th>
        <th>Action</th>
        <th>Status</th>
        <th>Payment Status</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
    <td><?php echo $row['order_id']; ?></td>
    <td><?php echo $row['customer_name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td><?php echo $row['agency']; ?></td>
    <td>₱<?php echo number_format($row['total'], 2); ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>
        <?php if ($row['status'] === "Pending") { ?>
            <form method="POST" action="update_order_status.php" style="display:inline-block;">
                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                <button type="submit" name="confirm" class="btn confirm">Confirm</button>
            </form>
            <form method="POST" action="update_order_status.php" style="display:inline-block;">
                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                <button type="submit" name="reject" class="btn reject">Reject</button>
            </form>
        <?php } else { echo "Processed"; } ?>
    </td>
    <td>
        <?php if ($row['payment_status'] === "Pending") { ?>
            <form method="POST" action="update_order_status.php">
                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                <button type="submit" name="mark_paid" class="btn confirm">Mark as Paid</button>
            </form>
        <?php } else { echo "Paid"; } ?>
    </td>
    <td>
        <a href="view_order_details.php?order_id=<?php echo $row['order_id']; ?>" class="btn view-details">View Details</a>
    </td>
</tr>
<?php }?>
</table>

</body>
</html>
