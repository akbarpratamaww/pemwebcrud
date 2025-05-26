<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: index.php"); // Arahkan ke halaman login jika belum login
    exit();
}

require_once 'db_connect.php';

// Ambil data user dari session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$name = $_SESSION['name'] ?? ''; // Jika ada data nama di session (sesuaikan dengan register_process.php)
$phone = $_SESSION['phone'] ?? '';
$address = $_SESSION['address'] ?? '';

// Inisialisasi pesan
$success_message = '';
$error_message = '';

// Ambil data layanan untuk dropdown
try {
    $layanan = $pdo->query("SELECT * FROM layanan")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Gagal mengambil data layanan: " . $e->getMessage();
    $layanan = [];
}

// Tambah Pesanan
if (isset($_POST['add_pesanan'])) {
    $nama_pelanggan = trim($_POST['nama_pelanggan']);
    $no_telepon = trim($_POST['no_telepon']);
    $alamat_pelanggan = trim($_POST['alamat_pelanggan']);
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $id_layanan = (int)$_POST['id_layanan'];
    $berat_unit = (float)$_POST['berat_unit'];
    $catatan = !empty($_POST['catatan']) ? trim($_POST['catatan']) : null;

    if (empty($nama_pelanggan) || empty($no_telepon) || empty($alamat_pelanggan) || empty($tanggal_masuk) || $id_layanan <= 0 || $berat_unit <= 0) {
        $error_message = "Semua kolom wajib diisi dengan data yang valid.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO pelanggan (nama_pelanggan, no_telepon, alamat_pelanggan, tanggal_masuk, id_layanan, berat_unit, catatan, status, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pesanan Online User', ?)");
            if ($stmt->execute([$nama_pelanggan, $no_telepon, $alamat_pelanggan, $tanggal_masuk, $id_layanan, $berat_unit, $catatan, $user_id])) {
                $success_message = "Pesanan berhasil ditambahkan!";
            } else {
                $error_message = "Gagal menambahkan pesanan.";
            }
        } catch (PDOException $e) {
            $error_message = "Gagal menambahkan pesanan: " . $e->getMessage();
        }
    }
}

// Ambil riwayat pesanan berdasarkan user_id
try {
    $stmt = $pdo->prepare("SELECT p.*, l.nama_layanan, l.harga_layanan, l.satuan, u.name AS user_name 
                           FROM pelanggan p 
                           JOIN layanan l ON p.id_layanan = l.id_layanan 
                           JOIN users u ON p.user_id = u.id 
                           WHERE p.user_id = ?");
    $stmt->execute([$user_id]);
    $pesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Gagal mengambil riwayat pesanan: " . $e->getMessage();
    $pesanan = [];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Laundry - Cahaya Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #E8F5E9; /* Warna latar belakang sama seperti admin.php */
            font-family: 'Open Sans', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .navbar {
            background-color: #FFFFFF; /* Latar belakang putih seperti admin.php */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Shadow sama seperti admin.php */
            margin-bottom: 30px;
        }
        .navbar-brand {
            color: #4CAF50 !important; /* Warna teks sama seperti admin.php */
            font-weight: 600;
        }
        .container {
            max-width: 1200px;
        }
        .form-card, .history-card {
            background: #FFFFFF; /* Warna latar belakang form sama seperti admin.php */
            border: 2px solid #4CAF50; /* Border hijau seperti admin.php */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow sama seperti admin.php */
            padding: 30px;
            margin-bottom: 30px;
            color: #2E7D32;
        }
        h2, h3 {
            font-family: 'Poppins', sans-serif;
            color: #4CAF50; /* Warna judul sama seperti admin.php */
        }
        .form-label {
            color: #2E7D32;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .form-control, .form-select {
            background: rgb(255, 255, 255);
            border: none;
            border-radius: 5px;
            color: #2E7D32;
            padding: 10px;
            font-size: 1rem;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 10px rgba(46, 125, 50, 0.5);
            outline: none;
        }
        .btn-primary {
            background-color: #4CAF50; /* Warna tombol sama seperti admin.php */
            border: none;
            padding: 12px;
            width: 100%;
            color: #FFFFFF;
            font-size: 1.1rem;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-primary:hover {
            background-color: #81C784; /* Warna hover sama seperti admin.php */
            border-color: #81C784;
            transform: translateY(-2px);
        }
        .table {
            background: #FFFFFF; /* Warna tabel sama seperti admin.php */
            color: #2E7D32;
        }
        .table th, .table td {
            border-color: rgba(255, 255, 255, 0.2);
        }
        .alert {
            color: #2E7D32;
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(46, 125, 50, 0.5);
        }
        .alert-danger {
            color: #D32F2F;
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(211, 47, 47, 0.5);
        }
        @media (max-width: 768px) {
            .form-card, .history-card {
                padding: 20px;
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">User - Cahaya Laundry</a>
            <div class="ms-auto d-flex align-items-center">
                <span class="navbar-text me-3" style="color: #4CAF50;">Selamat datang, <?php echo htmlspecialchars($username); ?>!</span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Form Pesanan -->
        <div class="form-card">
            <h2 class="mb-4">Buat Pesanan Laundry</h2>
            <form method="POST">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="fas fa-user"></i> Nama Pelanggan</label>
                        <input type="text" class="form-control" name="nama_pelanggan" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="fas fa-phone"></i> No Telepon</label>
                        <input type="text" class="form-control" name="no_telepon" pattern="[0-9]{10,15}" title="Masukkan nomor telepon yang valid (10-15 digit)" value="<?php echo htmlspecialchars($phone); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                        <textarea class="form-control" name="alamat_pelanggan" required><?php echo htmlspecialchars($address); ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="fas fa-calendar-alt"></i> Tanggal Masuk</label>
                        <input type="date" class="form-control" name="tanggal_masuk" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="fas fa-concierge-bell"></i> Jenis Layanan</label>
                        <select class="form-select" name="id_layanan" required>
                            <?php foreach ($layanan as $l): ?>
                                <option value="<?php echo $l['id_layanan']; ?>">
                                    <?php echo htmlspecialchars($l['nama_layanan']); ?> - Rp<?php echo number_format($l['harga_layanan'], 0, ',', '.'); ?> per <?php echo htmlspecialchars($l['satuan']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="fas fa-weight"></i> Berat/Unit</label>
                        <input type="number" step="0.01" class="form-control" name="berat_unit" min="0.01" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-sticky-note"></i> Catatan (Opsional)</label>
                    <textarea class="form-control" name="catatan"></textarea>
                </div>
                <button type="submit" name="add_pesanan" class="btn btn-primary">Buat Pesanan</button>
            </form>
        </div>

        <!-- Riwayat Pesanan -->
        <div class="history-card">
            <h2 class="mb-4">Riwayat Pesanan</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Tanggal Masuk</th>
                        <th>Layanan</th>
                        <th>Berat/Unit</th>
                        <th>Total Harga (Rp)</th>
                        <th>Status</th>
                        <th>Pemesan</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pesanan)): ?>
                        <tr>
                            <td colspan="9" class="text-center">Belum ada pesanan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pesanan as $p): ?>
                            <tr>
                                <td><?php echo $p['id_pelanggan']; ?></td>
                                <td><?php echo htmlspecialchars($p['nama_pelanggan']); ?></td>
                                <td><?php echo $p['tanggal_masuk']; ?></td>
                                <td><?php echo htmlspecialchars($p['nama_layanan']); ?></td>
                                <td><?php echo number_format($p['berat_unit'], 2, ',', '.') . ' ' . htmlspecialchars($p['satuan']); ?></td>
                                <td><?php echo number_format($p['berat_unit'] * $p['harga_layanan'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($p['status']); ?></td>
                                <td><?php echo htmlspecialchars($p['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($p['catatan'] ?: '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>