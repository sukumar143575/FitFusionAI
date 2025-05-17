<?php
include 'admin/includes/db.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : '';

$query = "SELECT * FROM clothes WHERE 1";
if ($search !== '') {
    $query .= " AND name LIKE '%$search%'";
}
if ($filter !== '') {
    $query .= " AND gender = '$filter'";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>All Clothes - Mini Amazon Store</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    font-family: 'Roboto', sans-serif;
    background: #f5f7fa;
    color: #333;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    padding: 30px 15px 50px;
  }

  h1 {
    text-align: center;
    font-weight: 700;
    font-size: 2.8rem;
    margin-bottom: 30px;
    color: #111;
    letter-spacing: 1.2px;
    text-shadow: 0 1px 3px rgba(0,0,0,0.1);
    animation: fadeInDown 1s ease;
  }

  .search-bar {
    max-width: 700px;
    margin: 0 auto 40px;
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
  }

  .search-bar input[type="text"],
  .search-bar select {
    flex: 1 1 220px;
    padding: 14px 18px;
    font-size: 1rem;
    border: 2px solid #ddd;
    border-radius: 8px;
    transition: border-color 0.3s ease;
    outline-offset: 2px;
    box-shadow: 0 0 0 rgba(0,0,0,0);
  }
  .search-bar input[type="text"]:focus,
  .search-bar select:focus {
    border-color: #007BFF;
    box-shadow: 0 0 6px rgba(0,123,255,0.4);
  }

  .search-bar button {
    background-color: #007BFF;
    border: none;
    padding: 14px 28px;
    color: white;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    flex: 0 0 auto;
    box-shadow: 0 6px 12px rgba(0,123,255,0.4);
    user-select: none;
    text-decoration: none;
  }
  .search-bar button:hover {
    background-color: #0056b3;
    box-shadow: 0 10px 20px rgba(0,86,179,0.6);
  }

  .products-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
    animation: fadeInUp 0.9s ease forwards;
  }

  .product-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    position: relative;
  }
  .product-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 14px 40px rgba(0, 0, 0, 0.15);
  }

  .product-image {
    width: 100%;
    height: 220px;
    object-fit: contain;
    background: #f8f9fa;
    transition: transform 0.4s ease;
  }
  .product-card:hover .product-image {
    transform: scale(1.05);
  }

  .product-info-container {
    padding: 20px 25px 30px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .product-name {
    font-weight: 700;
    font-size: 1.2rem;
    margin-bottom: 8px;
    color: #222;
    min-height: 3em;
  }

  .product-details {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 14px;
    line-height: 1.3;
  }

  .product-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #007BFF;
    margin-bottom: 14px;
  }

  /* Rating stars */
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

  .add-to-cart-btn {
    background-color: #28a745;
    border: none;
    padding: 12px 0;
    font-weight: 600;
    color: white;
    font-size: 1rem;
    border-radius: 12px;
    cursor: pointer;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    width: 100%;
    box-shadow: 0 6px 12px rgba(40, 167, 69, 0.5);
    user-select: none;
  }
  .add-to-cart-btn:hover {
    background-color: #1e7e34;
    box-shadow: 0 10px 20px rgba(30, 126, 52, 0.7);
  }

  .back-home-btn {
      margin: 20px auto 0;
      padding: 10px 20px;
      background: #f44336;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.3s;
    }

    .back-home-btn:hover {
      background: #d32f2f;
    }

  /* Responsive adjustments */
  @media (max-width: 600px) {
    .search-bar {
      flex-direction: column;
      gap: 12px;
      max-width: 100%;
    }
    .search-bar button {
      width: 100%;
    }
  }

  /* Animations */
  @keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-25px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(25px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
</head>
<body>

<h1>All Clothes Collection</h1>

<form class="search-bar" method="GET" role="search" aria-label="Search clothes">
  <input type="text" name="search" placeholder="Search by name..." value="<?= htmlspecialchars($search) ?>" aria-label="Search clothes by name" />
  <select name="filter" aria-label="Filter by gender">
    <option value="">All Genders</option>
    <option value="Male" <?= $filter === 'Male' ? 'selected' : '' ?>>Male</option>
    <option value="Female" <?= $filter === 'Female' ? 'selected' : '' ?>>Female</option>
    <option value="Unisex" <?= $filter === 'Unisex' ? 'selected' : '' ?>>Unisex</option>
  </select>
  <div>
  <button type="submit" aria-label="Search products">Search</button>
  <a href="http://localhost/clothes_Recommendation_System/index.php" class="back-home-btn">Back to Home</a>
</div>
</form>

<div class="products-container" aria-live="polite">
  <?php if (mysqli_num_rows($result) === 0) : ?>
    <p style="text-align:center; font-size:1.2rem; color:#666; grid-column: 1 / -1;">No products found matching your criteria.</p>
  <?php else: ?>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <div class="product-card" tabindex="0" role="article" aria-label="<?= htmlspecialchars($row['name']) ?> product card">
        <img src="admin/uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="product-image" loading="lazy" />
        <div class="product-info-container">
          <div class="product-name"><?= htmlspecialchars($row['name']) ?></div>
          <div class="product-details">
            <div>Type: <?= htmlspecialchars($row['type']) ?></div>
            <div>Size: <?= htmlspecialchars($row['size']) ?></div>
            <div>Gender: <?= htmlspecialchars($row['gender']) ?></div>
          </div>
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
          <div class="product-price">₹<?= number_format($row['price'], 2) ?></div>
          <button class="add-to-cart-btn" type="button" onclick="window.location.href='user/login.php'">Add to Cart 🛒</button>
        </div>
      </div>
    <?php } ?>
  <?php endif; ?>
</div>

</body>
</html>
