<?php
session_start();
include '../admin/includes/db.php'; // Make sure the path is correct

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = mysqli_real_escape_string($conn, $_SESSION['user']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $preferred_type = mysqli_real_escape_string($conn, $_POST['preferred_type']);

    $query = "UPDATE users SET gender='$gender', size='$size', preferred_type='$preferred_type' WHERE username='$username'";

    if (mysqli_query($conn, $query)) {
        // ✅ Redirect if update is successful
        header("Location: products.php");
        exit();
    } else {
        // ❌ Handle update error
        echo "Error updating preferences: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Update Preferences</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #f3f4f6;
            overflow: hidden;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 14px;
            box-shadow: 0 12px 24px rgba(0,0,0,0.2);
            width: 320px;
            padding: 30px 25px 35px;
            text-align: center;
            animation: fadeInUp 1s ease forwards;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #764ba2, #667eea);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            font-weight: 800;
            letter-spacing: 1px;
            animation: textGlow 3s ease-in-out infinite alternate;
        }

        p.description {
            font-size: 0.85rem;
            margin-bottom: 25px;
            color: #555;
        }

        form {
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
            font-size: 0.85rem;
        }

        select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 18px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 0.95rem;
            outline: none;
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="gray" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px 16px;
            cursor: pointer;
        }

        select:focus {
            border-color: #764ba2;
            box-shadow: 0 0 6px #764ba2aa;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #764ba2;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.4s ease, transform 0.2s ease;
            box-shadow: 0 4px 12px rgba(118, 75, 162, 0.5);
        }

        input[type="submit"]:hover {
            background: #5b3a99;
            transform: scale(1.04);
        }

        input[type="submit"]:active {
            transform: scale(0.96);
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes textGlow {
            0% {
                text-shadow: 0 0 3px #764ba2, 0 0 6px #667eea;
            }
            100% {
                text-shadow: 0 0 10px #764ba2, 0 0 20px #667eea;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Set Preferences</h1>
        <p class="description">Help us recommend the perfect outfit by selecting your preferences below.</p>

        <form action="set_preferences.php" method="POST" autocomplete="off">
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Unisex">Unisex</option>
            </select>

            <label for="preferred_type">Preferred Type:</label>
            <select id="preferred_type" name="preferred_type" required>
                <option value="" disabled selected>Select Type</option>
                <option value="Shirt">Shirt</option>
                <option value="T-Shirt">T-Shirt</option>
                <option value="Jeans">Jeans</option>
                <option value="Shorts">Shorts</option>
                <option value="Kurta">Kurta</option>
            </select>

            <label for="size">Size:</label>
            <select id="size" name="size" required>
                <option value="" disabled>Select Size</option>
                <option value="S">S</option>
                <option value="M" selected>M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
            </select>

            <input type="submit" value="Save Preferences" />
        </form>
    </div>
</body>
</html>
