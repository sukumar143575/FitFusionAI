<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

include 'includes/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $size = $_POST['size'];
    $gender = $_POST['gender'];
    $category = $_POST['category'];
    $price = $_POST['price'];

    // Image Upload
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $query = "INSERT INTO clothes (name, type, size, gender, category, price, image)
                  VALUES ('$name', '$type', '$size', '$gender', '$category', '$price', '$image')";
        if (mysqli_query($conn, $query)) {
            $message = "<div class='success'>Item added successfully. <a href='dashboard.php'>Go back</a></div>";
        } else {
            $message = "<div class='error'>Error: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='error'>Image upload failed.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #f1f2b5, #135058);
            padding: 30px;
            color: #333;
        }
        .form-box {
            background: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            max-width: 450px;
            margin: auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border: none;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .success, .error {
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
        a {
            text-decoration: none;
            color: #0077cc;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Add Clothing Item</h2>
        <?php echo $message; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Type:</label>
            <input type="text" name="type" required>

            <label>Size:</label>
            <input type="text" name="size" required>

            <label>Gender:</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Unisex">Unisex</option>
            </select>

            <label>Category:</label>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Casual">Casual</option>
                <option value="Formal">Formal</option>
                <option value="Party">Party</option>
                <option value="Sportswear">Sportswear</option>
            </select>

            <label>Price:</label>
            <input type="number" step="0.01" name="price" required>

            <label>Image:</label>
            <input type="file" name="image" accept="image/*" required>

            <input type="submit" value="Add Item">
        </form>
        <p><a href="dashboard.php">← Back to Dashboard</a></p>
    </div>
</body>
</html>
