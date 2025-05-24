<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $complete_address = trim($_POST['complete_address']);
    $agency = trim($_POST['agency']);
    $reference_number = trim($_POST['reference_number']);
    $contact_person = trim($_POST['contact_person']);
    $special_instructions = trim($_POST['special_instructions']);
    $bank = trim($_POST['bank']);
    $bank_name = trim($_POST['bank_name']);
    $account_number = trim($_POST['account_number']);
    $account_holder = trim($_POST['account_holder']);
    $branch_name = trim($_POST['branch_name']);
    $status = "Pending"; // Default status
    $total = 0;

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($complete_address) || empty($agency) || empty($contact_person) || empty($bank) || empty($account_number)) {
        die("Error: Please fill out all required fields.");
    }

    // Generate a Unique Order ID
    $order_id = uniqid("ORD-");

    // Calculate Total Price (Retrieve from session cart)
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    } else {
        die("Error: Your cart is empty. Please add items before checkout.");
    }

    // Insert order into database
    $sql = "INSERT INTO orders (order_id, name, email, phone, address, agency, reference_number, contact_person, special_instructions, bank, bank_name, account_number, account_holder, branch_name, total, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssds", $order_id, $name, $email, $phone, $complete_address, $agency, $reference_number, $contact_person, $special_instructions, $bank, $bank_name, $account_number, $account_holder, $branch_name, $total, $status);

    if ($stmt->execute()) {
        // Clear Cart After Successful Checkout
        unset($_SESSION['cart']);

        // Redirect to Order Confirmation Page
        header("Location: order_success.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
