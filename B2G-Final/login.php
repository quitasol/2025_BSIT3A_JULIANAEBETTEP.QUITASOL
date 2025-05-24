<?php
session_start();
require 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Prepare SQL query to fetch user data
    $sql = "SELECT user_id, email, password_hash, user_type, status FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $email_db, $hashed_password, $user_type, $status);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Admins can login directly
            if ($user_type === 'admin') {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email_db;
                $_SESSION['user_type'] = $user_type;
                header("Location: admin.php");
                exit();
            }

            // Suppliers and government users with verification logic
            if (in_array($user_type, ['supplier', 'government'])) {
                if ($status === 'verified') {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['email'] = $email_db;
                    $_SESSION['user_type'] = $user_type;

                    // Redirect to appropriate dashboard
                    if ($user_type === 'supplier') {
                        header("Location: supplier_dashboard.php");
                    } else {
                        header("Location: government.php");
                    }
                    exit();
                } elseif ($status === 'rejected') {
                    echo "<script>alert('Your registration was rejected by the admin.'); window.location='login.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Your account is not verified yet. Please wait for admin approval.'); window.location='login.php';</script>";
                    exit();
                }
            }

            // Fallback for undefined user_type
            echo "<script>alert('Unknown user type.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Invalid email or password.'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found.'); window.location='login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .register-link {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p class="register-link">Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>
