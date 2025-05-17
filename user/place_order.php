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

// Validate form input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $total = floatval($_POST['total']);

    if (empty($fullname) || empty($address) || empty($phone)) {
        die("All fields are required.");
    }

    // Insert order
    $orderQuery = "INSERT INTO orders (user_id, fullname, address, phone, total, order_date) 
                   VALUES ($user_id, '$fullname', '$address', '$phone', $total, NOW())";
    if (!mysqli_query($conn, $orderQuery)) {
        die("Failed to place order: " . mysqli_error($conn));
    }

    $order_id = mysqli_insert_id($conn); // Get inserted order ID

    // Fetch cart items
    $cartQuery = "
        SELECT c.*, cl.name, cl.price 
        FROM cart c 
        JOIN clothes cl ON c.clothes_id = cl.id 
        WHERE c.user_id = $user_id
    ";
    $cartResult = mysqli_query($conn, $cartQuery);

    // Insert into order_items
    while ($item = mysqli_fetch_assoc($cartResult)) {
        $clothes_id = $item['clothes_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $subtotal = $price * $quantity;

        mysqli_query($conn, "INSERT INTO order_items (order_id, clothes_id, quantity, price, subtotal) 
                             VALUES ($order_id, $clothes_id, $quantity, $price, $subtotal)");
    }

    // Clear cart
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

    // Redirect to thank you page or show message
    echo "<script>
        alert('Order placed successfully! Your order ID is $order_id');
        window.location.href = 'thank_you.php?order_id=$order_id';
    </script>";
    exit();
} else {
    header("Location: checkout.php");
    exit();
}
