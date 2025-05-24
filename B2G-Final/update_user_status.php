<?php
// Include your database connection file
include 'db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request method is POST and required parameters are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $user_id = (int)$_POST['id'];  // Ensure it's an integer for security
    $status = $conn->real_escape_string($_POST['status']);  // Escape to prevent SQL injection

    // Prepare the SQL query to update the user status
    $sql = "UPDATE users SET status = ? WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the query
        $stmt->bind_param("si", $status, $user_id);

        // Execute the query
        if ($stmt->execute()) {
            echo "success";  // Respond with 'success' if the update is successful
        } else {
            echo "Error updating status: " . $stmt->error;  // Print detailed error message
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to prepare query: " . $conn->error;  // Print error if query preparation fails
    }
} else {
    echo "Invalid request";  // If the POST data is missing or incorrect
}

// Close the database connection
$conn->close();
?>
