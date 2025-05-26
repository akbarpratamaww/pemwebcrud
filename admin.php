<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php';

// Inisialisasi pesan
$success_message = '';
$error_message = '';

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

// **Kelola Pelanggan**
// Tambah Pelanggan
if (isset($_POST['add_pelanggan'])) {
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
            $stmt = $pdo->prepare("INSERT INTO pelanggan (nama_pelanggan, no_telepon, alamat_pelanggan, tanggal_masuk, id_layanan, berat_unit, catatan, status, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$nama_pelanggan, $no_telepon, $alamat_pelanggan, $tanggal_masuk, $id_layanan, $berat_unit, $catatan, $status, $admin_user_id])) {
                $success_message = "Pelanggan berhasil ditambahkan!";
            } else {
                $error_message = "Gagal menambahkan pelanggan.";
            }
        } catch (PDOException $e) {
            $error_message = "Gagal menambahkan pelanggan: " . $e->getMessage();
        }
    }
}

// Edit Pelanggan
if (isset($_POST['edit_pelanggan'])) {
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
                $success_message = "Pelanggan berhasil diperbarui!";
            } else {
                $error_message = "Gagal memperbarui pelanggan.";
            }
        } catch (PDOException $e) {
            $error_message = "Gagal memperbarui pelanggan: " . $e->getMessage();
        }
    }
}

// Hapus Pelanggan
if (isset($_GET['delete_pelanggan'])) {
    $id_pelanggan = (int)$_GET['delete_pelanggan'];
    try {
        $stmt = $pdo->prepare("DELETE FROM pelanggan WHERE id_pelanggan = ?");
        if ($stmt->execute([$id_pelanggan])) {
            $success_message = "Pelanggan berhasil dihapus!";
        } else {
            $error_message = "Gagal menghapus pelanggan.";
        }
    } catch (PDOException $e) {
        $error_message = "Gagal menghapus pelanggan: " . $e->getMessage();
    }
}

// Ambil data layanan untuk dropdown
try {
    $layanan = $pdo->query("SELECT * FROM layanan")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Gagal mengambil data layanan: " . $e->getMessage();
    $layanan = [];
}

// Ambil data pelanggan untuk ditampilkan
try {
    $pelanggan = $pdo->query("SELECT p.*, l.nama_layanan, l.harga_layanan, l.satuan, u.name AS user_name 
                             FROM pelanggan p 
                             JOIN layanan l ON p.id_layanan = l.id_layanan 
                             JOIN users u ON p.user_id = u.id")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Gagal mengambil data pelanggan: " . $e->getMessage();
    $pelanggan = [];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Cahaya Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #E8F5E9;
            font-family: 'Open Sans', sans-serif;
        }
        h2, h3 {
            font-family: 'Poppins', sans-serif;
            color: #4CAF50;
        }
        .navbar {
            background-color: #FFFFFF;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        .btn-primary:hover {
            background-color: #81C784;
            border-color: #81C784;
        }
        .table {
            background-color: #FFFFFF;
        }
        .form-card {
            background-color: #FFFFFF;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        /* Perbaikan untuk tabel Daftar Pelanggan */
        .table-responsive {
            margin-bottom: 30px;
        }
        .table th, .table td {
            padding: 10px; /* Tambah padding untuk ruang lebih */
            vertical-align: middle; /* Rata tengah vertikal */
            text-align: left; /* Rata kiri untuk teks */
        }
        .table th {
            background-color: #4CAF50;
            color: white;
        }
        .table td {
            word-wrap: break-word; /* Teks panjang akan membungkus */
            max-width: 200px; /* Batasi lebar maksimum kolom */
        }
        /* Atur lebar minimum untuk kolom tertentu */
        .col-id { min-width: 50px; }
        .col-nama { min-width: 120px; }
        .col-telepon { min-width: 120px; }
        .col-alamat { min-width: 150px; }
        .col-tanggal { min-width: 120px; }
        .col-layanan { min-width: 120px; }
        .col-berat { min-width: 100px; }
        .col-harga { min-width: 120px; }
        .col-status { min-width: 120px; }
        .col-pemesan { min-width: 120px; }
        .col-catatan { min-width: 150px; }
        .col-aksi { min-width: 150px; }
        /* Sembunyikan kolom tertentu pada layar kecil */
        @media (max-width: 768px) {
            .col-alamat, .col-catatan {
                display: none; /* Sembunyikan kolom Alamat dan Catatan pada layar kecil */
            }
        }
        /* Perbaikan untuk modal */
        .modal-body .row {
            margin-bottom: 15px;
        }
        .modal-body label {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#" style="color: #4CAF50;">Admin - Cahaya Laundry</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container my-5">
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Kelola Layanan -->
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
        <div class="table-responsive">
            <table class="table table-bordered mb-5">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-nama">Nama Layanan</th>
                        <th class="col-harga">Harga (Rp)</th>
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

        <!-- Kelola Pelanggan -->
        <h2 class="mb-4">Kelola Pelanggan</h2>
        <div class="form-card mb-5">
            <h3>Tambah Pelanggan</h3>
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
                <button type="submit" name="add_pelanggan" class="btn btn-primary">Tambah</button>
            </form>
        </div>

        <!-- Daftar Pelanggan -->
        <h3>Daftar Pelanggan</h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-nama">Nama</th>
                        <th class="col-telepon">No Telepon</th>
                        <th class="col-alamat">Alamat</th>
                        <th class="col-tanggal">Tanggal Masuk</th>
                        <th class="col-layanan">Layanan</th>
                        <th class="col-berat">Berat/Unit</th>
                        <th class="col-harga">Total Harga (Rp)</th>
                        <th class="col-status">Status</th>
                        <th class="col-pemesan">Pemesan</th>
                        <th class="col-catatan">Catatan</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pelanggan as $p): ?>
                        <tr>
                            <td><?php echo $p['id_pelanggan']; ?></td>
                            <td><?php echo htmlspecialchars($p['nama_pelanggan']); ?></td>
                            <td><?php echo htmlspecialchars($p['no_telepon']); ?></td>
                            <td><?php echo htmlspecialchars($p['alamat_pelanggan']); ?></td>
                            <td><?php echo $p['tanggal_masuk']; ?></td>
                            <td><?php echo htmlspecialchars($p['nama_layanan']); ?></td>
                            <td><?php echo number_format($p['berat_unit'], 2, ',', '.') . ' ' . htmlspecialchars($p['satuan']); ?></td>
                            <td><?php echo number_format($p['berat_unit'] * $p['harga_layanan'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($p['status']); ?></td>
                            <td><?php echo htmlspecialchars($p['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($p['catatan'] ?: '-'); ?></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editPelangganModal<?php echo $p['id_pelanggan']; ?>">Edit</button>
                                <a href="admin.php?delete_pelanggan=<?php echo $p['id_pelanggan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">Hapus</a>
                            </td>
                        </tr>

                        <!-- Modal Edit Pelanggan -->
                        <div class="modal fade" id="editPelangganModal<?php echo $p['id_pelanggan']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Pelanggan</h5>
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
                                            <button type="submit" name="edit_pelanggan" class="btn btn-primary">Simpan</button>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>