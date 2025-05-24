<?php
session_start();

// Ensure the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture Form Data
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

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($complete_address) || empty($agency) || empty($contact_person) || empty($bank) || empty($account_number)) {
        die("Error: Please fill out all required fields.");
    }

    // Generate a Unique Order ID
    $order_id = uniqid("ORD-");

    // Calculate Total Price (Retrieve from session cart)
    $total = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    } else {
        die("Error: Your cart is empty. Please add items before checkout.");
    }

    // Store Order Data in Session (Use Database in a real application)
    $_SESSION['order'] = [
        'order_id' => $order_id,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'address' => $complete_address,
        'agency' => $agency,
        'reference_number' => $reference_number,
        'contact_person' => $contact_person,
        'special_instructions' => $special_instructions,
        'bank' => $bank,
        'bank_name' => $bank_name,
        'account_number' => $account_number,
        'account_holder' => $account_holder,
        'branch_name' => $branch_name,
        'total' => $total
    ];

    // Clear Cart After Successful Checkout
    unset($_SESSION['cart']);

    // Redirect to Order Confirmation Page
    header("Location: order_success.php");
    exit();
} else {
    // Redirect if the page is accessed directly
    header("Location: checkout.php");
    exit();
}
?>
