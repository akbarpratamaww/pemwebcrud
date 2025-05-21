<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cahaya Laundry - Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #E8F5E9;
            font-family: 'Open Sans', sans-serif;
        }
        h1, h2, h3 {
            font-family: 'Poppins', sans-serif;
            color: #4CAF50;
        }
        .navbar {
            background-color: #FFFFFF;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            color: #4CAF50 !important;
            font-weight: 600;
        }
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(76, 175, 80, 1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        .offcanvas {
            width: 250px;
            background-color: #FFFFFF;
            transition: transform 0.3s ease-in-out;
        }
        .offcanvas-end {
            transform: translateX(100%);
        }
        .offcanvas-end.show {
            transform: translateX(0);
        }
        .nav-link {
            color: #FFFFFF !important;
            background-color: #4CAF50;
            padding: 8px 16px !important;
            margin: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-align: center;
            display: block;
        }
        .nav-link:hover {
            background-color: #81C784 !important;
        }
        .nav-link.active {
            background-color: #81C784 !important;
        }
        .hero-carousel {
            height: 500px;
        }
        .hero-carousel .carousel-item {
            height: 500px;
            background-size: cover;
            background-position: center;
        }
        .hero-carousel .carousel-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
        }
        .hero-carousel .carousel-caption {
            top: 50%;
            transform: translateY(-50%);
        }
        .hero-carousel h1 {
            font-size: 3rem;
            color: #FFFFFF;
        }
        .hero-carousel p {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #FFFFFF;
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
            padding: 10px 30px;
            font-size: 1.1rem;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            background-color: #81C784;
            border-color: #81C784;
        }
        .btn-secondary {
            background-color: #B0BEC5;
            border-color: #B0BEC5;
            padding: 10px 30px;
            font-size: 1.1rem;
        }
        .btn-secondary:hover {
            background-color: #90A4AE;
            border-color: #90A4AE;
        }
        .ripple-effect {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        .feature-card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .testimoni-card {
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .faq-item {
            margin-bottom: 15px;
        }
        .faq-item h5 {
            cursor: pointer;
            color: #4CAF50;
        }
        footer {
            background-color: #FFFFFF;
            color: #4CAF50;
            padding: 40px 0;
        }
        .footer-link {
            color: #4CAF50;
            text-decoration: none;
        }
        .footer-link:hover {
            color: #81C784;
        }
        .icon-img {
            width: 60px;
            height: 60px;
            font-size: 60px; /* Untuk ikon Bootstrap */
            color: #4CAF50; /* Warna konsisten dengan tema */
            line-height: 60px; /* Pusatkan vertikal */
        }
        .map-section {
            background-color: #2E3B4E;
            color: #FFFFFF;
            padding: 40px 0;
        }
        .map-section h5 {
            color: #FFFFFF;
            font-family: 'Poppins', sans-serif;
        }
        .map-section p {
            margin-bottom: 10px;
        }
        .map-section a {
            color: #4CAF50;
            text-decoration: none;
        }
        .map-section a:hover {
            color: #81C784;
        }
        .social-icons a {
            color: #FFFFFF;
            font-size: 1.5rem;
            margin: 0 10px;
        }
        .social-icons a:hover {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">Cahaya Laundry</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" id="navbarNav">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" style="color: #4CAF50;">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="index.html">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.html">Tentang</a></li>
                        <li class="nav-item"><a class="nav-link" href="services.html">Layanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="pricing.html">Harga</a></li>
                        <li class="nav-item"><a class="nav-link" href="booking.html">Pesan</a></li>
                        <li class="nav-item"><a class="nav-link" href="orders.html">Daftar Pesanan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Slider) -->
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('assets/depanback1.jpeg');">
                <div class="carousel-caption">
                    <h1>Cahaya Laundry: Solusi Cucian Anda! </h1>
                    <p>Cucian bersih, wangi, dan terjangkau dengan layanan cepat dan ramah lingkungan.</p>
                    <div class="mt-4">
                        <a href="booking.html" class="btn btn-primary me-3">Pesan Sekarang</a>
                        <a href="services.html" class="btn btn-secondary">Lihat Layanan</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('assets/depanback2.jpeg');">
                <div class="carousel-caption">
                    <h1>Cucian Rapi, Hidup Lebih Mudah! </h1>
                    <p>Nikmati layanan laundry modern dengan teknologi ramah lingkungan.</p>
                    <div class="mt-4">
                        <a href="booking.html" class="btn btn-primary me-3">Pesan Sekarang</a>
                        <a href="services.html" class="btn btn-secondary">Lihat Layanan</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('assets/depanback3.jpeg');">
                <div class="carousel-caption">
                    <h1>Laundry Cepat & Terpercaya! </h1>
                    <p>Percayakan pakaian Anda kepada kami untuk hasil terbaik.</p>
                    <div class="mt-4">
                        <a href="booking.html" class="btn btn-primary me-3">Pesan Sekarang</a>
                        <a href="services.html" class="btn btn-secondary">Lihat Layanan</a>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Features Section -->
    <section class="container my-5">
        <h2 class="text-center mb-5">Keunggulan Cahaya Laundry 🌈</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card feature-card text-center">
                    <i class="bi bi-rocket-takeoff icon-img mx-auto mt-4"></i>
                    <div class="card-body">
                        <h4 class="card-title">Layanan Cepat ⚡</h4>
                        <p class="card-text">Cucian Anda selesai dalam waktu singkat, tanpa mengorbankan kualitas. Kami paham waktu Anda berharga! ⏰</p>
                        <a href="services.html" class="btn btn-primary mt-3">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card feature-card text-center">
                    <i class="bi bi-tree-fill icon-img mx-auto mt-4"></i>
                    <div class="card-body">
                        <h4 class="card-title">Ramah Lingkungan 🌍</h4>
                        <p class="card-text">Kami menggunakan deterjen biodegradable dan teknologi hemat air untuk menjaga bumi tetap hijau. 🌱</p>
                        <a href="services.html" class="btn btn-primary mt-3">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card feature-card text-center">
                    <i class="bi bi-cash-coin icon-img mx-auto mt-4"></i>
                    <div class="card-body">
                        <h4 class="card-title">Harga Terjangkau 💸</h4>
                        <p class="card-text">Nikmati layanan laundry berkualitas tinggi dengan harga yang ramah di kantong untuk semua kalangan. 🤑</p>
                        <a href="pricing.html" class="btn btn-primary mt-3">Lihat Harga</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="container my-5">
        <h2 class="text-center mb-5">Mengapa Cahaya Laundry?</h2>
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="assets/fotodepan.jpg" class="img-fluid rounded" alt="Cahaya Laundry">
            </div>
            <div class="col-md-6">
                <p>Cahaya Laundry hadir sejak 2021 sebagai solusi modern untuk kebutuhan laundry Anda. Kami menggabungkan teknologi canggih dengan pendekatan ramah lingkungan untuk memberikan hasil cucian yang bersih, wangi, dan terawat.</p>
                <p>Kami percaya bahwa pakaian bersih bukan hanya soal penampilan, tetapi juga kenyamanan dan kesehatan. Oleh karena itu, setiap proses kami dirancang untuk memberikan pengalaman terbaik bagi pelanggan, mulai dari pemesanan online hingga pengambilan cucian.</p>
                <p>Dengan tim profesional dan komitmen terhadap kepuasan pelanggan, Cahaya Laundry siap menjadi mitra terpercaya Anda. Coba layanan kami sekarang dan rasakan perbedaannya!</p>
                <a href="about.html" class="btn btn-primary mt-3">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </section>

    <!-- Testimoni Section -->
    <section class="container my-5">
        <h2 class="text-center mb-5">Apa Kata Pelanggan Kami 😊</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="testimoni-card text-center">
                    <p>"Cahaya Laundry sangat membantu saya yang sibuk. Cucian selalu selesai tepat waktu dan hasilnya sangat bersih! "</p>
                    <h5>- Defina, Ibu Rumah Tangga</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimoni-card text-center">
                    <p>"Saya suka karena mereka menggunakan deterjen ramah lingkungan. Pakaian saya wangi dan terasa lembut! "</p>
                    <h5>- Akbar, Pegawai Kantoran</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimoni-card text-center">
                    <p>"Harga terjangkau dan pelayanan ramah. Saya pasti akan kembali lagi! 😍"</p>
                    <h5>- Salsa, Mahasiswi</h5>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="container my-5">
        <h2 class="text-center mb-5">Pertanyaan Umum</h2>
        <div class="accordion" id="faqAccordion">
            <div class="faq-item">
                <h5 data-bs-toggle="collapse" data-bs-target="#faq1">Berapa lama proses pencucian?</h5>
                <div id="faq1" class="collapse" data-bs-parent="#faqAccordion">
                    <p>Untuk cuci kiloan, biasanya selesai dalam 1-2 hari. Dry cleaning mungkin memerlukan waktu tambahan tergantung jenis pakaian.</p>
                </div>
            </div>
            <div class="faq-item">
                <h5 data-bs-toggle="collapse" data-bs-target="#faq2">Apakah ada layanan antar-jemput?</h5>
                <div id="faq2" class="collapse" data-bs-parent="#faqAccordion">
                    <p>Ya, kami menyediakan layanan antar-jemput gratis untuk area tertentu. Hubungi kami untuk informasi lebih lanjut.</p>
                </div>
            </div>
            <div class="faq-item">
                <h5 data-bs-toggle="collapse" data-bs-target="#faq3">Apakah deterjen aman untuk kulit sensitif?</h5>
                <div id="faq3" class="collapse" data-bs-parent="#faqAccordion">
                    <p>Kami menggunakan deterjen biodegradable yang lembut dan aman untuk kulit sensitif.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-light py-5 text-center">
        <div class="container">
            <h2 class="mb-4">Siap Mencoba Cahaya Laundry? 🚀</h2>
            <p class="mb-4">Dapatkan cucian bersih dan wangi dengan layanan terbaik kami. Pesan sekarang dan nikmati kemudahan laundry modern!</p>
            <a href="booking.html" class="btn btn-primary btn-lg">Mulai Sekarang</a>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5>Kontak Kami</h5>
                    <p><strong>Cahaya Laundry</strong></p>
                    <p>Alamat: Jl. Raya Bersih No. 123, Kota</p>
                    <p>HP/WA: <a href="tel:+621234567890">+62 123 456 7890</a></p>
                    <p>Email: <a href="mailto:info@cahayalaundry.id">info@cahayalaundry.id</a></p>
                    <div class="social-icons mt-3">
                        <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.668!2d112.737826!3d-7.275614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fbd96e8e6b1f%3A0x4e4c7a8b8b1b1d1e!2sSurabaya%2C%20Jawa%20Timur!5e0!3m2!1sid!2sid!4v1698765432100!5m2!1sid!2sid" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="mt-4">© 2025 Cahaya Laundry. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.btn-primary').forEach(button => {
            button.addEventListener('click', function (e) {
                const ripple = document.createElement('span');
                ripple.classList.add('ripple-effect');
                const rect = button.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = e.clientX - rect.left - size / 2 + 'px';
                ripple.style.top = e.clientY - rect.top - size / 2 + 'px';
                button.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });
    </script>
</body>
</html>