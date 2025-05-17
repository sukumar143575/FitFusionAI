<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User & Admin Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(120deg, #f6d365 0%, #fda085 100%);
      flex-wrap: wrap;
      gap: 40px;
    }

    .container {
      background: #fff;
      border-radius: 20px;
      padding: 35px;
      width: 340px;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
      animation: popIn 0.8s ease forwards;
      transform: scale(0.9);
      opacity: 0;
      transition: all 0.3s ease;
    }

    .container:hover {
      transform: scale(1);
    }

    @keyframes popIn {
      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
      font-weight: 600;
    }

    .input-group {
      position: relative;
      margin-bottom: 18px;
    }

    .input-group i {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      color: #aaa;
    }

    .input-group input {
      width: 100%;
      padding: 12px 12px 12px 40px;
      border: 1.5px solid #ddd;
      border-radius: 10px;
      font-size: 15px;
      transition: 0.3s;
    }

    .input-group input:focus {
      border-color: #ff8566;
      box-shadow: 0 0 6px #ffc4a3;
      outline: none;
    }

    input[type="submit"] {
      width: 100%;
      padding: 12px;
      background: linear-gradient(to right, #ff7e5f, #feb47b);
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
      box-shadow: 0 4px 10px rgba(255, 126, 95, 0.5);
    }

    input[type="submit"]:hover {
      background: linear-gradient(to right, #feb47b, #ff7e5f);
    }

    .message.error {
      margin-top: 12px;
      background-color: #f8d7da;
      color: #721c24;
      padding: 10px;
      border-radius: 8px;
      font-size: 14px;
      text-align: center;
    }

    .extras {
      margin-top: 15px;
      text-align: center;
      font-size: 14px;
      color: #333;
    }

    .extras a {
      color: #ff4b2b;
      text-decoration: none;
      font-weight: 600;
    }

    .extras a:hover {
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

        .icon-wrapper {
        text-align: center;
        margin-bottom: 10px;
        
    }

        .icon-wrapper i {
        font-size: 50px;  /* increase size */
        color: #ff7e5f;    /* icon color */
        animation-duration: 1.2s;  /* optional: control fa-shake speed */
    }


    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }
    }

  </style>
</head>
<body>

<?php
session_start();
include '../admin/includes/db.php';

$loginError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $loginType = $_POST['login_type'] ?? '';

    if ($loginType !== "user" && $loginType !== "admin") {
        $loginError = "Invalid login type.";
    } else {
        $table = $loginType === "admin" ? "admin" : "users";
        $redirect = $loginType === "admin" ? "../admin/dashboard.php" : "products.php";

        $query = "SELECT * FROM $table WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $_SESSION[$loginType] = $username;
            header("Location: $redirect");
            exit();
        } else {
            $loginError = "Invalid $loginType login credentials.";
        }
    }
}
?>


<!-- User Login Container -->
<div class="container">
    <div class="icon-wrapper">
    <i class="fa-solid fa-user"></i>
  </div>
  <h2>User Login</h2>
<form method="POST">
  <input type="hidden" name="login_type" value="user">
  <div class="input-group">
    <i class="fas fa-user"></i>
    <input type="text" name="username" placeholder="Username" required>
  </div>
  <div class="input-group">
    <i class="fas fa-lock"></i>
    <input type="password" name="password" placeholder="Password" required>
  </div>
  <input type="submit" value="Login">
  
  <?php if ($loginError): ?>
    <div class="message error"><?= $loginError ?></div>
    <div class="extras">
      <a href="https://wa.me/9964986491?text=Hi%20Admin%2C%20I%20forgot%20my%20password.%20Please%20help%20me%20reset%20it." target="_blank">
        Forgot Password? Contact Admin on WhatsApp
      </a>
    </div>
  <?php endif; ?>

  <div class="extras">
    Don’t have an account? <a href="http://localhost/clothes_Recommendation_System/user/register.php">Register</a>
  </div>
  <a href="http://localhost/clothes_Recommendation_System/index.php" class="back-home-btn">Back to Home</a>
</form>

  
</div>

</body>
</html>
