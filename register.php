<?php
session_start();

// Cek apakah user sudah login
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit();
} elseif (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: user_dashboard.php");
    exit();
}

// Proses pesan error dari register_process.php
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Cahaya Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/register.css">

    <style>
        .error-message {
            display: <?php echo $error ? 'block' : 'none'; ?>;
        }
    </style>
    
</head>
<body>
    <div class="register-container">
        <h2>Registrasi - Cahaya Laundry</h2>

        <!-- Registration Form -->
        <form id="registerForm" action="register_process.php" method="POST">
            <div class="form-group">
                <label for="username" class="form-label"><i class="fas fa-user"></i> Username</label>
                <input type="text" class="form-control" id="username" name="username" required maxlength="50">
            </div>
            <div class="form-group">
                <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <label for="name" class="form-label"><i class="fas fa-user-circle"></i> Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" required maxlength="100">
            </div>
            <div class="form-group">
                <label for="phone" class="form-label"><i class="fas fa-phone"></i> Nomor Telepon</label>
                <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10,15}" title="Masukkan nomor telepon yang valid (10-15 digit)" required>
            </div>
            <div class="form-group">
                <label for="address" class="form-label"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn-register">Daftar</button>
            <div id="error_message" class="error-message"><?php echo $error; ?></div>
        </form>

        <div class="footer-text">
            Sudah punya akun? <a href="index.php">Login di sini</a>
        </div>
    </div>

    <script>
        // Validasi sederhana (client-side)
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const username = this.querySelector('input[name="username"]').value;
            const password = this.querySelector('input[name="password"]').value;
            const name = this.querySelector('input[name="name"]').value;
            const phone = this.querySelector('input[name="phone"]').value;
            const address = this.querySelector('textarea[name="address"]').value;
            const errorDiv = this.querySelector('.error-message');

            if (username === '' || password === '' || name === '' || phone === '' || address === '') {
                e.preventDefault();
                errorDiv.textContent = 'Semua kolom wajib diisi!';
                errorDiv.style.display = 'block';
            } else if (password.length < 6) {
                e.preventDefault();
                errorDiv.textContent = 'Password minimal 6 karakter!';
                errorDiv.style.display = 'block';
            } else {
                errorDiv.style.display = 'none';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>