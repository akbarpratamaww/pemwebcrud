<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$database = "cahayalaundry";

// Koneksi ke database
$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = mysqli_real_escape_string($conn, $_POST['username']);
    $input_password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM admin WHERE username = '$input_username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && $input_password === $user['password']) { 
        header("Location: ../admin/admin.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Cahaya Laundry</title>
    
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet">

    <!-- Style -->
    <link rel="stylesheet" href="../css/loginadmin.css">
</head>
<body>
    <div class="login-container">
        <h2>Login Admin - Cahaya Laundry</h2>
        <?php
        if (isset($error)) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>
        <form action="loginadmin.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>