<?php
session_start();

// Clear the cart session
unset($_SESSION['cart']);

// Return a success message
echo "Cart cleared successfully.";
?>