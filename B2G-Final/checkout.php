<?php
session_start();
require 'db.php'; // Ensure database connection

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

// Calculate total price
$total = 0.00;
foreach ($_SESSION['cart'] as $item) {
    $total += floatval($item['price']) * intval($item['quantity']);
}
$formattedTotal = number_format($total, 2, '.', '');

if (!isset($_SESSION['supplier_id']) && !empty($_SESSION['cart'])) {
    $_SESSION['supplier_id'] = $_SESSION['cart'][0]['supplier_id'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request for Quote</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .checkout-container {
            display: flex;
            flex-direction: row;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            padding: 25px;
            gap: 20px;
        }

        .form-section, .cart-summary {
            padding: 20px;
        }

        .form-section {
            flex: 1;
            border-right: 2px solid #e0e0e0;
        }

        .cart-summary {
            flex: 0.8;
            text-align: center;
        }

        h2 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #333;
        }

        label {
            font-weight: 500;
            display: block;
            margin: 8px 0 5px;
            color: #555;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .checkout-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 6px;
            background-color: #007bff;
            color: white;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .checkout-btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 16px;
            border-bottom: 1px solid #ddd;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            margin-top: 15px;
            color: #007bff;
        }

        .bank-details {
            display: none;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #f1f1f1;
        }

        @media (max-width: 768px) {
            .checkout-container {
                flex-direction: column;
                width: 100%;
                padding: 20px;
            }
            .form-section {
                border-right: none;
                border-bottom: 2px solid #e0e0e0;
                padding-bottom: 20px;
            }
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

        .popup-content i {
            margin-bottom: 10px;
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
    </style>
</head>
<body>

    <div class="checkout-container">
        <div class="form-section">
            <h2>Request for Quote</h2>
            <form method="POST" action="process_quote.php">
                <label>Government Agency/Department Name</label>
                <input type="text" name="agency" placeholder="e.g., Department of Health" required>
                
                <label>Point of Contact</label>
                <input type="text" name="contact_person" placeholder="e.g., John Doe" required>
                
                <label>Email Address</label>
                <input type="email" name="email" placeholder="e.g., johndoe@example.com" required>
                
                <label>Phone Number</label>
                <input type="tel" name="phone" placeholder="e.g., +63 912 345 6789" required>
                
                <label>Delivery Address</label>
                <input type="text" name="delivery_address" placeholder="e.g., 123 Government St., Manila" required>
                
                <label>Reference Number (if applicable)</label>
                <input type="text" name="reference_number" placeholder="e.g., PR-2025-00123">
                
                <label>Special Instructions</label>
                <textarea name="special_instructions" rows="3" placeholder="e.g., Deliver within 7 days, include warranty details"></textarea>

                <!-- Hidden field to pass total amount securely -->
                <input type="hidden" name="total" value="<?php echo htmlspecialchars($formattedTotal); ?>">

                <!-- Hidden field for supplier_id -->
                <input type="hidden" name="supplier_id" value="<?php echo isset($_SESSION['supplier_id']) ? $_SESSION['supplier_id'] : ''; ?>">

                <button type="submit" class="checkout-btn">Submit Quote Request</button>
            </form>
        </div>

        <div class="cart-summary">
            <h2>Review Your Cart</h2>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <div class="cart-item">
                    <span><?php echo htmlspecialchars($item['product_name']); ?> (x<?php echo $item['quantity']; ?>)</span>
                    <span>₱<?php echo number_format(floatval($item['price']) * intval($item['quantity']), 2); ?></span>
                </div>
            <?php endforeach; ?>
            <div class="total">Total: ₱<?php echo htmlspecialchars($formattedTotal); ?></div>
        </div>
    </div>

    <div id="success-popup" class="popup">
        <div class="popup-content">
            <i class="fa fa-check-circle" style="font-size: 48px; color: #28a745;"></i>
            <h3>Request Sent Successfully!</h3>
            <p>Your quote request has been submitted. We will get back to you shortly.</p>
            <button onclick="closePopup()" class="popup-btn">OK</button>
        </div>
    </div>

    <script>
        // Function to show the pop-up
        function showPopup() {
            document.getElementById('success-popup').style.display = 'flex';
        }

        // Function to close the pop-up and clear the cart
        function closePopup() {
            // Send a request to clear the cart
            fetch('clear_cart.php')
                .then(response => response.text())
                .then(data => {
                    console.log(data); // Optional: Log the response for debugging
                    document.getElementById('success-popup').style.display = 'none';
                    window.location.href = 'cart.php'; // Redirect to the cart page or another page
                })
                .catch(error => console.error('Error clearing cart:', error));
        }

        // Simulate showing the pop-up after form submission
        <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
            window.onload = showPopup;
        <?php endif; ?>
    </script>

</body>
</html>
