<?php
session_start();
// Include the database connection file
require 'db.php'; 

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
// Fetch data from the government table
$sql = "SELECT * FROM government";
$result = $conn->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {
    // Output data for each row
    $govData = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $govData = [];
}

// Fetch data for products (example query, replace with actual product fetching logic)
$productSql = "SELECT * FROM products";
$productResult = $conn->query($productSql);

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
        /* Resetting default styles for consistency */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        /* Header Styles */
        header {
            background: linear-gradient(90deg, #0073e6, #005bb5);
            color: white;
            padding: 20px 30px;
            font-size: 24px;
            display: flex;
            justify-content: space-between;  /* This ensures space between the logo and cart */
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header .logo {
            font-size: 28px;
            font-weight: 600;
        }

        header .logout {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease-in-out;
        }

        header .logout:hover {
            background-color: #c0392b;
        }

        .header-right {
        display: flex;
        align-items: center;
        gap: 20px; /* Adds space between cart and logout button */
    }

    .cart-link {
        color: white;
        text-decoration: none;
        font-size: 18px;
    }

    .cart-link i {
        margin-right: 5px;
    }

        .container {
            text-align: center;
            margin-top: 5px;
            padding: 20px 30px;
            text-align: center;
        }

        /* Government Data Table */
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

        /* No Data Message */
        .no-data-message {
            color: #e74c3c;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        /* Search Box */
        .search-box {
            margin-bottom: 30px;
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

        /* Categories Section */
        .categories {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
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

        /* Products Section */
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 280px;
            text-align: center;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
            overflow: hidden;
        }

        .product:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .product img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .product img:hover {
            transform: scale(1.1);
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

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                font-size: 20px;
                padding: 15px;
            }

            .search-box input {
                width: 280px;   
            }

            .categories {
                flex-wrap: wrap;
                justify-content: flex-start;
            }

            .category {
                margin-bottom: 10px;
            }

            .products {
                flex-direction: column;
                align-items: center;
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
            <i class="fa fa-shopping-cart"></i> Cart (<?php echo $cart_count; ?>)
        </a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</header>


<div class="container">
    <h2>Welcome to Government Shopping Portal</h2>

    <!-- Display Government Data Table -->
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
                    <td><?php echo htmlspecialchars($row['gov_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['gov_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_info']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['country']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<!-- Search Box -->
<div class="search-box">
    <input type="text" id="search" placeholder="Search for products..." onkeyup="searchProducts()">
</div>

<h1>Browse Products</h1>

<!-- Categories -->
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


<!-- Product List (From Database) -->
<div class="products" id="product-list">
<?php
if ($productResult->num_rows > 0) {
    while ($row = $productResult->fetch_assoc()) {
        echo '<div class="product" data-category="' . htmlspecialchars($row['category']) . '">
                <img src="../uploads/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['product_name']) . '">
                <h3>' . htmlspecialchars($row['product_name']) . '</h3>
                <p>' . htmlspecialchars($row['description']) . '</p>
                <p class="price">₱' . number_format($row['price'], 2) . '</p>
                <a href="cart.php?add_to_cart=' . $row['product_id'] . '&quantity=1" class="order-now-button">
                    <button>Order Now</button>
                </a>
              </div>';
    }
} else {
    echo "<p>No products available.</p>";
}
?>
</div>

<script>
// Search Products by Name
function searchProducts() {
    let input = document.getElementById('search').value.toLowerCase();
    let products = document.querySelectorAll('.product');

    products.forEach(product => {
        let name = product.querySelector('h3').innerText.toLowerCase();
        product.style.display = name.includes(input) ? "block" : "none";
    });
}

// Filter Products by Category
function filterCategory(category) {
    let products = document.querySelectorAll('.product');
    products.forEach(product => {
        if (category === 'all' || product.dataset.category === category) {
            product.style.display = "block";
        } else {
            product.style.display = "none";
        }
    });
}

    function addToCart(productId, quantity) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let existingProduct = cart.find(item => item.id === productId);

        if (existingProduct) {
            existingProduct.quantity += quantity;  // Increase the quantity if product exists
        } else {
            cart.push({
                id: productId,
                quantity: quantity,
                name: 'Product Name',  // Set product name dynamically
                price: 'Product Price' // Set product price dynamically
            });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
    }

    // Update Cart Count
    function updateCartCount() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let cartCount = cart.reduce((total, item) => total + item.quantity, 0);
        document.getElementById("cart-count").innerText = cartCount;
    }

    // Call this function on page load to update cart count
    document.addEventListener('DOMContentLoaded', () => {
        updateCartCount();
    });
</script>


</body>
</html>