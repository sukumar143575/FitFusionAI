<?php
session_start();
include '../admin/includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = mysqli_real_escape_string($conn, $_SESSION['user']);
$userQuery = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
$userData = mysqli_fetch_assoc($userQuery);
$user_id = $userData['id'];

// Fetch user's orders
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Order History</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #fbc2eb, #a6c1ee);
            padding: 40px;
            margin: 0;
        }
        .order-container {
            max-width: 960px;
            margin: auto;
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .order {
            background: #f7f9fc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .order:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .order h3 {
            margin-bottom: 12px;
            color: #007bff;
        }
        .order p {
            margin: 6px 0;
            color: #444;
        }
        .order-items {
            margin-top: 10px;
        }
        .order-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .order-item img {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            object-fit: cover;
        }
        .item-info {
            font-size: 15px;
            color: #333;
        }
        .total {
            font-weight: bold;
            color: #28a745;
            margin-top: 12px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }
            .order-container {
                padding: 20px;
            }
            .order-item {
                flex-direction: column;
                align-items: flex-start;
            }
        }

         .checkout-btn {
            display: block;
            width: 250px;
            margin: 35px auto 0;
            padding: 14px;
            background: linear-gradient(to right, #007bff, #0056b3);
            color: white;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .checkout-btn:hover {
            background: linear-gradient(to right, #0056b3, #003d80);
            transform: translateY(-2px);
        }

    </style>
</head>
<body>

<div class="order-container">
    <h2>🧾 Your Order History</h2>

    <?php if (mysqli_num_rows($orders) > 0): ?>
        <?php while ($order = mysqli_fetch_assoc($orders)): ?>
            <div class="order">
                <h3>Order #<?= $order['id'] ?> – <?= date("d M Y, h:i A", strtotime($order['order_date'])) ?></h3>
                <p><strong>Name:</strong> <?= htmlspecialchars($order['fullname']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>

                <div class="order-items">
                    <?php
                    $order_id = $order['id'];
                    $items = mysqli_query($conn, "
                        SELECT oi.*, cl.name, cl.image 
                        FROM order_items oi 
                        JOIN clothes cl ON oi.clothes_id = cl.id 
                        WHERE oi.order_id = $order_id
                    ");
                    while ($item = mysqli_fetch_assoc($items)): ?>
                        <div class="order-item">
                            <img src="../admin/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <div class="item-info">
                                <?= htmlspecialchars($item['name']) ?> — 
                                <?= $item['quantity'] ?> × ₹<?= number_format($item['price'], 2) ?> = 
                                ₹<?= number_format($item['subtotal'], 2) ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <p class="total">Total: ₹<?= number_format($order['total'], 2) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; font-size: 18px; color: #666;">You haven’t placed any orders yet.</p>
    <?php endif; ?>
    <a href="products.php"  class="checkout-btn">Continue Shopping</a>
</div>

</body>
</html>
