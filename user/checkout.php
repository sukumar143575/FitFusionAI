<?php
session_start();
include '../admin/includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = mysqli_real_escape_string($conn, $_SESSION['user']);
$userResult = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
$userData = mysqli_fetch_assoc($userResult);

if (!$userData) {
    die("User not found. Please login again.");
}

$user_id = $userData['id'];

$query = "
    SELECT c.*, cl.name, cl.price, cl.size, cl.image 
    FROM cart c 
    JOIN clothes cl ON c.clothes_id = cl.id 
    WHERE c.user_id = $user_id
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #f5f7fa, #c3cfe2);
            margin: 0;
            padding: 30px;
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 30px;
            animation: slideUp 0.8s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        .cart-summary {
            margin-bottom: 30px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 20px;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            transition: background 0.3s ease;
        }

        .cart-item:hover {
            background: #f9f9f9;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
        }

        .item-details {
            flex: 1;
        }

        .item-details h4 {
            margin: 0;
            color: #444;
        }

        .item-details p {
            margin: 5px 0;
            color: #666;
        }

        .total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
            color: #28a745;
        }

        .form-section {
            margin-top: 30px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            transition: border 0.3s ease;
        }

        input:focus, textarea:focus {
            border-color: #28a745;
            outline: none;
        }

        .submit-btn {
            background: linear-gradient(90deg, #28a745, #218838);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background: linear-gradient(90deg, #218838, #1e7e34);
        }

        .empty-cart {
            text-align: center;
            color: #777;
            font-size: 18px;
            padding: 40px 0;
        }

        .empty-cart a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .empty-cart a:hover {
            text-decoration: underline;
        }

        .back-home-btn {
      display: block;
      margin: 20px auto 0;
      padding: 10px 20px;
      background: #f44336;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      text-decoration: none;
      text-align: center;
      transition: background 0.3s;
    }

    .back-home-btn:hover {
      background: #d32f2f;
    }
    </style>
</head>
<body>
<div class="container">
    <h2>🧾 Checkout Summary</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="cart-summary">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php $subtotal = $row['price'] * $row['quantity']; $total += $subtotal; ?>
                <div class="cart-item">
                    <img src="../admin/uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    <div class="item-details">
                        <h4><?= htmlspecialchars($row['name']) ?> (<?= $row['size'] ?>)</h4>
                        <p>Price: ₹<?= number_format($row['price'], 2) ?> × <?= $row['quantity'] ?> = ₹<?= number_format($subtotal, 2) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
            <div class="total">Total: ₹<?= number_format($total, 2) ?></div>
        </div>

        <form method="post" action="place_order.php" class="form-section">
            <a href="http://localhost/clothes_Recommendation_System/user/my_cart.php" class="back-home-btn">BACK</a>
            <h3 style="margin-bottom: 15px;">🚚 Shipping Information</h3>
            
            <label for="fullname">Full Name</label>
            <input type="text" name="fullname" id="fullname" required>

            <label for="address">Address</label>
            <textarea name="address" id="address" rows="3" required></textarea>

            <label for="phone">Phone Number</label>
            <input type="text" name="phone" id="phone" required>

            <input type="hidden" name="total" value="<?= $total ?>">
            <button type="submit" class="submit-btn">Place Order</button>
        </form>
    <?php else: ?>
        <div class="empty-cart">
            <p>Your cart is empty. 🛒</p>
            <a href="products.php">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
