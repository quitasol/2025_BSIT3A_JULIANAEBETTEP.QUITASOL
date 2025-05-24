<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B2G E-Commerce - Connecting Businesses & Government</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
        }
        header {
            background: linear-gradient(135deg, #004080, #0073e6);
            color: white;
            padding: 20px;
            font-size: 28px;
            font-weight: bold;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .hero {
            background: url('uploads/bg.webp') no-repeat center center/cover;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            flex-direction: column;
            padding: 20px;
            position: relative;
            margin-top: 80px;
        }
        .hero h1 {
            font-size: 50px;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }
        .hero p {
            font-size: 22px;
            margin-bottom: 30px;
            max-width: 700px;
            opacity: 0.9;
        }
        .btn {
            padding: 15px 40px;
            font-size: 20px;
            color: white;
            background: #ff6600;
            border: none;
            cursor: pointer;
            border-radius: 50px;
            text-decoration: none;
            transition: 0.3s ease-in-out;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }
        .btn:hover {
            background: #cc5500;
            transform: scale(1.1);
        }
        .section {
            padding: 60px 20px;
            background: #fff;
            margin: 20px auto;
            max-width: 900px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        }
        .section h2 {
            font-size: 32px;
            color: #004080;
            margin-bottom: 20px;
        }
        .features {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .feature {
            background: #f9f9f9;
            padding: 20px;
            width: 280px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            transition: 0.3s ease-in-out;
        }
        .feature:hover {
            transform: scale(1.05);
            background: #e6f2ff;
        }
        .feature h3 {
            font-size: 22px;
            color: #0073e6;
            margin-bottom: 10px;
        }
        .footer {
            background: #004080;
            color: white;
            padding: 15px;
            position: relative;
            bottom: 0;
            width: 100%;
            font-size: 16px;
        }
    </style>
</head>
<body>

<header>
    B2G E-Commerce - Bridging Business & Government
</header>

<div class="hero">
    <h1>Seamless Government Procurement</h1>
    <p>Empowering businesses to connect with government agencies securely and efficiently.</p>
    <a href="login.php" class="btn">Login</a>
    <p>OR</p>
    <a href="register.php" class="btn" style="background: #0073e6;">Register</a>
</div>

<div class="section">
    <h2>Why Choose Us?</h2>
    <div class="features">
        <div class="feature">
            <h3>Verified Suppliers</h3>
            <p>Work with only verified and trusted businesses.</p>
        </div>
        <div class="feature">
            <h3>Secure Transactions</h3>
            <p>Guaranteed safe and secure payment systems.</p>
        </div>
        <div class="feature">
            <h3>Regulation Compliance</h3>
            <p>All transactions comply with government standards.</p>
        </div>
    </div>
</div>

<div class="section">
    <h2>How It Works</h2>
    <p>Businesses register, get verified, and bid on government contracts. Government agencies browse and purchase from trusted suppliers.</p>
</div>

<footer class="footer">
    &copy; 2025 B2G E-Commerce | All Rights Reserved
</footer>

</body>
</html>
