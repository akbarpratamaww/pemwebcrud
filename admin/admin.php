<?php
session_start();

// Cek apa admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../admin/loginadmin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cahaya Laundry</title>

    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet">

    <!-- Style -->
    <link rel="stylesheet" href="../css/admin.css"> 
</head>
<body>
    <div class="admin-dashboard">
        <h2>Selamat Datang, Admin!</h2>
        <p>Ini adalah dashboard admin untuk Cahaya Laundry.</p>
        <a href="../admin/logout.php">Logout</a>
    </div>
</body>
</html>