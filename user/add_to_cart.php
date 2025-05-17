<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include '../admin/includes/db.php';

$username = mysqli_real_escape_string($conn, $_SESSION['user']);

// Get user ID
$userQuery = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
if (!$userQuery || mysqli_num_rows($userQuery) == 0) {
    die("User not found.");
}
$user = mysqli_fetch_assoc($userQuery);
$user_id = $user['id'];

// Get clothes ID from POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clothes_id'])) {
    $clothes_id = intval($_POST['clothes_id']);

    // Get clothes details
    $itemQuery = mysqli_query($conn, "SELECT * FROM clothes WHERE id = $clothes_id");
    if (!$itemQuery || mysqli_num_rows($itemQuery) == 0) {
        die("Item not found.");
    }
    $item = mysqli_fetch_assoc($itemQuery);

    // Check if already in cart
    $check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id AND clothes_id = $clothes_id");
    if (mysqli_num_rows($check) > 0) {
        // Update quantity
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND clothes_id = $clothes_id");
    } else {
        // Insert new
        mysqli_query($conn, "INSERT INTO cart (user_id, clothes_id, quantity) VALUES ($user_id, $clothes_id, 1)");
    }
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Added to Cart</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            padding: 40px;
            background-color: #f4f8fc;
            text-align: center;
        }
        .card {
            display: inline-block;
            padding: 30px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 8px 18px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        .card img {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
            background: #f9f9f9;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        h2 {
            color: #28a745;
        }
        .price {
            color: #28a745;
            font-weight: bold;
            font-size: 18px;
        }
        .actions a {
            display: inline-block;
            margin: 15px 10px 0;
            padding: 10px 20px;
            background-color: #0077cc;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
        .actions a:hover {
            background-color: #005fa3;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Item Added to Cart!</h2>
        <img src="../admin/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
        <h3><?= htmlspecialchars($item['name']) ?></h3>
        <p><strong>Type:</strong> <?= htmlspecialchars($item['type']) ?></p>
        <p><strong>Size:</strong> <?= htmlspecialchars($item['size']) ?></p>
        <p class="price">₹<?= number_format($item['price'], 2) ?></p>
        <div class="actions">
            <a href="products.php">Continue Shopping</a>
            <a href="my_cart.php">Go to Cart</a>
        </div>
    </div>
</body>
</html>
