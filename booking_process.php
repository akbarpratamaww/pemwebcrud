<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db_connect.php';

    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $order_date = trim($_POST['order_date']);
    $notes = trim($_POST['notes']);
    $services = isset($_POST['services']) ? $_POST['services'] : [];

    // Validasi input
    if (empty($name) || empty($phone) || empty($address) || empty($order_date) || empty($services)) {
        header("Location: user_dashboard.php?error=Semua kolom wajib diisi!");
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Simpan setiap layanan sebagai order terpisah
        foreach ($services as $service) {
            $service_type = $service['type'];
            $quantity = floatval($service['quantity']);

            // Hitung total harga berdasarkan jenis layanan
            $price_per_unit = 0;
            if ($service_type === 'Cuci Basah') {
                $price_per_unit = 3500;
            } elseif ($service_type === 'Cuci Lipat') {
                $price_per_unit = 4500;
            } elseif ($service_type === 'Cuci Setrika') {
                $price_per_unit = 7000;
            } elseif ($service_type === 'Setrika') {
                $price_per_unit = 4500;
            } elseif ($service_type === 'Cuci Lipat Express') {
                $price_per_unit = 8000;
            } elseif ($service_type === 'Cuci Setrika Express') {
                $price_per_unit = 12000;
            } elseif ($service_type === 'Sprei') {
                $price_per_unit = 10000;
            } elseif ($service_type === 'Selimut') {
                $price_per_unit = 10000;
            } elseif ($service_type === 'Sepatu') {
                $price_per_unit = 20000;
            } elseif ($service_type === 'Tas') {
                $price_per_unit = 15000;
            } elseif ($service_type === 'Boneka') {
                $price_per_unit = 10000;
            } elseif ($service_type === 'Wenter') {
                $price_per_unit = 35000;
            }

            $total_price = $price_per_unit * $quantity;

            // Simpan ke tabel orders
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, service_type, quantity, total_price, order_date, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $service_type, $quantity, $total_price, $order_date, 'pending']);
        }

        // Update data user (opsional, jika ingin menyimpan data terbaru)
        $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $address, $user_id]);

        $pdo->commit();
        header("Location: user_dashboard.php?success=Pesanan berhasil disimpan!");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        header("Location: user_dashboard.php?error=Terjadi kesalahan: " . htmlspecialchars($e->getMessage()));
        exit();
    }
} else {
    header("Location: user_dashboard.php");
    exit();
}
?>