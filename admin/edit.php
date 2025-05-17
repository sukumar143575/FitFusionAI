<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

include 'includes/db.php';

if (!isset($_GET['id'])) {
    echo "No item selected.";
    exit();
}

$id = intval($_GET['id']);

// Fetch existing item data
$query = "SELECT * FROM clothes WHERE id = $id";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "Item not found.";
    exit();
}
$item = mysqli_fetch_assoc($result);

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $price = floatval($_POST['price']);

    // Optional: Handle image update here if needed

    $updateQuery = "UPDATE clothes SET name='$name', type='$type', size='$size', gender='$gender', price=$price WHERE id=$id";
    if (mysqli_query($conn, $updateQuery)) {
        $message = "Item updated successfully.";
    } else {
        $message = "Error updating item: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Item</title>
    <style>
        /* Basic styling */
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        input, select { width: 100%; padding: 8px; margin: 8px 0; }
        input[type=submit] { background-color: #28a745; color: white; border: none; cursor: pointer; }
        input[type=submit]:hover { background-color: #218838; }
        .message { padding: 10px; margin-bottom: 10px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Item</h2>
        <?php if ($message): ?>
            <div class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>

            <label>Type:</label>
            <input type="text" name="type" value="<?= htmlspecialchars($item['type']) ?>" required>

            <label>Size:</label>
            <input type="text" name="size" value="<?= htmlspecialchars($item['size']) ?>" required>

            <label>Gender:</label>
            <select name="gender" required>
                <option value="Male" <?= $item['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $item['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Unisex" <?= $item['gender'] == 'Unisex' ? 'selected' : '' ?>>Unisex</option>
            </select>

            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="<?= $item['price'] ?>" required>

            <input type="submit" value="Update Item">
        </form>
        <p><a href="dashboard.php">← Back to Dashboard</a></p>
    </div>
</body>
</html>
