<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit();
} elseif (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: user_dashboard.php");
    exit();
}

// Proses pesan error dari login_process.php
$error = isset($_GET['error']) ? $_GET['error'] : '';
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cahaya Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/index.css">

    <style>
        .error-message {
            display: <?php echo $message ? 'block' : 'none'; ?>;
        }
    </style>
    
</head>
<body>
    <div class="login-container">
        <h2>Cahaya Laundry</h2>
        <div class="d-flex justify-content-center mb-4">
            <button class="tab-button active" onclick="showTab('user')">User</button>
            <button class="tab-button" onclick="showTab('admin')">Admin</button>
        </div>

        <!-- User Login Form -->
        <form id="userForm" class="login-form" action="login_process.php" method="POST">
            <input type="hidden" name="role" value="user">
            <div class="form-group">
                <label for="username_user" class="form-label"><i class="fas fa-user"></i> Username</label>
                <input type="text" class="form-control" id="username_user" name="username" required>
            </div>
            <div class="form-group">
                <label for="password_user" class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" class="form-control" id="password_user" name="password" required>
            </div>
            <button type="submit" class="btn-login">Login sebagai User</button>
            <div id="error_user" class="error-message">Username atau password salah!</div>
        </form>

        <!-- Admin Login Form -->
        <form id="adminForm" class="login-form" action="login_process.php" method="POST" style="display: none;">
            <input type="hidden" name="role" value="admin">
            <div class="form-group">
                <label for="username_admin" class="form-label"><i class="fas fa-user-shield"></i> Username</label>
                <input type="text" class="form-control" id="username_admin" name="username" required>
            </div>
            <div class="form-group">
                <label for="password_admin" class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" class="form-control" id="password_admin" name="password" required>
            </div>
            <button type="submit" class="btn-login">Login sebagai Admin</button>
            <div id="error_admin" class="error-message"><?php echo $message; ?></div>
        </form>

        <div class="footer-text">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>

    <script>
        function showTab(role) {
            const userForm = document.getElementById('userForm');
            const adminForm = document.getElementById('adminForm');
            const userButton = document.querySelector('.tab-button[onclick="showTab(\'user\')"]');
            const adminButton = document.querySelector('.tab-button[onclick="showTab(\'admin\')"]');

            if (role === 'user') {
                userForm.style.display = 'block';
                adminForm.style.display = 'none';
                userButton.classList.add('active');
                adminButton.classList.remove('active');
            } else {
                userForm.style.display = 'none';
                adminForm.style.display = 'block';
                userButton.classList.remove('active');
                adminButton.classList.add('active');
            }
        }

        // Validasi sederhana (akan digantikan oleh server-side validation)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const username = this.querySelector('input[name="username"]').value;
                const password = this.querySelector('input[name="password"]').value;
                const errorDiv = this.querySelector('.error-message');

                if (username === '' || password === '') {
                    e.preventDefault();
                    errorDiv.style.display = 'block';
                } else {
                    errorDiv.style.display = 'none';
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>