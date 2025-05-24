<?php
session_start();
include 'db.php';

// Ensure the user is logged in and is a supplier
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== "supplier") {
    header("Location: login.php");
    exit();
}

$quote_id = $_GET['id'] ?? null;

if (!$quote_id) {
    echo "Invalid quote ID.";
    exit();
}

// Fetch quote details
$sql = "SELECT * FROM quotes WHERE id = ? AND supplier_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $quote_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Quote not found or you do not have permission to view this quote.";
    exit();
}

$quote = $result->fetch_assoc();

// Fetch quote items
$item_sql = "SELECT * FROM quote_items WHERE quote_id = ?";
$item_stmt = $conn->prepare($item_sql);
$item_stmt->bind_param("i", $quote_id);
$item_stmt->execute();
$item_result = $item_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quote Details</title>
    <style>
        body {
            background: #f4f8fb;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(44,62,80,0.10);
            padding: 32px 36px 28px 36px;
        }
        h1 {
            color: #2d3e50;
            font-size: 2em;
            margin-bottom: 18px;
        }
        h3 {
            margin-top: 32px;
            color: #2980b9;
        }
        p {
            margin: 8px 0;
            color: #34495e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            margin-bottom: 18px;
        }
        th, td {
            padding: 10px 12px;
            border-bottom: 1px solid #e1e7ed;
            text-align: left;
        }
        th {
            background: #eaf3fb;
            color: #2980b9;
            font-weight: 600;
        }
        tr:last-child td {
            border-bottom: none;
        }
        a {
            display: inline-block;
            margin-top: 18px;
            padding: 10px 22px;
            background: #2980b9;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }
        a:hover {
            background: #1a5a8a;
        }
        @media (max-width: 600px) {
            .container {
                padding: 12px 4vw;
            }
            h1 {
                font-size: 1.2em;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Quote Details</h1>
    <p><strong>Agency:</strong> <?= htmlspecialchars($quote['agency']) ?></p>
    <p><strong>Contact Person:</strong> <?= htmlspecialchars($quote['contact_person']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($quote['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($quote['phone']) ?></p>
    <p><strong>Delivery Address:</strong> <?= htmlspecialchars($quote['delivery_address']) ?></p>
    <p><strong>Reference Number:</strong> <?= htmlspecialchars($quote['reference_number']) ?></p>
    <p><strong>Special Instructions:</strong> <?= nl2br(htmlspecialchars($quote['special_instructions'])) ?></p>
    <p><strong>Total:</strong> ₱<?= number_format($quote['total'], 2) ?></p>
    <p><strong>Received At:</strong> <?= htmlspecialchars($quote['created_at']) ?></p>

    <h3>Quote Items</h3>
    <table>
        <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php while ($item = $item_result->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if (!empty($item['image']) && file_exists("uploads/" . $item['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="Product Image" style="width:60px;max-height:60px;border-radius:6px;">
                <?php else: ?>
                    <span style="color:#aaa;">No Image</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($item['product_name']) ?></td>
            <td><?= htmlspecialchars($item['quantity']) ?></td>
            <td>₱<?= htmlspecialchars($item['price']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="supplier.php">Back to Dashboard</a>
</div>
</body>
</html>