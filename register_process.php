<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db_connect.php';

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Validasi input
    if (empty($username) || empty($password) || empty($name) || empty($phone) || empty($address)) {
        header("Location: register.php?error=Semua kolom wajib diisi!");
        exit();
    }

    try {
        // Cek apakah username sudah ada
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            header("Location: register.php?error=Username sudah digunakan!");
            exit();
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Simpan data user ke database (mirip dengan cara admin)
        $stmt = $pdo->prepare("INSERT INTO users (username, password, name, phone, address) VALUES (?, ?, ?, ?, ?)");
        $params = [$username, $hashed_password, $name, $phone, $address];

        if ($stmt->execute($params)) {
            // Ambil ID user yang baru dibuat
            $user_id = $pdo->lastInsertId();

            // Set session dan arahkan ke user_dashboard.php
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header("Location: user_dashboard.php");
            exit();
        } else {
            header("Location: register.php?error=Gagal mendaftar, silakan coba lagi!");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: register.php?error=Terjadi kesalahan: " . htmlspecialchars($e->getMessage()));
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>