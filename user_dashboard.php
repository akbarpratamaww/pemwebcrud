<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php';

// Ambil data user dari session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$name = $_SESSION['name'] ?? '';
$phone = $_SESSION['phone'] ?? '';
$address = $_SESSION['address'] ?? '';

// Inisialisasi pesan
$success_message = '';
$error_message = '';
$show_receipt = false;
$receipt_data = [];

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
            // Ambil data layanan untuk struk
            $stmt = $pdo->prepare("SELECT nama_layanan, harga_layanan, satuan FROM layanan WHERE id_layanan = ?");
            $stmt->execute([$id_layanan]);
            $selected_layanan = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($selected_layanan) {
                $stmt = $pdo->prepare("INSERT INTO pelanggan (nama_pelanggan, no_telepon, alamat_pelanggan, tanggal_masuk, id_layanan, berat_unit, catatan, status, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pesanan Online User', ?)");
                if ($stmt->execute([$nama_pelanggan, $no_telepon, $alamat_pelanggan, $tanggal_masuk, $id_layanan, $berat_unit, $catatan, $user_id])) {
                    $success_message = "Pesanan berhasil ditambahkan!";
                    $show_receipt = true;

                    // Siapkan data untuk struk
                    $total_harga = $berat_unit * $selected_layanan['harga_layanan'];
                    $receipt_data = [
                        'nama_pelanggan' => $nama_pelanggan,
                        'no_telepon' => $no_telepon,
                        'alamat_pelanggan' => $alamat_pelanggan,
                        'tanggal_masuk' => $tanggal_masuk,
                        'nama_layanan' => $selected_layanan['nama_layanan'],
                        'berat_unit' => number_format($berat_unit, 2, ',', '.') . ' ' . $selected_layanan['satuan'],
                        'total_harga' => number_format($total_harga, 0, ',', '.'),
                        'status' => 'Pesanan Online User',
                        'catatan' => $catatan ?: '-'
                    ];
                } else {
                    $error_message = "Gagal menambahkan pesanan.";
                }
            } else {
                $error_message = "Layanan tidak ditemukan.";
            }
        } catch (PDOException $e) {
            $error_message = "Gagal menambahkan pesanan: " . $e->getMessage();
        }
    }
}

// Pagination dan Search
$items_per_page = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    // Query untuk menghitung total pesanan
    $count_query = "SELECT COUNT(*) 
                    FROM pelanggan p 
                    JOIN layanan l ON p.id_layanan = l.id_layanan 
                    JOIN users u ON p.user_id = u.id 
                    WHERE p.user_id = :user_id";
    $params = [':user_id' => $user_id];
    
    if (!empty($search)) {
        $count_query .= " AND (p.nama_pelanggan LIKE :search OR l.nama_layanan LIKE :search OR p.status LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    $stmt = $pdo->prepare($count_query);
    $stmt->execute($params);
    $total_items = $stmt->fetchColumn();
    $total_pages = ceil($total_items / $items_per_page);

    // Query untuk mengambil data pesanan
    $query = "SELECT p.*, l.nama_layanan, l.harga_layanan, l.satuan, u.name AS user_name 
              FROM pelanggan p 
              JOIN layanan l ON p.id_layanan = l.id_layanan 
              JOIN users u ON p.user_id = u.id 
              WHERE p.user_id = :user_id";
    
    if (!empty($search)) {
        $query .= " AND (p.nama_pelanggan LIKE :search OR l.nama_layanan LIKE :search OR p.status LIKE :search)";
    }
    
    $query .= " ORDER BY p.tanggal_masuk DESC LIMIT :offset, :items_per_page";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':items_per_page', $items_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $pesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Gagal mengambil riwayat pesanan: " . $e->getMessage();
    $pesanan = [];
}

// Basic PHP configuration
$company_name = "Cahaya Laundry";
$contact_email = "info@cahayalaundry.com";
$contact_phone = "+62 123 456 7890";
$contact_address = "Jl. Sudirman No. 123, Jakarta, Indonesia";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $company_name; ?> - Jasa Laundry Profesional</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            line-height: 1.6;
            color: #333;
            background: #FFFFFF;
        }

        /* Header */
        header {
            background: #FFFFFF;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
        }

        .nav-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 400;
        }

        .nav-links a:hover {
            color: #81C784;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1582738411706-bfc8e691d1c2');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #FFFFFF;
        }

        .hero-content h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .hero-content p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #81C784;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #A5D6A7;
        }

        /* Services Section */
        .services {
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            background: #FFFFFF;
        }

        .services h2 {
            font-size: 36px;
            margin-bottom: 40px;
            color: #2c3e50;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .service-item {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #81C784;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-item:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .service-item h3 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .service-item p {
            font-size: 16px;
            color: #666;
        }

        /* Order Section */
        .order-section {
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
            background: #FFFFFF;
        }

        .form-card, .history-card, .receipt-card {
            background: #FFFFFF;
            border: 2px solid #81C784;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .receipt-card {
            display: none;
        }

        .order-section h2, .order-section h3 {
            font-family: 'Poppins', sans-serif;
            color: #81C784;
        }

        .form-label {
            color: #2c3e50;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-control, .form-select {
            background: #f9f9f9;
            border: none;
            border-radius: 5px;
            color: #2c3e50;
            padding: 10px;
            font-size: 1rem;
        }

        .form-control:focus, .form-select:focus {
            background: #f0f0f0;
            box-shadow: 0 0 10px rgba(129, 199, 132, 0.5);
            outline: none;
        }

        .btn-primary {
            background-color: #81C784;
            border: none;
            padding: 12px;
            width: 100%;
            color: #FFFFFF;
            font-size: 1.1rem;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-primary:hover {
            background-color: #A5D6A7;
            transform: translateY(-2px);
        }

        .btn-back {
            background-color: #2c3e50;
            border: none;
            padding: 10px 20px;
            color: #FFFFFF;
            font-size: 1rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #34495e;
        }

        .table {
            background: #FFFFFF;
            color: #2c3e50;
        }

        .table th, .table td {
            border-color: #f0f0f0;
        }

        .alert {
            color: #2c3e50;
            background: #f9f9f9;
            border-color: #81C784;
        }

        .alert-danger {
            color: #D32F2F;
            background: #FFEBEE;
            border-color: #EF5350;
        }

        /* Search and Pagination Styles */
        .search-form {
            margin-bottom: 20px;
        }

        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .pagination .page-link {
            color: #2c3e50;
            border-radius: 5px;
            margin: 0 5px;
        }

        .pagination .page-link:hover {
            background-color: #81C784;
            color: #FFFFFF;
        }

        .pagination .active .page-link {
            background-color: #81C784;
            border-color: #81C784;
            color: #FFFFFF;
        }

        /* About Section */
        .about {
            background: #f4f4f4;
            padding: 60px 20px;
            text-align: center;
        }

        .about h2 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .about p {
            max-width: 800px;
            margin: 0 auto;
            font-size: 16px;
            color: #666;
        }

        /* Contact Section */
        .contact {
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            background: #FFFFFF;
        }

        .contact h2 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .contact p {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: #FFFFFF;
            text-align: center;
            padding: 20px;
        }

        footer p {
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 32px;
            }

            .hero-content p {
                font-size: 16px;
            }

            .nav-links a {
                margin-left: 10px;
                font-size: 14px;
            }

            .form-card, .history-card, .receipt-card {
                padding: 20px;
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="navbar">
            <div class="logo"><?php echo $company_name; ?></div>
            <div class="nav-links">
                <a href="#home">Beranda</a>
                <a href="#services">Layanan</a>
                <a href="#order">Pesanan</a>
                <a href="#about">Tentang Kami</a>
                <a href="#contact">Kontak</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Selamat Datang di <?php echo $company_name; ?></h1>
            <p>Jasa laundry profesional dengan pelayanan cepat, bersih, dan terjangkau.</p>
            <a href="#order" class="btn">Buat Pesanan</a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <h2>Layanan Kami</h2>
        <div class="service-grid">
            <div class="service-item">
                <h3>Cuci Basah</h3>
                <p>Rp3.500/kg</p>
            </div>
            <div class="service-item">
                <h3>Cuci Lipat</h3>
                <p>Rp4.500/kg</p>
            </div>
            <div class="service-item">
                <h3>Cuci Setrika</h3>
                <p>Rp7.000/kg</p>
            </div>
            <div class="service-item">
                <h3>Setrika</h3>
                <p>Rp4.500/kg</p>
            </div>
            <div class="service-item">
                <h3>Cuci Lipat Express</h3>
                <p>Rp8.000/kg</p>
            </div>
            <div class="service-item">
                <h3>Cuci Setrika Express</h3>
                <p>Rp12.000/kg</p>
            </div>
            <div class="service-item">
                <h3>Bedcover</h3>
                <p>Rp20.000/unit</p>
            </div>
            <div class="service-item">
                <h3>Seprei</h3>
                <p>Rp10.000/unit</p>
            </div>
            <div class="service-item">
                <h3>Selimut</h3>
                <p>Rp10.000/unit</p>
            </div>
            <div class="service-item">
                <h3>Sepatu</h3>
                <p>Rp20.000/pasang</p>
            </div>
            <div class="service-item">
                <h3>Tas</h3>
                <p>Rp15.000/unit</p>
            </div>
            <div class="service-item">
                <h3>Boneka</h3>
                <p>Rp10.000/unit</p>
            </div>
            <div class="service-item">
                <h3>Wenter</h3>
                <p>Rp35.000/unit</p>
            </div>
        </div>
    </section>

    <!-- Order Section -->
    <section class="order-section" id="order">
        <div class="container">
            <?php if ($success_message && !$show_receipt): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Form Pesanan -->
            <div class="form-card" id="bookingFormCard" style="<?php echo $show_receipt ? 'display: none;' : ''; ?>">
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

            <!-- Struk Pemesanan -->
            <?php if ($show_receipt): ?>
                <div class="receipt-card" id="receiptCard" style="display: block;">
                    <h2 class="mb-4">Struk Pemesanan</h2>
                    <div class="mb-3">
                        <strong>Nama Pelanggan:</strong> <span id="receiptName"><?php echo htmlspecialchars($receipt_data['nama_pelanggan']); ?></span>
                    </div>
                    <div class="mb-3">
                        <strong>No Telepon:</strong> <span id="receiptPhone"><?php echo htmlspecialchars($receipt_data['no_telepon']); ?></span>
                    </div>
                    <div class="mb-3">
                        <strong>Alamat:</strong> <span id="receiptAddress"><?php echo htmlspecialchars($receipt_data['alamat_pelanggan']); ?></span>
                    </div>
                    <div class="mb-3">
                        <strong>Tanggal Masuk:</strong> <span id="receiptDate"><?php echo htmlspecialchars($receipt_data['tanggal_masuk']); ?></span>
                    </div>
                    <div class="mb-3">
                        <strong>Jenis Layanan:</strong> <span id="receiptService"><?php echo htmlspecialchars($receipt_data['nama_layanan']); ?></span>
                    </div>
                    <div class="mb-3">
                        <strong>Berat/Unit:</strong> <span id="receiptWeight"><?php echo htmlspecialchars($receipt_data['berat_unit']); ?></span>
                    </div>
                    <div class="mb-3">
                        <strong>Total Harga (Rp):</strong> <span id="receiptTotal"><?php echo htmlspecialchars($receipt_data['total_harga']); ?></span>
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong> <span id="receiptStatus"><?php echo htmlspecialchars($receipt_data['status']); ?></span>
                    </div>
                    <div class="mb-3">
                        <strong>Catatan:</strong> <span id="receiptNotes"><?php echo htmlspecialchars($receipt_data['catatan']); ?></span>
                    </div>
                    <button class="btn btn-back" onclick="showForm()">Kembali ke Form</button>
                </div>
            <?php endif; ?>

            <!-- Riwayat Pesanan -->
            <div class="history-card">
                <h2 class="mb-4">Riwayat Pesanan</h2>
                <!-- Search Form -->
                <form method="GET" class="search-form">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="search" placeholder="Cari nama, layanan, atau status..." value="<?php echo htmlspecialchars($search); ?>">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </form>
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
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Pagination">
                        <ul class="pagination">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Sebelumnya</a>
                                </li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Selanjutnya</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <h2>Tentang Kami</h2>
        <p><?php echo $company_name; ?> adalah penyedia jasa laundry profesional yang berdedikasi untuk memberikan pelayanan terbaik bagi pelanggan. Dengan peralatan modern dan tim berpengalaman, kami memastikan pakaian Anda bersih, wangi, dan terawat.</p>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <h2>Hubungi Kami</h2>
        <p>Email: <?php echo $contact_email; ?></p>
        <p>Telepon: <?php echo $contact_phone; ?></p>
        <p>Alamat: <?php echo $contact_address; ?></p>
    </section>

    <!-- Footer -->
    <footer>
        <p>© <?php echo date("Y"); ?> <?php echo $company_name; ?>. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showForm() {
            document.getElementById('bookingFormCard').style.display = 'block';
            document.getElementById('receiptCard').style.display = 'none';
        }
    </script>
</body>
</html>