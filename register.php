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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Font Seragam */
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
        .btn-register {
            font-size: 0.95rem;
            font-weight: 500;
        }
        /* Pemusatan dan Gradasi Latar */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #e0f7fa, #b2dfdb);
        }
        /* Register Container */
        .register-container {
            max-width: 500px;
            margin: auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fadeIn 0.7s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Form Styling */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .input-group {
            position: relative;
        }
        .form-control {
            font-size: 1rem;
            padding: 10px 10px 10px 40px;
            height: 45px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border: 2px solid transparent;
            border-image: linear-gradient(to right, #28a745, #218838) 1;
            box-shadow: 0 0 8px rgba(40, 167, 69, 0.2);
            transform: scale(1.02);
            outline: none;
        }
        textarea.form-control {
            height: auto;
            min-height: 80px;
            padding: 10px;
            resize: vertical;
        }
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        textarea ~ .input-icon {
            top: 20px;
            transform: none;
        }
        .form-control:focus + .input-icon {
            color: #28a745;
            transform: translateY(-50%) scale(1.2);
        }
        textarea:focus ~ .input-icon {
            transform: scale(1.2);
        }
        .form-label {
            color: #343a40;
            margin-bottom: 6px;
        }
        /* Register Button */
        .btn-register {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            background: linear-gradient(to right, #28a745, #218838);
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }
        /* Error Message */
        .error-message {
            display: <?php echo $error ? 'block' : 'none'; ?>;
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 10px;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease;
        }
        /* Footer Text */
        .footer-text {
            margin-top: 20px;
            color: #343a40;
        }
        .footer-text a {
            color: #28a745;
            text-decoration: none;
            font-weight: 500;
            position: relative;
        }
        .footer-text a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #28a745;
            transition: width 0.3s ease;
        }
        .footer-text a:hover::after {
            width: 100%;
        }
        .footer-text a:hover {
            color: #218838;
        }
        /* Responsive Design */
        @media (max-width: 576px) {
            .register-container {
                max-width: 90%;
                padding: 20px;
            }
            h2 {
                font-size: 1.6rem;
            }
            .btn-register {
                padding: 10px;
                font-size: 0.9rem;
            }
            .form-control {
                font-size: 0.9rem;
                padding: 8px 8px 8px 36px;
                height: 40px;
            }
            textarea.form-control {
                padding: 8px;
                min-height: 60px;
            }
            .input-icon {
                font-size: 0.9rem;
            }
            textarea ~ .input-icon {
                top: 16px;
            }
            .form-label, .footer-text, .footer-text a {
                font-size: 0.9rem;
            }
            .btn-register:hover {
                transform: none;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registrasi - Cahaya Laundry</h2>

        <!-- Registration Form -->
        <form id="registerForm" action="register_process.php" method="POST">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="username" name="username" required maxlength="50" placeholder="Username">
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required minlength="6" placeholder="Password">
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="name" name="name" required maxlength="100" placeholder="Nama Lengkap">
                    <i class="fas fa-user-circle input-icon"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <div class="input-group">
                    <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10,15}" title="Masukkan nomor telepon yang valid (10-15 digit)" required placeholder="Nomor Telepon">
                    <i class="fas fa-phone input-icon"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="form-label">Alamat</label>
                <div class="input-group">
                    <textarea class="form-control" id="address" name="address" rows="3" required placeholder="Alamat"></textarea>
                    <i class="fas fa-map-marker-alt input-icon"></i>
                </div>
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