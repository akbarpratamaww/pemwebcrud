<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db_connect.php';

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    try {
        if ($role === 'admin') {
            // Validasi admin (contoh: username 'admin', password 'admin123')
            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['admin_logged_in'] = true;
                header("Location: admin.php");
                exit();
            } else {
                header("Location: index.php?error=invalid_admin&message=Tidak bisa login");
                exit();
            }
        } else { // User
            // Validasi user
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: user_dashboard.php");
                exit();
            } else {
                header("Location: index.php?error=invalid_user&message=Tidak bisa login");
                exit();
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>