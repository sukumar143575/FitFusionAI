<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include '../admin/includes/db.php';

$username = mysqli_real_escape_string($conn, $_SESSION['user']);

// Fetch user data
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
if (!$userQuery || mysqli_num_rows($userQuery) === 0) {
    die("User not found.");
}
$user = mysqli_fetch_assoc($userQuery);

// Ensure gender preference is set
if (empty($user['gender'])) {
    header("Location: set_preferences.php");
    exit();
}

$gender = mysqli_real_escape_string($conn, $user['gender']);

// Get clothes filtered only by gender
$result = mysqli_query($conn, "SELECT * FROM clothes WHERE gender='$gender'");
if (!$result) {
    die("Clothes fetch failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recommended Clothes</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(120deg, #fef9f4, #e3f2fd);
            color: #333;
        }
        h2, h3 {
            text-align: center;
        }
        .links {
            text-align: center;
            margin-bottom: 30px;
        }
        .links a {
            text-decoration: none;
            color: #0077cc;
            margin: 0 15px;
            font-weight: bold;
        }
        .links a:hover {
            color: #004c99;
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.15);
        }
        .card img {
            width: 100%;
            max-height: 200px;
            object-fit: contain;
            background: #f8f8f8;
        }
        .card h4 {
            margin: 5px 0;
            font-size: 18px;
        }
        .card p {
            margin: 3px 0;
            font-size: 14px;
        }
        .price {
            font-weight: bold;
            color: #28a745;
            font-size: 16px;
            margin-top: 10px;
        }
        .card form {
            margin-top: 10px;
        }
        .card button {
            padding: 10px;
            background-color: #ff5722;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        .card button:hover {
            background-color: #e64a19;
        }
        .no-results {
            text-align: center;
            color: #555;
            font-size: 18px;
            margin-top: 30px;
        }
        .rating {
    display: flex;
    align-items: center;
    margin-bottom: 18px;
  }
  .rating svg {
    fill: #f2b01e;
    width: 18px;
    height: 18px;
    margin-right: 3px;
  }
  .rating span {
    font-size: 0.9rem;
    color: #777;
    margin-left: 8px;
    user-select: none;
  }
    </style>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($username) ?>!</h2>
    <div class="links">
        <a href="logout.php">Logout</a>
        <a href="set_preferences.php">Edit Preferences</a>
        <a href="order_history.php">🧾 My Orders</a>
        
    </div>
    
    <h3>Recommended Clothes for You (Filtered by Gender: <?= htmlspecialchars($gender) ?>)</h3>

    <div class="card-grid">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <img src="../admin/uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    <h4><?= htmlspecialchars($row['name']) ?></h4>
                    <p><strong>Type:</strong> <?= htmlspecialchars($row['type']) ?></p>
                    <p><strong>Size:</strong> <?= htmlspecialchars($row['size']) ?></p>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($row['gender']) ?></p>
                    <div class="rating" aria-label="Product rating">
            <!-- Random star rating out of 5 for demo -->
            <?php
              $stars = rand(3,5);
              for ($i = 1; $i <= 5; $i++) {
                if ($i <= $stars) {
                  echo '<svg viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431L24 9.748l-6 5.839L19.335 24 12 19.897 4.665 24 6 15.587 0 9.748l8.332-1.73z"/></svg>';
                } else {
                  echo '<svg style="fill:#ccc" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431L24 9.748l-6 5.839L19.335 24 12 19.897 4.665 24 6 15.587 0 9.748l8.332-1.73z"/></svg>';
                }
              }
            ?>
            <span>(<?= $stars ?>.0)</span>
          </div>
                    <p class="price">₹<?= number_format($row['price'], 2) ?></p>
                    <form method="POST" action="add_to_cart.php">
                        <input type="hidden" name="clothes_id" value="<?= $row['id'] ?>">
                        <button type="submit">Add to Cart</button>
                    </form>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-results">No clothes found for your selected gender.</p>
        <?php endif; ?>
    </div>
</body>
</html>
