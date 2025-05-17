<?php
$order_id = $_GET['order_id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Thank You for Your Order</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');

  body {
    margin: 0;
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    font-family: 'Poppins', sans-serif;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    overflow: hidden;
  }

  .container {
    background: rgba(255, 255, 255, 0.1);
    padding: 40px 60px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    max-width: 400px;
    animation: fadeInUp 1s ease forwards;
  }

  h2 {
    font-size: 2.5rem;
    margin-bottom: 15px;
    animation: popIn 0.8s ease forwards;
  }

  p {
    font-size: 1.25rem;
    margin-bottom: 30px;
    letter-spacing: 0.04em;
    animation: fadeIn 1.5s ease forwards;
  }

  strong {
    color: #ffd700;
    font-weight: 700;
  }

  a {
    background: #ffd700;
    color: #1a1a1a;
    text-decoration: none;
    font-weight: 700;
    padding: 15px 30px;
    border-radius: 50px;
    box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
    transition: background 0.3s ease, transform 0.3s ease;
    display: inline-block;
    animation: popIn 1.2s ease forwards;
  }

  a:hover {
    background: #ffea00;
    transform: scale(1.05);
  }

  /* Animations */
  @keyframes fadeInUp {
    0% {
      opacity: 0;
      transform: translateY(40px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes popIn {
    0% {
      opacity: 0;
      transform: scale(0.8);
    }
    100% {
      opacity: 1;
      transform: scale(1);
    }
  }

  @keyframes fadeIn {
    0% {
      opacity: 0;
    }
    100% {
      opacity: 1;
    }
  }
</style>
</head>
<body>
  <div class="container" role="main" aria-live="polite" aria-atomic="true">
    <h2>🎉 Thank You for Your Order!</h2>
    <p>Your order ID is <strong>#<?= htmlspecialchars($order_id) ?></strong>.</p>
    <a href="products.php" aria-label="Back to shopping page">Back to Shopping</a>
  </div>
</body>
</html>
