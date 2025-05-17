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
$user_id = $userData['id'];

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $cart_id = (int)$_POST['cart_id'];
        $quantity = (int)$_POST['quantity'];
        if ($quantity > 0) {
            mysqli_query($conn, "UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = $user_id");
        }
    }

    if (isset($_POST['remove_item'])) {
        $cart_id = (int)$_POST['cart_id'];
        mysqli_query($conn, "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    }

    header("Location: my_cart.php");
    exit();
}

// Get cart items
$query = "
    SELECT c.*, cl.name, cl.price, cl.size, cl.image 
    FROM cart c 
    JOIN clothes cl ON c.clothes_id = cl.id 
    WHERE c.user_id = $user_id
";
$result = mysqli_query($conn, $query);
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <!-- Keep the PHP part same -->

<!-- Inside the <head> tag, add this script: -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const quantityInputs = document.querySelectorAll("input[name='quantity']");

    quantityInputs.forEach(input => {
        input.addEventListener("change", () => {
            const form = input.closest("form");
            if (input.value > 0) {
                const hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "update_quantity";
                hiddenInput.value = "1";
                form.appendChild(hiddenInput);
                form.submit();
            } else {
                alert("Quantity must be greater than 0");
                input.value = 1;
            }
        });
    });
});
</script>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(to right, #f9f9f9, #e3f2fd);
            padding: 10px;
            animation: fadeIn 0.8s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 32px;
        }

        .cart-container {
            max-width: 1000px;
            margin: auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 30px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 20px 0;
            transition: background 0.3s ease;
        }

        .cart-item:hover {
            background-color: #f7faff;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 25px;
        }

        .item-details {
            flex: 1;
        }

        .item-details h4 {
            margin: 0 0 10px;
            font-size: 20px;
            color: #444;
        }

        .item-details p {
            margin: 5px 0;
            color: #666;
        }

        .price {
            font-weight: bold;
            color: #28a745;
        }

        .cart-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }

        .cart-actions input[type=number] {
            width: 60px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            transition: border-color 0.3s ease;
        }

        .cart-actions input:focus {
            border-color: #28a745;
        }

        .cart-actions button {
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .update-btn {
            background-color: #17a2b8;
            color: white;
        }

        .update-btn:hover {
            background-color: #138496;
        }

        .remove-btn {
            background-color: #dc3545;
            color: white;
        }

        .remove-btn:hover {
            background-color: #bd2130;
        }

        .total {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-top: 25px;
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

        .empty-cart {
            text-align: center;
            font-size: 20px;
            color: #666;
            padding: 60px 20px;
        }

        .empty-cart a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .empty-cart a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>🛒 Your Shopping Cart</h2>
    <div class="cart-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php $subtotal = $row['price'] * $row['quantity']; $total += $subtotal; ?>
                <div class="cart-item">
                    <img src="../admin/uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    <div class="item-details">
                        <h4><?= htmlspecialchars($row['name']) ?></h4>
                        <p><strong>Size:</strong> <?= htmlspecialchars($row['size']) ?></p>
                        <p class="price">Price: ₹<?= number_format($row['price'], 2) ?></p>
                        <p class="price">Subtotal: ₹<?= number_format($subtotal, 2) ?></p>

                <form method="post" class="cart-actions">
                    <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                    <input type="number" name="quantity" value="<?= $row['quantity'] ?>" min="1">
                    <button type="submit" name="remove_item" class="remove-btn" onclick="return confirm('Remove this item from cart?')">Remove</button>
                </form>

                    </div>
                </div>
            <?php endwhile; ?>
            <div class="total">Total: ₹<?= number_format($total, 2) ?></div>
            <a href="products.php"  class="checkout-btn">Continue Shopping</a>
            <a href="checkout.php" class="checkout-btn">🧾 Proceed to Checkout</a>
        <?php else: ?>
            <div class="empty-cart">
                <p>Your cart is empty.</p>
                <a href="products.php">Go to Products</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
