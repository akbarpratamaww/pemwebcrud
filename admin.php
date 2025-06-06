<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php';

// Inisialisasi pesan
$success_message = '';
$error_message = '';

// **Dashboard Data**
$total_orders = 0;
$total_revenue = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total_orders, SUM(p.berat_unit * l.harga_layanan) as total_revenue 
                         FROM pelanggan p 
                         JOIN layanan l ON p.id_layanan = l.id_layanan");
    $dashboard_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_orders = $dashboard_data['total_orders'];
    $total_revenue = $dashboard_data['total_revenue'] ?: 0;
} catch (PDOException $e) {
    $error_message = "Gagal mengambil data dashboard: " . $e->getMessage();
}

// **Kelola Layanan**
// Tambah Layanan
if (isset($_POST['add_layanan'])) {
    $nama_layanan = trim($_POST['nama_layanan']);
    $harga_layanan = (int)$_POST['harga_layanan'];
    $satuan = $_POST['satuan'];

    if (empty($nama_layanan) || $harga_layanan <= 0 || empty($satuan)) {
        $error_message = "Nama layanan, harga, dan satuan harus valid.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO layanan (nama_layanan, harga_layanan, satuan) VALUES (?, ?, ?)");
            if ($stmt->execute([$nama_layanan, $harga_layanan, $satuan])) {
                $success_message = "Layanan berhasil ditambahkan!";
            } else {
                $error_message = "Gagal menambahkan layanan.";
            }
        } catch (PDOException $e) {
            $error_message = "Gagal menambahkan layanan: " . $e->getMessage();
        }
    }
}

// Edit Layanan
if (isset($_POST['edit_layanan'])) {
    $id_layanan = (int)$_POST['id_layanan'];
    $nama_layanan = trim($_POST['nama_layanan']);
    $harga_layanan = (int)$_POST['harga_layanan'];
    $satuan = $_POST['satuan'];

    if (empty($nama_layanan) || $harga_layanan <= 0 || empty($satuan)) {
        $error_message = "Nama layanan, harga, dan satuan harus valid.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE layanan SET nama_layanan = ?, harga_layanan = ?, satuan = ? WHERE id_layanan = ?");
            if ($stmt->execute([$nama_layanan, $harga_layanan, $satuan, $id_layanan])) {
                $success_message = "Layanan berhasil diperbarui!";
            } else {
                $error_message = "Gagal memperbarui layanan.";
            }
        } catch (PDOException $e) {
            $error_message = "Gagal memperbarui layanan: " . $e->getMessage();
        }
    }
}

// Hapus Layanan
if (isset($_GET['delete_layanan'])) {
    $id_layanan = (int)$_GET['delete_layanan'];
    try {
        $stmt = $pdo->prepare("DELETE FROM layanan WHERE id_layanan = ?");
        if ($stmt->execute([$id_layanan])) {
            $success_message = "Layanan berhasil dihapus!";
        } else {
            $error_message = "Gagal menghapus layanan.";
        }
    } catch (PDOException $e) {
        $error_message = "Gagal menghapus layanan: " . $e->getMessage();
    }
}

// **Kelola Pesanan**
// Tambah Pesanan
if (isset($_POST['add_pesanan'])) {
    $nama_pelanggan = trim($_POST['nama_pelanggan']);
    $no_telepon = trim($_POST['no_telepon']);
    $alamat_pelanggan = trim($_POST['alamat_pelanggan']);
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $id_layanan = (int)$_POST['id_layanan'];
    $berat_unit = (float)$_POST['berat_unit'];
    $catatan = !empty($_POST['catatan']) ? trim($_POST['catatan']) : null;
    $status = 'Admin'; // Default status untuk pesanan dari admin

    // Ambil user_id untuk admin (user dummy)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'admin_dummy'");
    $stmt->execute();
    $admin_user = $stmt->fetch(PDO::FETCH_ASSOC);
    $admin_user_id = $admin_user ? $admin_user['id'] : 1; // Fallback ke ID 1 jika admin_dummy tidak ditemukan

    if (empty($nama_pelanggan) || empty($no_telepon) || empty($alamat_pelanggan) || empty($tanggal_masuk) || $id_layanan <= 0 || $berat_unit <= 0) {
        $error_message = "Semua kolom wajib diisi dengan data yang valid.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO pelanggan (nama_pelanggan, no_telepon, alamat_pelanggan, tanggal_masuk, id_layanan, berat_unit, catatan, status, user_id, is_taken) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
            if ($stmt->execute([$nama_pelanggan, $no_telepon, $alamat_pelanggan, $tanggal_masuk, $id_layanan, $berat_unit, $catatan, $status, $admin_user_id])) {
                $success_message = "Pesanan berhasil ditambahkan!";
            } else {
                $error_message = "Gagal menambahkan pesanan.";
            }
        } catch (PDOException $e) {
            $error_message = "Gagal menambahkan pesanan: " . $e->getMessage();
        }
    }
}

// Edit Pesanan
if (isset($_POST['edit_pesanan'])) {
    $id_pelanggan = (int)$_POST['id_pelanggan'];
    $nama_pelanggan = trim($_POST['nama_pelanggan']);
    $no_telepon = trim($_POST['no_telepon']);
    $alamat_pelanggan = trim($_POST['alamat_pelanggan']);
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $id_layanan = (int)$_POST['id_layanan'];
    $berat_unit = (float)$_POST['berat_unit'];
    $catatan = !empty($_POST['catatan']) ? trim($_POST['catatan']) : null;
    $status = $_POST['status']; // Admin bisa mengubah status

    if (empty($nama_pelanggan) || empty($no_telepon) || empty($alamat_pelanggan) || empty($tanggal_masuk) || $id_layanan <= 0 || $berat_unit <= 0) {
        $error_message = "Semua kolom wajib diisi dengan data yang valid.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE pelanggan SET nama_pelanggan = ?, no_telepon = ?, alamat_pelanggan = ?, tanggal_masuk = ?, id_layanan = ?, berat_unit = ?, catatan = ?, status = ? WHERE id_pelanggan = ?");
            if ($stmt->execute([$nama_pelanggan, $no_telepon, $alamat_pelanggan, $tanggal_masuk, $id_layanan, $berat_unit, $catatan, $status, $id_pelanggan])) {
                $success_message = "Pesanan berhasil diperbarui!";
            } else {
                $error_message = "Gagal memperbarui pesanan.";
            }
        } catch (PDOException $e) {
            $error_message = "Gagal memperbarui pesanan: " . $e->getMessage();
        }
    }
}

// Hapus Pesanan
if (isset($_GET['delete_pesanan'])) {
    $id_pelanggan = (int)$_GET['delete_pesanan'];
    try {
        $stmt = $pdo->prepare("DELETE FROM pelanggan WHERE id_pelanggan = ?");
        if ($stmt->execute([$id_pelanggan])) {
            $success_message = "Pesanan berhasil dihapus!";
        } else {
            $error_message = "Gagal menghapus pesanan.";
        }
    } catch (PDOException $e) {
        $error_message = "Gagal menghapus pesanan: " . $e->getMessage();
    }
}

// Tandai Pesanan sebagai Diambil
if (isset($_GET['take_pesanan'])) {
    $id_pelanggan = (int)$_GET['take_pesanan'];
    try {
        $stmt = $pdo->prepare("UPDATE pelanggan SET is_taken = 1 WHERE id_pelanggan = ?");
        if ($stmt->execute([$id_pelanggan])) {
            $success_message = "Pesanan berhasil ditandai sebagai diambil!";
        } else {
            $error_message = "Gagal menandai pesanan sebagai diambil.";
        }
    } catch (PDOException $e) {
        $error_message = "Gagal menandai pesanan sebagai diambil: " . $e->getMessage();
    }
}

// Ambil data layanan untuk dropdown
$layanan = [];
try {
    $layanan = $pdo->query("SELECT * FROM layanan")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Gagal mengambil data layanan: " . $e->getMessage();
}

// Pagination dan Pencarian untuk Daftar Pesanan
$items_per_page = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$pelanggan = [];
try {
    // Query untuk menghitung total pesanan
    $count_query = "SELECT COUNT(*) 
                    FROM pelanggan p 
                    JOIN layanan l ON p.id_layanan = l.id_layanan 
                    JOIN users u ON p.user_id = u.id";
    $params = [];
    
    if (!empty($search)) {
        $count_query .= " WHERE p.nama_pelanggan LIKE :search OR p.no_telepon LIKE :search OR p.status LIKE :search";
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
              JOIN users u ON p.user_id = u.id";
    
    if (!empty($search)) {
        $query .= " WHERE p.nama_pelanggan LIKE :search OR p.no_telepon LIKE :search OR p.status LIKE :search";
    }
    
    $query .= " ORDER BY p.tanggal_masuk DESC LIMIT :offset, :items_per_page";
    
    $stmt = $pdo->prepare($query);
    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':items_per_page', $items_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $pelanggan = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Hitung tanggal selesai untuk setiap pesanan
    foreach ($pelanggan as &$p) {
        $tanggal_masuk = new DateTime($p['tanggal_masuk']);
        $is_express = stripos($p['nama_layanan'], 'express') !== false;
        $days_to_add = $is_express ? 1 : 2;
        $tanggal_masuk->modify("+$days_to_add days");
        $p['tanggal_selesai'] = $tanggal_masuk->format('Y-m-d');
    }
    unset($p); // Hapus referensi untuk keamanan
} catch (PDOException $e) {
    $error_message = "Gagal mengambil data pesanan: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Cahaya Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/admin.css">
    <style>
        .dashboard-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .dashboard-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            .dashboard-card {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#" style="color: #388E3C;">Cahaya Laundry</a>
            <div>
                <a href="#dashboard" class="btn btn-primary me-2">Dashboard</a>
                <a href="#layanan" class="btn btn-primary me-2">Layanan</a>
                <a href="#pesanan" class="btn btn-primary me-2">Pesanan</a>
                <a href="#daftar-pesanan" class="btn btn-primary me-2">Daftar Pesanan</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Dashboard -->
        <div id="dashboard">
            <h2 class="mb-4">Dashboard</h2>
            <div class="dashboard-container">
                <div class="dashboard-card" style="flex: 1; margin-right: 15px;">
                    <h4>Total Pesanan</h4>
                    <h3><?php echo number_format($total_orders, 0, ',', '.'); ?></h3>
                </div>
                <div class="dashboard-card" style="flex: 1;">
                    <h4>Total Pendapatan (Rp)</h4>
                    <h3><?php echo number_format($total_revenue, 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>

        <!-- Kelola Layanan -->
        <div id="layanan">
            <h2 class="mb-4">Kelola Layanan</h2>
            <div class="form-card mb-5">
                <h3>Tambah Layanan</h3>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control" name="nama_layanan" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" class="form-control" name="harga_layanan" min="1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Satuan</label>
                            <select class="form-select" name="satuan" required>
                                <option value="kg">kg</option>
                                <option value="unit">unit</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="add_layanan" class="btn btn-primary">Tambah</button>
                </form>
            </div>

            <!-- Daftar Layanan -->
            <h3>Daftar Layanan</h3>
            <div class="form-card mb-5">
                <div class="table-responsive">
                    <table class="table rounded-table">
                        <thead>
                            <tr>
                                <th class="col-id">ID</th>
                                <th class="col-nama">Nama Layanan</th>
                                <th class="col-harga(Process finished with exit code 0
)">Harga (Rp)</th>
                                <th class="col-satuan">Satuan</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($layanan as $l): ?>
                                <tr>
                                    <td><?php echo $l['id_layanan']; ?></td>
                                    <td><?php echo htmlspecialchars($l['nama_layanan']); ?></td>
                                    <td><?php echo number_format($l['harga_layanan'], 0, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($l['satuan']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editLayananModal<?php echo $l['id_layanan']; ?>">Edit</button>
                                        <a href="admin.php?delete_layanan=<?php echo $l['id_layanan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus layanan ini?')">Hapus</a>
                                    </td>
                                </tr>
                                <!-- Modal Edit Layanan -->
                                <div class="modal fade" id="editLayananModal<?php echo $l['id_layanan']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Layanan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST">
                                                    <input type="hidden" name="id_layanan" value="<?php echo $l['id_layanan']; ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Layanan</label>
                                                        <input type="text" class="form-control" name="nama_layanan" value="<?php echo htmlspecialchars($l['nama_layanan']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Harga (Rp)</label>
                                                        <input type="number" class="form-control" name="harga_layanan" value="<?php echo $l['harga_layanan']; ?>" min="1" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Satuan</label>
                                                        <select class="form-select" name="satuan" required>
                                                            <option value="kg" <?php echo $l['satuan'] == 'kg' ? 'selected' : ''; ?>>kg</option>
                                                            <option value="unit" <?php echo $l['satuan'] == 'unit' ? 'selected' : ''; ?>>unit</option>
                                                        </select>
                                                    </div>
                                                    <button type="submit" name="edit_layanan" class="btn btn-primary">Simpan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kelola Pesanan -->
        <div id="pesanan">
            <h2 class="mb-4">Kelola Pesanan</h2>
            <div class="form-card mb-5">
                <h3>Tambah Pesanan</h3>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" name="nama_pelanggan" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">No Telepon</label>
                            <input type="text" class="form-control" name="no_telepon" pattern="[0-9]{10,15}" title="Masukkan nomor telepon yang valid (10-15 digit)" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat_pelanggan" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" name="tanggal_masuk" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jenis Layanan</label>
                            <select class="form-select" name="id_layanan" required>
                                <?php foreach ($layanan as $l): ?>
                                    <option value="<?php echo $l['id_layanan']; ?>">
                                        <?php echo htmlspecialchars($l['nama_layanan']); ?> - Rp<?php echo number_format($l['harga_layanan'], 0, ',', '.'); ?> per <?php echo htmlspecialchars($l['satuan']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Berat/Unit</label>
                            <input type="number" step="0.01" class="form-control" name="berat_unit" min="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan"></textarea>
                    </div>
                    <button type="submit" name="add_pesanan" class="btn btn-primary">Tambah</button>
                </form>
            </div>

            <!-- Daftar Pesanan -->
            <div id="daftar-pesanan">
                <h3>Daftar Pesanan</h3>
                <div class="form-card">
                    <!-- Form Pencarian -->
                    <form method="GET" class="search-form">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="search" placeholder="Cari nama, telepon, atau status..." value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table rounded-table">
                            <thead>
                                <tr>
                                    <th class="col-id">ID</th>
                                    <th class="col-nama">Nama</th>
                                    <th class="col-telepon">No Telepon</th>
                                    <th class="col-alamat">Alamat</th>
                                    <th class="col-tanggal">Tanggal Masuk</th>
                                    <th class="col-selesai">Tanggal Selesai</th>
                                    <th class="col-layanan">Layanan</th>
                                    <th class="col-berat">Berat/Unit</th>
                                    <th class="col-harga">Total Harga (Rp)</th>
                                    <th class="col-status">Status</th>
                                    <th class="col-pemesan">Pemesan</th>
                                    <th class="col-catatan">Catatan</th>
                                    <th class="col-pengambilan">Status Pengambilan</th>
                                    <th class="col-aksi">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pelanggan)): ?>
                                    <tr>
                                        <td colspan="14" class="text-center">Belum ada data pesanan.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($pelanggan as $p): ?>
                                        <tr>
                                            <td><?php echo $p['id_pelanggan']; ?></td>
                                            <td><?php echo htmlspecialchars($p['nama_pelanggan']); ?></td>
                                            <td><?php echo htmlspecialchars($p['no_telepon']); ?></td>
                                            <td><?php echo htmlspecialchars($p['alamat_pelanggan']); ?></td>
                                            <td><?php echo $p['tanggal_masuk']; ?></td>
                                            <td><?php echo $p['tanggal_selesai']; ?></td>
                                            <td><?php echo htmlspecialchars($p['nama_layanan']); ?></td>
                                            <td><?php echo number_format($p['berat_unit'], 2, ',', '.') . ' ' . htmlspecialchars($p['satuan']); ?></td>
                                            <td><?php echo number_format($p['berat_unit'] * $p['harga_layanan'], 0, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($p['status']); ?></td>
                                            <td><?php echo htmlspecialchars($p['user_name']); ?></td>
                                            <td><?php echo htmlspecialchars($p['catatan'] ?: '-'); ?></td>
                                            <td>
                                                <?php if ($p['is_taken']): ?>
                                                    <span class="badge bg-success">Sudah Diambil</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark me-1">Belum Diambil</span>
                                                    <a href="admin.php?take_pesanan=<?php echo $p['id_pelanggan']; ?>&page=<?php echo $page; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-success btn-sm" style="margin-top: 5px;"  onclick="return confirm('Tandai pesanan ini sebagai diambil?')">Tandai Diambil</a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editPesananModal<?php echo $p['id_pelanggan']; ?>">Edit</button>
                                                <a href="admin.php?delete_pesanan=<?php echo $p['id_pelanggan']; ?>&page=<?php echo $page; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
        </div>

        <!-- Modal Edit Pesanan -->
        <?php foreach ($pelanggan as $p): ?>
            <div class="modal fade" id="editPesananModal<?php echo $p['id_pelanggan']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Pesanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST">
                                <input type="hidden" name="id_pelanggan" value="<?php echo $p['id_pelanggan']; ?>">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nama Pelanggan</label>
                                        <input type="text" class="form-control" name="nama_pelanggan" value="<?php echo htmlspecialchars($p['nama_pelanggan']); ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">No Telepon</label>
                                        <input type="text" class="form-control" name="no_telepon" pattern="[0-9]{10,15}" title="Masukkan nomor telepon yang valid (10-15 digit)" value="<?php echo htmlspecialchars($p['no_telepon']); ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea class="form-control" name="alamat_pelanggan" required><?php echo htmlspecialchars($p['alamat_pelanggan']); ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tanggal Masuk</label>
                                        <input type="date" class="form-control" name="tanggal_masuk" value="<?php echo $p['tanggal_masuk']; ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Jenis Layanan</label>
                                        <select class="form-select" name="id_layanan" required>
                                            <?php foreach ($layanan as $l): ?>
                                                <option value="<?php echo $l['id_layanan']; ?>" <?php echo $l['id_layanan'] == $p['id_layanan'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($l['nama_layanan']); ?> - Rp<?php echo number_format($l['harga_layanan'], 0, ',', '.'); ?> per <?php echo htmlspecialchars($l['satuan']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Berat/Unit</label>
                                        <input type="number" step="0.01" class="form-control" name="berat_unit" value="<?php echo $p['berat_unit']; ?>" min="0.01" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status" required>
                                            <option value="Admin" <?php echo $p['status'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                                            <option value="Pesanan Online User" <?php echo $p['status'] == 'Pesanan Online User' ? 'selected' : ''; ?>>Pesanan Online User</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Catatan (Opsional)</label>
                                        <textarea class="form-control" name="catatan"><?php echo htmlspecialchars($p['catatan']); ?></textarea>
                                    </div>
                                </div>
                                <button type="submit" name="edit_pesanan" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>