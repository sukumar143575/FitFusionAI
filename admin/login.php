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
            background: linear-gradient(120deg, #89f7fe 0%, #66a6ff 100%);
            gap: 30px;
            flex-wrap: wrap;
        }

        .container {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            width: 320px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: slideFade 0.8s ease forwards;
            transform: translateY(20px);
            opacity: 0;
            transition: 0.3s;
        }

        .container:hover {
            transform: scale(1.03);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        @keyframes slideFade {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .input-group {
            position: relative;
            margin-bottom: 15px;
        }

        .input-group i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #aaa;
        }

        .input-group input {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #66a6ff;
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #66a6ff;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background: #4d8ce0;
        }

        .message.error {
            margin-top: 10px;
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 6px;
            font-size: 14px;
            text-align: center;
        }

        .icon-wrapper {
            text-align: center;
            margin-bottom: 15px;
        }

        .icon-wrapper i {
            font-size: 60px;
            color: #66a6ff;
            animation-duration: 1.5s;
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $loginType = $_POST['login_type']; // "user" or "admin"

    $table = $loginType === "admin" ? "admin" : "users";
    $redirect = $loginType === "admin" ? "../admin/dashboard.php" : "products.php";

    $query = "SELECT * FROM $table WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION[$loginType] = $username;
        header("Location: $redirect");
        exit();
    } else {
        echo "<div class='message error'>Invalid $loginType login credentials.</div>";
    }
}
?>

   <!-- Admin Login Container -->
<div class="container" style="animation-delay: 0.4s;">
    <!-- Centered icon -->
    <div class="icon-wrapper">
        <i class="fa-solid fa-user-shield fa-shake"></i>
    </div>

    <h2>Admin Login</h2>

    <form method="POST">
        <input type="hidden" name="login_type" value="admin">

        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Admin Username" required>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <input type="submit" value="Login"><hr>
        <a href="http://localhost/clothes_Recommendation_System/index.php" class="back-home-btn">Back to Home</a>
    </form>
</div>
</body>
</html>
