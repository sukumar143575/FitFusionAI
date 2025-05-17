<?php
include '../admin/includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $password = $_POST['password'];

    // Hash password securely
   $hashed_password = $password; // store as plain text

    // Check for duplicates (optional but recommended)
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<div class='message error'>Username or Email already exists.</div>";
    } else {
        // Insert user securely
        $stmt = $conn->prepare("INSERT INTO users (username, email, contact, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $contact, $hashed_password);

        if ($stmt->execute()) {
            echo "<div class='message success'>Registration successful. <a href='login.php'>Login here</a></div>";
        } else {
            echo "<div class='message error'>Error: " . htmlspecialchars($stmt->error) . "</div>";
        }

        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
             background: linear-gradient(to right, #74ebd5, #ACB6E5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
            animation: slideIn 1s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        
        .input-group {
            position: relative;
            margin-bottom: 15px;
        }

        /* .input-group i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #aaa;
        } */

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

        input[type="text"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: 0.3s ease;
            animation: floatIn 1s ease forwards;
            opacity: 0;
        }

        input[type="text"]:nth-child(1),
        input[type="password"]:nth-child(2) {
            animation-delay: 0.3s;
        }

        @keyframes floatIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
            from {
                opacity: 0;
                transform: translateY(15px);
            }
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #74ebd5;
            outline: none;
            transform: scale(1.02);
        }

        input[type="submit"] {
            background-color: #74ebd5;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            animation: fadeIn 1.2s ease-out;
        }

        input[type="submit"]:hover {
            background-color: #58cbd0;
            transform: scale(1.05);
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: bold;
            animation: fadeIn 0.8s ease-out;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        a {
            color: #0077cc;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body >
    <div class="form-container">
        <h2>User Registration</h2>
        <form method="POST" action="">
    <div class="input-group">
        <input type="text" name="username" placeholder="Username" required>
    </div>
    <div class="input-group">
        <input type="text" name="email" placeholder="Email" required
               pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
               title="Please enter a valid email address.">
    </div>
    <div class="input-group">
        <input type="text" name="contact" placeholder="Contact Number" required
       pattern="\d{10}" maxlength="10" title="Please enter only numbers exactly 10 digits">

    </div>
    <div class="input-group">
        <input type="password" name="password" placeholder="Password" required>
    </div>
    <input type="submit" value="Register"><br><hr>
    Already Registered?
    <a href="login.php"><button type="button" style="border-radius:5px;">Login</button></a>
</form>

    </div>
</body>
</html>
