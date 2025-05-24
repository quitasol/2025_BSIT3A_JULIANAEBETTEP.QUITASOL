<?php
include 'db.php'; // Ensure db.php has the correct connection settings

// Fetch all users from the database
$sql = "SELECT user_id, user_type, status FROM users";
$result = $conn->query($sql);

// Handle the POST request for updating user status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $user_id = (int)$_POST['id'];  // Ensure it's an integer for security
    $status = $conn->real_escape_string($_POST['status']);  // Escape to prevent SQL injection

    // Prepare the SQL query to update the user status
    $sql = "UPDATE users SET status = ? WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $status, $user_id);
        if ($stmt->execute()) {
            echo "success";  // Respond with 'success' if the update is successful
        } else {
            echo "Error updating status: " . $stmt->error;  // Respond with an error message if the query fails
        }
        $stmt->close();
    } else {
        echo "Failed to prepare query: " . $conn->error;  // If the statement preparation fails
    }
    $conn->close();
    exit(); // Stop further processing
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <ul>
                    <li><a href="admin.php" class="sidebar-btn">Dashboard</a></li>
                    <li><a href="usermanagement.php" class="sidebar-btn">User Management</a></li>
                    <li><a href="productlisting.php" class="sidebar-btn">Product Listings</a></li>
                    <li><a href="orders.php" class="sidebar-btn">Orders</a></li>
                    <li><a href="payment.php" class="sidebar-btn">Payments</a></li>
                    <li><a href="report.php" class="sidebar-btn">Reports</a></li>
                    <li><a href="#" class="sidebar-btn">Compliance</a></li>
                    <li><a href="#" class="sidebar-btn">Settings</a></li>
                    <li><a href="#" class="sidebar-btn">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header>
                User Management
            </header>

            <!-- User Management Table -->
            <section class="table-section">
                <h2>User Management</h2>
                <table>
                    <tr>
                        <th>User ID</th>
                        <th>User Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    // Check if the query returned results
                    if ($result && $result->num_rows > 0) {
                        // Loop through the users and display them
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>#U" . $row["user_id"] . "</td>";
                            echo "<td>" . $row["user_type"] . "</td>";
                            echo "<td class='status'>" . ($row["status"] ? $row["status"] : "Pending") . "</td>";
                            echo "<td>
                                    <button class='btn approve-btn' data-id='" . $row["user_id"] . "'>Approve</button>
                                    <button class='btn btn-reject reject-btn' data-id='" . $row["user_id"] . "'>Reject</button>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        // Display a message if no users are found
                        echo "<tr><td colspan='4'>No users found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const approveButtons = document.querySelectorAll(".approve-btn");
            const rejectButtons = document.querySelectorAll(".reject-btn");

            // Function to handle updating the user status
            function updateUserStatus(userId, status) {
                const formData = new FormData();
                formData.append('id', userId);
                formData.append('status', status);

                // Use fetch API to send a POST request to the server
                fetch('usermanagement.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(responseText => {
                    // If the status update was successful
                    if (responseText.trim() === 'success') {
                        alert('Status updated successfully!');
                        location.reload();  // Reload the page to reflect the changes
                    } else {
                        alert('Error updating status: ' + responseText);
                    }
                })
                .catch(error => {
                    console.error('Error updating status:', error);
                    alert('An error occurred. Please try again.');
                });
            }

            // Attach event listeners to each approve and reject button
            approveButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const userId = this.dataset.id;
                    updateUserStatus(userId, "verified");
                });
            });

            rejectButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const userId = this.dataset.id;
                    updateUserStatus(userId, "rejected");
                });
            });
        });
    </script>
</body>
</html>
