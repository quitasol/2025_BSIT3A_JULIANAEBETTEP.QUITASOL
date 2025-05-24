<?php
session_start();
// Include the database connection file
require 'db.php'; 

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Fetch data from the government table
$sql = "SELECT * FROM government";
$result = $conn->query($sql);
$govData = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Fetch data for products
$productSql = "SELECT * FROM products";
$productResult = $conn->query($productSql);

// ✅ Fetch notifications for government users
$notifSql = "SELECT * FROM notifications WHERE user_type = 'government' ORDER BY created_at DESC";
$notifResult = $conn->query($notifSql);
$notifications = $notifResult->num_rows > 0 ? $notifResult->fetch_all(MYSQLI_ASSOC) : [];

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Government Shopping Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        header {
            background: linear-gradient(90deg, #0073e6, #005bb5);
            color: white;
            padding: 20px 30px;
            font-size: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        header .logo {
            font-size: 28px;
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .cart-link {
            color: white;
            text-decoration: none;
            font-size: 18px;
            position: relative;
        }

        .cart-link i {
            margin-right: 5px;
        }

        .logout {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease-in-out;
        }

        .logout:hover {
            background-color: #c0392b;
        }

        .notification-wrapper {
            position: relative;
        }

        .notification-count {
            background: red;
            color: white;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 50%;
            position: absolute;
            top: -5px;
            right: -10px;
            z-index: 1000;
            display: none;
        }

        .notification-box {
            position: absolute;
            right: 0;
            top: 35px;
            background: white;
            border: 1px solid #ccc;
            width: 300px;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 100;
            display: none;
        }

        .notification-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .notification-box li {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #333;
        }

        .container {
            padding: 20px 30px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #0073e6;
            color: white;
        }

        .no-data-message {
            color: #e74c3c;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .search-box {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .search-box input {
            padding: 10px;
            width: 350px;
            border-radius: 25px;
            border: 1px solid #ccc;
            font-size: 16px;
            text-align: center;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .categories {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .category {
            background: #0073e6;
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .category:hover {
            background: #005bb5;
            transform: translateY(-2px);
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }

        .product {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 280px;
            text-align: center;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        .product:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .product img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product h3 {
            font-size: 20px;
            margin: 15px 0;
            font-weight: 600;
            color: #333;
        }

        .product p {
            font-size: 14px;
            color: #777;
            margin-bottom: 15px;
        }

        .product .price {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 20px;
        }

        .product button {
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .product button:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .search-box input {
                width: 280px;   
            }

            .product {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="logo">GovShop</div>
    <div class="header-right">
        <a href="cart.php" class="cart-link">
            <i class="fa fa-shopping-cart"></i> Quote (<?php echo $cart_count; ?>)
        </a>

        <div class="notification-wrapper">
            <a href="#" id="notification-btn" class="cart-link">
                <i class="fa fa-bell"></i> Notifications
                <span id="notification-count" class="notification-count">0</span>
            </a>
            <div id="notification-box" class="notification-box">
                <ul id="notification-list">
                    <!-- Notifications will be loaded here dynamically -->
                </ul>
            </div>
        </div>

        <a href="logout.php" class="logout">Logout</a>
    </div>
</header>

<div class="container">
    <h2>Welcome to Government Shopping Portal</h2>

    <?php if (!empty($govData)): ?>
        <h1>Government Data</h1>
        <table>
            <tr>
                <th>Gov ID</th>
                <th>Government Name</th>
                <th>Contact Info</th>
                <th>Address</th>
                <th>Country</th>
                <th>Created At</th>
            </tr>
            <?php foreach ($govData as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['gov_id']) ?></td>
                    <td><?= htmlspecialchars($row['gov_name']) ?></td>
                    <td><?= htmlspecialchars($row['contact_info']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= htmlspecialchars($row['country']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<div class="search-box">
    <input type="text" id="search" placeholder="Search for products..." onkeyup="searchProducts()">
</div>

<h1>Browse Products</h1>

<div class="categories">
    <div class="category" onclick="filterCategory('all')">All</div>
    <div class="category" onclick="filterCategory('writing-instruments')">Writing Instruments</div>
    <div class="category" onclick="filterCategory('paper-products')">Paper Products</div>
    <div class="category" onclick="filterCategory('office-tools')">Office Tools</div>
    <div class="category" onclick="filterCategory('binders-organizers')">Binders and Organizers</div>
    <div class="category" onclick="filterCategory('technology-supplies')">Technology Supplies</div>
    <div class="category" onclick="filterCategory('desk-accessories')">Desk Accessories</div>
    <div class="category" onclick="filterCategory('mailing-supplies')">Mailing Supplies</div>
</div>

<div class="products" id="product-list">
<?php
if ($productResult->num_rows > 0) {
    while ($row = $productResult->fetch_assoc()) {
        echo '<div class="product" data-category="' . htmlspecialchars($row['category']) . '">
        <img src="uploads/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['product_name']) . '">
        <h3>' . htmlspecialchars($row['product_name']) . '</h3>
        <p>' . htmlspecialchars($row['description']) . '</p>
        <p class="price">₱' . number_format($row['price'], 2) . '</p>
        <a href="cart.php?add_to_cart=' . $row['product_id'] . '&quantity=1" class="order-now-button">
            <button>Add to Quote</button>
        </a>
      </div>';

    }
} else {
    echo "<p>No products available.</p>";
}
?>
</div>

<!-- Order Details Modal -->
<div id="order-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:2000; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px; border-radius:10px; max-width:400px; margin:auto; position:relative;">
        <span id="close-modal" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:20px;">&times;</span>
        <div id="order-modal-content"></div>
    </div>
</div>

<script>
function searchProducts() {
    let input = document.getElementById('search').value.toLowerCase();
    document.querySelectorAll('.product').forEach(product => {
        let name = product.querySelector('h3').innerText.toLowerCase();
        product.style.display = name.includes(input) ? "block" : "none";
    });
}

function filterCategory(category) {
    document.querySelectorAll('.product').forEach(product => {
        product.style.display = category === 'all' || product.dataset.category === category ? "block" : "none";
    });
}

// Toggle Notifications Dropdown and fetch notifications
document.getElementById('notification-btn').addEventListener('click', function (e) {
    e.preventDefault();
    let box = document.getElementById('notification-box');
    if (box.style.display === 'none' || box.style.display === '') {
        fetch('fetch_notifications.php')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('notification-list');
                const count = document.getElementById('notification-count');
                list.innerHTML = '';
                if (data.length > 0) {
                    count.innerText = data.length;
                    count.style.display = 'inline-block';
                    data.forEach(n => {
                        const li = document.createElement('li');
                        li.innerText = n.message;
                        li.style.cursor = n.quote_id ? 'pointer' : 'default';
                        if (n.quote_id) {
                            li.style.color = '#0073e6';
                            li.addEventListener('click', function () {
                                showQuoteDetails(n.quote_id);
                            });
                        }
                        list.appendChild(li);
                    });
                } else {
                    count.style.display = 'none';
                    const li = document.createElement('li');
                    li.innerText = 'No notifications.';
                    list.appendChild(li);
                }
            });
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
});

// Show quote details in modal
function showQuoteDetails(quoteId) {
    fetch('fetch_quote_details.php?quote_id=' + quoteId)
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data.success) {
                html += '<h3>Quote Summary</h3>';
                for (const key in data.quote) {
                    html += '<p><strong>' + key.replace(/_/g, ' ') + ':</strong> ' + data.quote[key] + '</p>';
                }
                // Check status field for accepted or rejected
                if (data.quote.status && data.quote.status.toLowerCase() === 'accepted') {
                    html += '<a href="place_order.php?quote_id=' + quoteId + '" style="display:inline-block;margin-top:15px;padding:10px 20px;background:#28a745;color:#fff;border-radius:5px;text-decoration:none;">Place Order</a>';
                } else if (data.quote.status && data.quote.status.toLowerCase() === 'rejected') {
                    html += '<p style="color:#e74c3c;font-weight:bold;margin-top:15px;">This quote has been rejected.</p>';
                }
            } else {
                html = '<p>Quote details not found.</p>';
            }
            document.getElementById('order-modal-content').innerHTML = html;
            document.getElementById('order-modal').style.display = 'flex';
        });
}

// Close modal
document.getElementById('close-modal').onclick = function() {
    document.getElementById('order-modal').style.display = 'none';
};
document.getElementById('order-modal').onclick = function(e) {
    if (e.target === this) this.style.display = 'none';
};

// Show notifications on page load
document.addEventListener('DOMContentLoaded', function () {
    fetch('fetch_notifications.php')
        .then(response => response.json())
        .then(data => {
            const count = document.getElementById('notification-count');
            if (data.length > 0) {
                count.innerText = data.length;
                count.style.display = 'inline-block';
            } else {
                count.style.display = 'none';
            }
        });
});
</script>

</body>
</html>