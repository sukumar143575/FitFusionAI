<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}
include 'includes/db.php';

// Defaults
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$gender = isset($_GET['gender']) ? mysqli_real_escape_string($conn, $_GET['gender']) : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Query building
$filter = "WHERE 1=1";
if ($search) {
    $filter .= " AND (username LIKE '%$search%' OR email LIKE '%$search%')";
}
if ($gender) {
    $filter .= " AND gender = '$gender'";
}
if ($from_date && $to_date) {
    $filter .= " AND DATE(created_at) BETWEEN '$from_date' AND '$to_date'";
}

$query = "SELECT * FROM users $filter ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Count for pagination
$count_query = "SELECT COUNT(*) as total FROM users $filter";
$count_result = mysqli_query($conn, $count_query);
$total_users = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_users / $limit);
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Details</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #eef2f7;
      padding: 20px;
      margin: 0;
    }

    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 20px;
    }

    .filter-form {
      background: #fff;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      margin-bottom: 20px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
    }

    .filter-form input[type="text"],
    .filter-form select,
    .filter-form input[type="date"],
    .filter-form input[type="submit"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      outline: none;
      font-size: 14px;
    }

    .filter-form input[type="submit"] {
      background-color: #2980b9;
      color: white;
      border: none;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .filter-form input[type="submit"]:hover {
      background-color: #1e6fa6;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    th, td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid #f0f0f0;
    }

    th {
      background-color: #34495e;
      color: white;
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    td a {
      text-decoration: none;
      color: #2980b9;
      font-weight: bold;
    }

    td a:hover {
      text-decoration: underline;
      color: #1e6fa6;
    }

    .pagination {
      text-align: center;
      margin-top: 25px;
    }

    .pagination a {
      margin: 4px;
      padding: 8px 14px;
      border: 1px solid #2980b9;
      color: #2980b9;
      text-decoration: none;
      border-radius: 6px;
      transition: all 0.3s ease;
    }

    .pagination a:hover {
      background-color: #2980b9;
      color: white;
    }

    .pagination a.active {
      background-color: #2980b9;
      color: white;
      font-weight: bold;
    }

    @media screen and (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      thead {
        display: none;
      }

      tr {
        margin-bottom: 15px;
        background: #fff;
        border-radius: 8px;
        padding: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      }

      td {
        position: relative;
        padding-left: 50%;
      }

      td::before {
        position: absolute;
        top: 14px;
        left: 14px;
        width: 45%;
        white-space: nowrap;
        font-weight: bold;
        color: #555;
      }

      td:nth-of-type(1)::before { content: "ID"; }
      td:nth-of-type(2)::before { content: "Name"; }
      td:nth-of-type(3)::before { content: "Email"; }
      td:nth-of-type(4)::before { content: "Gender"; }
      td:nth-of-type(5)::before { content: "Phone"; }
      td:nth-of-type(6)::before { content: "Password"; }
      td:nth-of-type(7)::before { content: "Registered At"; }
      td:nth-of-type(8)::before { content: "Actions"; }
    }
  </style>
</head>
<body>

<h2>User Details (<?= $total_users ?> Registered Users)</h2>

<form method="GET" class="filter-form">
  <input type="text" name="search" placeholder="Search name or email" value="<?= htmlspecialchars($search) ?>">
  <select name="gender">
    <option value="">All Genders</option>
    <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
    <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
    <option value="Other" <?= $gender == 'Other' ? 'selected' : '' ?>>Other</option>
  </select>
  <input type="date" name="from_date" value="<?= $from_date ?>">
  <input type="date" name="to_date" value="<?= $to_date ?>">
  <input type="submit" value="Filter">
</form>

<table>
<thead>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Gender</th>
    <th>Phone</th>
    <th>Password</th>
    <th>Registered At</th>
    <th>Actions</th>
  </tr>
</thead>
<tbody>
  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['gender']) ?></td>
    <td><?= htmlspecialchars($row['contact']) ?></td>
    <td><?= htmlspecialchars($row['password']) ?></td>
    <td><?= date("Y-m-d H:i:s", strtotime($row['created_at'])) ?></td>
    <td>
      <a href="edit_user.php?id=<?= $row['id'] ?>">Edit</a> |
      <a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
    </td>
  </tr>
  <?php } ?>
  <?php if ($total_users == 0) { ?>
  <tr><td colspan="8" style="text-align:center;">No users found.</td></tr>
  <?php } ?>
</tbody>
</table>

<div class="pagination">
  <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&gender=<?= urlencode($gender) ?>&from_date=<?= urlencode($from_date) ?>&to_date=<?= urlencode($to_date) ?>" class="<?= $i == $page ? 'active' : '' ?>">
      <?= $i ?>
    </a>
  <?php } ?>
</div>

</body>
</html>
