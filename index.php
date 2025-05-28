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
    <style>
        body {
            background: #FFFFFF; /* Warna latar belakang di luar kotak menjadi putih */
            font-family: 'Open Sans', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .login-container {
            background: rgba(209, 247, 209, 0.58); /* Latar belakang hijau muda dengan opacity rendah */
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            color: #2E7D32; /* Warna teks utama hijau tua untuk kontras */
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .login-container h2 {
            font-family: 'Poppins', sans-serif;
            color: #2E7D32; /* Tajuk hijau tua untuk konsistensi */
            text-align: center;
            margin-bottom: 30px;
        }
        .tab-button {
            background: none;
            border: none;
            color: #2E7D32; /* Warna tombol tab hijau tua */
            padding: 10px 20px;
            margin: 0 5px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: color 0.3s, border-bottom 0.3s;
        }
        .tab-button:hover, .tab-button.active {
            color: #1B5E20; /* Warna lebih gelap saat hover/active */
            border-bottom: 2px solid #1B5E20;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            color: #2E7D32; /* Warna label hijau tua */
            margin-bottom: 5px;
            font-weight: 600;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.3); /* Latar belakang input semi-transparan */
            border: none;
            border-radius: 5px;
            color: #2E7D32;
            padding: 10px;
            font-size: 1rem;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.5); /* Latar belakang input lebih terang saat fokus */
            box-shadow: 0 0 10px rgba(46, 125, 50, 0.5); /* Bayangan hijau tua */
            outline: none;
        }
        .btn-login {
            background-color: #2E7D32; /* Tombol login hijau tua */
            border: none;
            padding: 12px;
            width: 100%;
            color: #FFFFFF;
            font-size: 1.1rem;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-login:hover {
            background-color: #1B5E20; /* Warna lebih gelap saat hover */
            transform: translateY(-2px);
        }
        .error-message {
            color: #D32F2F; /* Warna error merah tua untuk kontras */
            font-size: 0.9rem;
            text-align: center;
            display: <?php echo $message ? 'block' : 'none'; ?>;
        }
        .footer-text {
            text-align: center;
            color: #2E7D32; /* Warna footer hijau tua */
            margin-top: 20px;
            font-size: 0.9rem;
        }
        .footer-text a {
            color: #1B5E20; /* Tautan di footer lebih gelap */
            text-decoration: none;
        }
        .footer-text a:hover {
            color: #388E3C; /* Warna tautan saat hover */
        }
        @media (max-width: 768px) {
            .login-container {
                padding: 20px;
                margin: 10px;
            }
            .tab-button {
                padding: 8px 15px;
                font-size: 1rem;
            }
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