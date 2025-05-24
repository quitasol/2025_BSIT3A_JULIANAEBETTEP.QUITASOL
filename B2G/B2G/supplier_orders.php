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
           o.total, o.status, o.created_at 
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
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; text-align: center; padding: 20px; }
        h2 { color: #007bff; }
        table { width: 90%; margin: 20px auto; border-collapse: collapse; background: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .btn { padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
        .confirm { background: #28a745; color: white; }
        .reject { background: #dc3545; color: white; }
        .btn:hover { opacity: 0.8; }
    </style>
</head>
<body>

<h2>Supplier Orders Dashboard</h2>
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
                    <form method="POST" action="update_order_status.php">
                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                        <button type="submit" name="confirm" class="btn confirm">Confirm</button>
                        <button type="submit" name="reject" class="btn reject">Reject</button>
                    </form>
                <?php } else { echo "Processed"; } ?>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
