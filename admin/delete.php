<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Optional: Delete image file here if you save images to folder

    $deleteQuery = "DELETE FROM clothes WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting item: " . mysqli_error($conn);
    }
} else {
    echo "Invalid item ID.";
}
?>
