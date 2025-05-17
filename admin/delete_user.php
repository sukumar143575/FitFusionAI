<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
}

header("Location: users_details.php");
exit();
?>
