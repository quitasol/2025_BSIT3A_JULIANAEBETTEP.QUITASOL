<?php
// Connect to the database
$servername = "localhost";
$username = "root";  // your database username
$password = "";      // your database password
$dbname = "btog";    // the name of your database

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['register'])) {
    // Retrieve form data
    $full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
    $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';

    // Check if all fields are filled
    if (empty($full_name) || empty($user_name) || empty($email) || empty($password) || empty($user_type)) {
        echo "<p>Please fill in all fields.</p>";
    } else {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $sql = "INSERT INTO users (full_name, user_name, email, password_hash, user_type, status) 
                VALUES ('$full_name', '$user_name', '$email', '$password_hash', '$user_type', 'pending')";

        if ($conn->query($sql) === TRUE) {
            // Registration successful, redirect to login page
            header("Location: login.php"); // Redirect to login page after successful registration
            exit(); // Make sure no further code is executed after the redirect
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        /* General Styles for the Page */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        h2 {
            color: #333;
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
        }

        .registration-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px 40px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
            background-color: #fff;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .form-group select {
            padding: 12px;
        }

        .message {
            text-align: center;
            color: green;
            margin-top: 10px;
        }

        .message p {
            margin: 0;
        }

        .form-link {
            text-align: center;
            margin-top: 20px;
        }

        .form-link a {
            color: #007bff;
            text-decoration: none;
        }

        .form-link a:hover {
            text-decoration: underline;
        }

        /* Responsiveness */
        @media (max-width: 480px) {
            .registration-container {
                width: 90%;
                padding: 25px;
            }
        }

    </style>
</head>
<body>

    <h2>User Registration</h2>
    <div class="registration-container">
        <!-- Form submits to the same page -->
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" required>
            </div>

            <div class="form-group">
                <label for="user_name">Username</label>
                <input type="text" name="user_name" id="user_name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="user_type">User Type</label>
                <select name="user_type" id="user_type" required>
                    <option value="government">Government</option>
                    <option value="supplier">Supplier</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" name="register">Register</button>
        </form>

        <!-- Optional message or link if needed -->
        <div class="form-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

</body>
</html>
