<?php
$conn = mysqli_connect("localhost", "root", "", "clothes_db", 3306); // Replace 3306 if using a different port

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
