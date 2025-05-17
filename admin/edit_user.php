<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

include 'includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: users_details.php");
    exit();
}

$id = (int) $_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $update_query = "UPDATE users SET 
                        username = '$username',
                        email = '$email',
                        gender = '$gender',
                        contact = '$contact',
                        password = '$password'
                     WHERE id = $id";
    
    if (mysqli_query($conn, $update_query)) {
        header("Location: users_details.php");
        exit();
    } else {
        $error = "Failed to update user.";
    }
}

// Fetch current user data
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($user_query);

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit User</title>
  <style>
    body { font-family: Arial; background: #f9f9f9; padding: 40px; }
    form { background: #fff; padding: 20px; max-width: 500px; margin: auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    input, select { width: 100%; padding: 10px; margin-bottom: 15px; }
    label { font-weight: bold; }
    button { padding: 10px 20px; background: #2980b9; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
    .error { color: red; margin-bottom: 10px; }
  </style>
</head>
<body>

<h2 style="text-align:center;">Edit User #<?= $user['id'] ?></h2>

<?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

<form method="POST">
  <label>Name:</label>
  <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

  <label>Email:</label>
  <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

  <label>Gender:</label>
  <select name="gender" required>
    <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
    <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
    <option value="Other" <?= $user['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
  </select>

  <label>Phone:</label>
  <input type="text" name="contact" value="<?= htmlspecialchars($user['contact']) ?>" required>

  <label>Password:</label>
  <input type="text" name="password" value="<?= htmlspecialchars($user['password']) ?>" required>

  <button type="submit">Update User</button>
</form>

</body>
</html>
