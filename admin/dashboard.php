<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();    
}
include 'includes/db.php';

// Search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$limit = 5; // Items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM clothes WHERE name LIKE '%$search%' OR type LIKE '%$search%' LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM clothes WHERE name LIKE '%$search%' OR type LIKE '%$search%'");
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin Dashboard</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

  * {
    box-sizing: border-box;
  }

  body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
    margin: 0;
    padding: 30px;
    color: #2c3e50;
    min-height: 100vh;
  }

  h2 {
    text-align: center;
    color: #2c3e50;
    font-weight: 600;
    font-size: 2.5rem;
    margin-bottom: 30px;
    text-shadow: 0 2px 6px rgba(0,0,0,0.1);
    animation: fadeInDown 0.8s ease forwards;
  }

  .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 30px;
  }

  .search-box {
    flex-grow: 1;
    max-width: 320px;
    display: flex;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-radius: 8px;
    overflow: hidden;
    animation: fadeIn 1s ease forwards;
  }

  .search-box input[type="text"] {
    flex-grow: 1;
    border: none;
    padding: 12px 16px;
    font-size: 1rem;
    outline: none;
    transition: background-color 0.3s ease;
  }
  .search-box input[type="text"]:focus {
    background-color: #eef6f9;
  }

  .search-box input[type="submit"] {
    border: none;
    background-color: #2980b9;
    color: white;
    padding: 0 20px;
    cursor: pointer;
    font-weight: 600;
    letter-spacing: 0.05em;
    transition: background-color 0.3s ease;
  }
  .search-box input[type="submit"]:hover {
    background-color: #1c5980;
  }

  .top-bar > div {
    display: flex;
    gap: 12px;
  }
  .top-bar a {
    text-decoration: none;
    padding: 12px 22px;
    border-radius: 50px;
    background: linear-gradient(45deg, #6a11cb, #2575fc);
    color: white;
    font-weight: 600;
    box-shadow: 0 6px 15px rgba(101,42,255,0.45);
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    user-select: none;
  }
  .top-bar a:hover {
    box-shadow: 0 10px 25px rgba(101,42,255,0.75);
    transform: translateY(-3px);
  }

  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.1);
    overflow: hidden;
    animation: fadeIn 1.2s ease forwards;
  }

  thead tr {
    background: #2980b9;
    color: white;
    font-weight: 600;
  }
  thead th {
    padding: 14px 20px;
    text-align: center;
    font-size: 1rem;
  }

  tbody tr {
    background: #f9faff;
    box-shadow: 0 0 15px rgba(41, 128, 185, 0.1);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    cursor: default;
    border-radius: 10px;
    animation: shimmer 2s infinite;
  }
  tbody tr:hover {
    background-color: #e1f0ff;
    box-shadow: 0 4px 20px rgba(41, 128, 185, 0.3);
  }
  tbody tr:last-child {
    margin-bottom: 20px;
  }

  tbody td {
    padding: 15px 12px;
    font-size: 0.95rem;
    text-align: center;
    vertical-align: middle;
  }

  img.thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  .action-btn {
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    user-select: none;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    display: inline-block;
    margin: 0 4px;
    text-decoration: none;
  }
  .edit-btn {
    background-color: #27ae60;
    color: white;
  }
  .edit-btn:hover {
    background-color: #1e8449;
    box-shadow: 0 6px 15px rgba(39, 174, 96, 0.6);
    transform: translateY(-2px);
  }

  .delete-btn {
    background-color: #e74c3c;
    color: white;
  }
  .delete-btn:hover {
    background-color: #c0392b;
    box-shadow: 0 6px 15px rgba(231, 76, 60, 0.6);
    transform: translateY(-2px);
  }

  .pagination {
    text-align: center;
    margin-top: 30px;
  }
  .pagination a {
    padding: 10px 16px;
    margin: 0 6px;
    border: 2px solid #2980b9;
    border-radius: 50px;
    color: #2980b9;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
  }
  .pagination a:hover {
    background-color: #2980b9;
    color: white;
    box-shadow: 0 4px 12px rgba(41, 128, 185, 0.6);
    transform: translateY(-3px);
  }
  .pagination a[style*="font-weight:bold;"] {
    background-color: #2980b9;
    color: white;
    cursor: default;
    box-shadow: 0 4px 15px rgba(41, 128, 185, 0.8);
  }

  /* Animations */
  @keyframes fadeInDown {
    0% { opacity: 0; transform: translateY(-30px);}
    100% { opacity: 1; transform: translateY(0);}
  }

  @keyframes fadeIn {
    0% { opacity: 0;}
    100% { opacity: 1;}
  }

  @keyframes shimmer {
    0% {
      background-position: -400px 0;
    }
    100% {
      background-position: 400px 0;
    }
  }

  tbody tr {
    background: linear-gradient(90deg, #f9faff 25%, #e0f0ff 50%, #f9faff 75%);
    background-size: 400% 100%;
  }
</style>
</head>
<body>

<h2>Welcome, Admin</h2>

<div class="top-bar">
  <form class="search-box" method="GET" action="dashboard.php" style="margin-bottom: 0;">
    <input type="text" name="search" placeholder="Search by name, type..." 
           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
    <input type="submit" value="Search" />
  </form>
 <div>
  <a href="add.php" title="Add New Item">➕ Add New Item</a>
  <a href="users_details.php" title="View Users">👤 User Details</a>
  <a href="logout.php" title="Logout">🔒 Logout</a>
</div>

</div>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Thumbnail</th>
      <th>Name</th>
      <th>Type</th>
      <th>Size</th>
      <th>Gender</th>
      <th>Price</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="thumb" class="thumbnail"></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['type']) ?></td>
      <td><?= htmlspecialchars($row['size']) ?></td>
      <td><?= htmlspecialchars($row['gender']) ?></td>
      <td>₹<?= number_format($row['price'], 2) ?></td>
      <td>
        <a href="edit.php?id=<?= $row['id'] ?>" class="action-btn edit-btn" title="Edit Item ✏️">✏️ Edit</a>
        <a href="delete.php?id=<?= $row['id'] ?>" class="action-btn delete-btn" title="Delete Item 🗑️" onclick="return confirm('Are you sure you want to delete this item?')">🗑️ Delete</a>
      </td>
    </tr>
    <?php } ?>
  </tbody>
</table>

<div class="pagination">
  <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"<?= $i == $page ? ' style="font-weight:bold;"' : '' ?>><?= $i ?></a>
  <?php } ?>
</div>

</body>
</html>
