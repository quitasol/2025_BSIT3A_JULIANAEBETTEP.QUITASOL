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

// Ensure supplier_id is stored in session (Modify this logic to fit your needs)
if (!isset($_SESSION['supplier_id'])) {
    $_SESSION['supplier_id'] = 1; // Replace with dynamic supplier ID logic
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
    </style>
</head>
<body>

    <div class="checkout-container">
        <div class="form-section">
            <h2>Shipping Information</h2>
            <form method="POST" action="process_order.php">
                <label>Full Name</label>
                <input type="text" name="name" required>
                
                <label>Email Address</label>
                <input type="email" name="email" required>
                
                <label>Phone Number</label>
                <input type="tel" name="phone" required>
                
                <label>Complete Address</label>
                <input type="text" name="complete_address" required>
                
                <label>Government Agency/Department Name</label>
                <input type="text" name="agency" required>
                
                <label>Reference Number (if applicable)</label>
                <input type="text" name="reference_number">
                
                <label>Point of Contact</label>
                <input type="text" name="contact_person" required>
                
                <label>Special Instructions</label>
                <textarea name="special_instructions" rows="3"></textarea>

                <h2>Payment Method</h2>
                <label>Select Your Bank</label>
                <select name="bank" id="bank" required>
                    <option value="">Choose a bank</option>
                    <option value="govbank">GovBank</option>
                    <option value="citybank">CityBank</option>
                    <option value="unionbank">UnionBank</option>
                    <option value="other">Other</option>
                </select>

                <!-- Bank Details Form (Appears Dynamically) -->
                <div class="bank-details" id="bank-details">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" id="bank_name">

                    <label>Account Number</label>
                    <input type="text" name="account_number" id="account_number">

                    <label>Account Holder Name</label>
                    <input type="text" name="account_holder" id="account_holder">

                    <label>Branch Name</label>
                    <input type="text" name="branch_name" id="branch_name">
                </div>

                <!-- Hidden field to pass total amount securely -->
                <input type="hidden" name="total" value="<?php echo htmlspecialchars($formattedTotal); ?>">

                <!-- Hidden field for supplier_id -->
                <input type="hidden" name="supplier_id" value="<?php echo isset($_SESSION['supplier_id']) ? $_SESSION['supplier_id'] : ''; ?>">

                <button type="submit" class="checkout-btn">Place Order</button>
            </form>
        </div>

        <div class="cart-summary">
            <h2>Review Your Cart</h2>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <div class="cart-item">
                    <span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</span>
                    <span>₱<?php echo number_format(floatval($item['price']) * intval($item['quantity']), 2); ?></span>
                </div>
            <?php endforeach; ?>
            <div class="total">Total: ₱<?php echo htmlspecialchars($formattedTotal); ?></div>
        </div>
    </div>

    <script>
        document.getElementById('bank').addEventListener('change', function() {
            var bankDetails = document.getElementById('bank-details');
            if (this.value !== "") {
                bankDetails.style.display = 'block';
            } else {
                bankDetails.style.display = 'none';
            }
        });
    </script>
</body>
</html>
