<?php
session_start();
require 'db.php';

$gov_id = $_GET['gov_id'] ?? 1; // Default to 1 for demo
// Replace with actual DB query
$govSql = "SELECT * FROM government WHERE gov_id = ?";
$stmt = $conn->prepare($govSql);
$stmt->bind_param("i", $gov_id);
$stmt->execute();
$result = $stmt->get_result();
$govData = $result->fetch_assoc();

if (!$govData) {
    echo "Government agency not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Bid - <?php echo htmlspecialchars($govData['gov_name']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f4f8fc;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(90deg, #0073e6, #005bb5);
            color: white;
            padding: 20px 30px;
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .gov-info {
            background: #eaf4ff;
            padding: 20px;
            border-left: 6px solid #0073e6;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .gov-info p {
            margin: 8px 0;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }

        input[type="number"],
        input[type="date"],
        input[type="file"],
        textarea {
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            background: #f9f9f9;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        @media (max-width: 600px) {
            .container {
                margin: 15px;
                padding: 20px;
            }

            header {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

<header>Submit Your Bid for Office Supplies</header>

<div class="container">
    <h2>Procurement: <?php echo htmlspecialchars($govData['gov_name']); ?></h2>

    <div class="gov-info">
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($govData['contact_info']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($govData['address']); ?></p>
        <p><strong>Country:</strong> <?php echo htmlspecialchars($govData['country']); ?></p>
    </div>

    <form action="submit_bid.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="gov_id" value="<?php echo $gov_id; ?>">

        <label for="bid_price">Your Bid Price (₱)</label>
        <input type="number" step="0.01" name="bid_price" id="bid_price" required>

        <label for="delivery_date">Estimated Delivery Date</label>
        <input type="date" name="delivery_date" id="delivery_date" required>

        <label for="proposal_doc">Upload Proposal Document (PDF)</label>
        <input type="file" name="proposal_doc" id="proposal_doc" accept="application/pdf" required>

        <label for="notes">Additional Notes</label>
        <textarea name="notes" id="notes" placeholder="Add any important notes or details..."></textarea>

        <button type="submit">Submit Bid</button>
    </form>
</div>

</body>
</html>
