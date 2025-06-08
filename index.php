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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Font Seragam untuk Seluruh Elemen */
        * {
            font-family: 'Poppins', sans-serif !important;
            font-weight: 400;
        }
        h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #343a40;
        }
        .form-label, .footer-text, .footer-text a {
            font-size: 1rem;
            font-weight: 400;
        }
        .btn-login, .tab-button {
            font-size: 0.95rem;
            font-weight: 500;
        }
        /* Pemusatan dan Gradasi Latar Belakang */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #e6f4ea, #e0f7fa);
        }
        /* Styling Login Container */
        .login-container {
            max-width: 600px;
            margin: auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        /* Styling Tab Buttons */
        .tab-button {
            padding: 10px 20px;
            margin: 0 5px;
            border-radius: 20px;
            border: 1px solid transparent;
            background-color: #e9ecef;
            color: #343a40;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .tab-button.active, .tab-button:hover {
            background-color: #28a745;
            color: #fff;
            border-color: #28a745;
        }
        /* Styling Form */
        .login-form {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-control {
            font-size: 1rem;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
            transition: border-color 0.2s ease;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.2);
        }
        .form-label {
            color: #343a40;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        /* Styling Login Button */
        .btn-login {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            background-color: #28a745;
            color: #fff;
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s ease;
        }
        .btn-login:hover {
            background-color: #218838;
        }
        /* Styling Error Message */
        .error-message {
            display: <?php echo $message ? 'block' : 'none'; ?>;
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 10px;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
        }
        /* Styling Footer Text */
        .footer-text {
            margin-top: 20px;
            color: #343a40;
        }
        .footer-text a {
            color: #28a745;
            text-decoration: none;
            font-weight: 500;
        }
        .footer-text a:hover {
            color: #218838;
            text-decoration: underline;
        }
        /* Responsive Design */
        @media (max-width: 576px) {
            .login-container {
                max-width: 90%;
                padding: 20px;
            }
            h2 {
                font-size: 1.5rem;
            }
            .tab-button {
                padding: 8px 16px;
                font-size: 0.85rem;
            }
            .btn-login {
                padding: 10px;
                font-size: 0.85rem;
            }
            .form-control {
                font-size: 0.9rem;
                padding: 8px;
            }
            .form-label, .footer-text, .footer-text a {
                font-size: 0.9rem;
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