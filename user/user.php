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
        .service-card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        .service-card:hover {
            transform: translateY(-10px);
        }
        .service-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
            background: rgba(0, 0, 0, 0.3);
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #4CAF50;
            border-radius: 50%;
        }
        @media (max-width: 767px) {
            .carousel-inner .carousel-item > div {
                display: none;
            }
            .carousel-inner .carousel-item > div:first-child {
                display: block;
            }
        }
        .carousel-inner .carousel-item.active,
        .carousel-inner .carousel-item-next,
        .carousel-inner .carousel-item-prev {
            display: flex;
        }
        @media (min-width: 768px) {
            .carousel-inner .carousel-item-end.active,
            .carousel-inner .carousel-item-next {
                transform: translateX(33.33%);
            }
            .carousel-inner .carousel-item-start.active,
            .carousel-inner .carousel-item-prev {
                transform: translateX(-33.33%);
            }
        }
        .carousel-inner .carousel-item-end,
        .carousel-inner .carousel-item-start {
            transform: translateX(0);
        }
        .form-card {
            max-width: 600px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .form-label {
            font-family: 'Poppins', sans-serif;
            color: #4CAF50;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-control, .form-select {
            border: 1px solid #4CAF50;
            border-radius: 5px;
            padding: 10px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #81C784;
            box-shadow: 0 0 5px rgba(129, 199, 132, 0.5);
            outline: none;
        }
        .btn-danger {
            padding: 10px 15px;
            font-size: 0.9rem;
        }
        .service-item {
            border: 1px solid #4CAF50;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #F5F5F5;
        }
        .service-item .row {
            align-items: center;
        }
        .receipt-card {
            max-width: 600px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            display: none;
        }
        .receipt-card p {
            margin-bottom: 10px;
        }
        .receipt-card strong {
            color: #4CAF50;
        }
        .receipt-card table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .receipt-card th, .receipt-card td {
            padding: 8px;
            border-bottom: 1px solid #4CAF50;
            text-align: left;
        }
        .receipt-card th {
            background-color: #E8F5E9;
            color: #4CAF50;
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
            <div class="carousel-item active" style="background-image: url('../img/depanback1.jpeg');">
                <div class="carousel-caption">
                    <h1>Cahaya Laundry: Solusi Cucian Anda!</h1>
                    <p>Cucian bersih, wangi, dan terjangkau dengan layanan cepat dan ramah lingkungan.</p>
                    <div class="mt-4">
                        <a href="#booking-section" class="btn btn-primary me-3">Pesan Sekarang</a>
                        <a href="#services-section" class="btn btn-secondary">Lihat Layanan</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('../img/depanback2.jpeg');">
                <div class="carousel-caption">
                    <h1>Cucian Rapi, Hidup Lebih Mudah!</h1>
                    <p>Nikmati layanan laundry modern dengan teknologi ramah lingkungan.</p>
                    <div class="mt-4">
                        <a href="#booking-section" class="btn btn-primary me-3">Pesan Sekarang</a>
                        <a href="#services-section" class="btn btn-secondary">Lihat Layanan</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('../img/depanback3.jpeg');">
                <div class="carousel-caption">
                    <h1>Laundry Cepat & Terpercaya!</h1>
                    <p>Percayakan pakaian Anda kepada kami untuk hasil terbaik.</p>
                    <div class="mt-4">
                        <a href="#booking-section" class="btn btn-primary me-3">Pesan Sekarang</a>
                        <a href="#services-section" class="btn btn-secondary">Lihat Layanan</a>
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

    <!-- Services Section -->
    <section id="services-section" class="container my-5">
        <h2 class="text-center mb-5">Layanan Kami</h2>
        <div id="servicesCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <!-- Item 1: Cuci Basah, Cuci Kering, Cuci Setrika -->
                <div class="carousel-item active">
                    <div class="col-md-4">
                        <div class="card service-card text-center">
                            <img src="../img/cucibasah.jpg" class="card-img-top" alt="Cuci Basah">
                            <div class="card-body">
                                <h4 class="card-title">Cuci Basah</h4>
                                <p class="card-text">Layanan cuci basah untuk pakaian sehari-hari dengan deterjen ramah lingkungan.</p>
                                <a href="#booking-section" class="btn btn-primary mt-3">Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card service-card text-center">
                            <img src="../img/cuci kering.jpg" class="card-img-top" alt="Cuci Kering">
                            <div class="card-body">
                                <h4 class="card-title">Cuci Kering</h4>
                                <p class="card-text">Pembersihan pakaian khusus tanpa air, cocok untuk bahan sensitif.</p>
                                <a href="#booking-section" class="btn btn-primary mt-3">Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card service-card text-center">
                            <img src="../img/cuci-setrika-wangi.jpg" class="card-img-top" alt="Cuci Setrika">
                            <div class="card-body">
                                <h4 class="card-title">Cuci Setrika</h4>
                                <p class="card-text">Cuci dan setrika pakaian Anda agar siap pakai dengan rapi.</p>
                                <a href="#booking-section" class="btn btn-primary mt-3">Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Item 2: Setrika, Cuci Sprei, Cuci Boneka -->
                <div class="carousel-item">
                    <div class="col-md-4">
                        <div class="card service-card text-center">
                            <img src="../img/setrika34.jpg" class="card-img-top" alt="Setrika">
                            <div class="card-body">
                                <h4 class="card-title">Setrika</h4>
                                <p class="card-text">Layanan setrika saja untuk pakaian yang sudah dicuci, rapi maksimal.</p>
                                <a href="#booking-section" class="btn btn-primary mt-3">Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card service-card text-center">
                            <img src="../img/cara-mencuci-dan-merawat-sprei-katun-bahan-katun.jpg" class="card-img-top" alt="Cuci Sprei">
                            <div class="card-body">
                                <h4 class="card-title">Cuci Sprei</h4>
                                <p class="card-text">Pembersihan sprei dan bed cover agar tetap bersih dan wangi.</p>
                                <a href="#booking-section" class="btn btn-primary mt-3">Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card service-card text-center">
                            <img src="../img/boneka.jpg" class="card-img-top" alt="Cuci Boneka">
                            <div class="card-body">
                                <h4 class="card-title">Cuci Boneka</h4>
                                <p class="card-text">Cuci boneka kesayangan dengan perawatan khusus agar tetap lembut.</p>
                                <a href="#booking-section" class="btn btn-primary mt-3">Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Item 3: Cuci Karpet -->
                <div class="carousel-item">
                    <div class="col-md-4">
                        <div class="card service-card text-center">
                            <img src="../img/Cuci-Karpet-Tangerang-Selatan.jpg" class="card-img-top" alt="Cuci Karpet">
                            <div class="card-body">
                                <h4 class="card-title">Cuci Karpet</h4>
                                <p class="card-text">Layanan cuci karpet untuk menghilangkan debu dan kotoran secara mendalam.</p>
                                <a href="#booking-section" class="btn btn-primary mt-3">Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#servicesCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#servicesCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Booking Section -->
    <section id="booking-section" class="container my-5">
        <h2 class="text-center mb-5">Pesan Layanan Laundry</h2>
        <div class="form-card" id="bookingFormCard">
            <form id="bookingForm">
                <div class="mb-3">
                    <label for="name" class="form-label"><i class="fas fa-user"></i> Nama</label>
                    <input type="text" class="form-control" id="name" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label"><i class="fas fa-phone"></i> Nomor HP</label>
                    <input type="tel" class="form-control" id="phone" placeholder="Masukkan nomor HP Anda" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                    <textarea class="form-control" id="address" rows="3" placeholder="Masukkan alamat Anda" required></textarea>
                </div>

                <!-- Dynamic Services Section -->
                <div id="servicesContainer">
                    <div class="service-item" data-index="0">
                        <div class="row">
                            <div class="col-md-5 mb-2">
                                <label class="form-label"><i class="fas fa-tshirt"></i> Jenis Layanan</label>
                                <select class="form-select service-type" required>
                                    <option value="" disabled selected>Pilih layanan</option>
                                    <option value="Cuci Basah">Cuci Basah - Rp15.000/kg</option>
                                    <option value="Cuci Setrika">Cuci Setrika - Rp20.000/kg</option>
                                    <option value="Dry Cleaning">Dry Cleaning - Rp25.000/kg</option>
                                    <option value="Cuci Boneka">Cuci Boneka - Rp30.000/unit</option>
                                    <option value="Setrika">Setrika - Rp10.000/kg</option>
                                    <option value="Cuci Sprei">Cuci Sprei - Rp20.000/unit</option>
                                    <option value="Cuci Karpet">Cuci Karpet - Rp50.000/unit</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label"><i class="fas fa-weight"></i> Berat (kg) / Jumlah Unit</label>
                                <input type="number" class="form-control service-quantity" min="1" placeholder="Masukkan berat/jumlah" required>
                            </div>
                            <div class="col-md-3 mb-2 d-flex align-items-end justify-content-end">
                                <button type="button" class="btn btn-danger btn-remove-service" disabled>Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-3">
                    <button type="button" class="btn btn-primary" id="addServiceBtn">Tambah Layanan</button>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label"><i class="fas fa-sticky-note"></i> Catatan (opsional)</label>
                    <textarea class="form-control" id="notes" rows="3" placeholder="Masukkan catatan tambahan"></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Pesan Sekarang</button>
                </div>
            </form>
        </div>

        <!-- Receipt Section -->
        <div class="receipt-card" id="receiptCard">
            <h2 class="text-center mb-4">Struk Pemesanan</h2>
            <p><strong>Nama:</strong> <span id="receiptName"></span></p>
            <p><strong>Nomor HP:</strong> <span id="receiptPhone"></span></p>
            <p><strong>Alamat:</strong> <span id="receiptAddress"></span></p>
            <p><strong>Catatan:</strong> <span id="receiptNotes"></span></p>
            <h4 class="mt-4">Detail Layanan:</h4>
            <table>
                <thead>
                    <tr>
                        <th>Jenis Layanan</th>
                        <th>Berat/Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="receiptServices"></tbody>
            </table>
            <p><strong>Total Harga:</strong> <span id="receiptTotal"></span></p>
            <p class="mt-3">Silakan datang ke Cahaya Laundry untuk membayar dan menyerahkan cucian.</p>
            <div class="text-center mt-4">
                <a href="index.html" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-light py-5 text-center">
        <div class="container">
            <h2 class="mb-4">Siap Mencoba Cahaya Laundry? 🚀</h2>
            <p class="mb-4">Dapatkan cucian bersih dan wangi dengan layanan terbaik kami. Pesan sekarang dan nikmati kemudahan laundry modern!</p>
            <a href="#booking-section" class="btn btn-primary btn-lg">Mulai Sekarang</a>
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
        // Ripple effect for buttons
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

        // Services carousel script
        document.addEventListener('DOMContentLoaded', function () {
            const carousel = document.querySelector('#servicesCarousel');
            const items = carousel.querySelectorAll('.carousel-item');
            items.forEach((el) => {
                const minPerSlide = 3;
                let next = el.nextElementSibling;
                for (let i = 1; i < minPerSlide; i++) {
                    if (!next) {
                        next = items[0];
                    }
                    let cloneChild = next.cloneNode(true);
                    el.appendChild(cloneChild.children[0]);
                    next = next.nextElementSibling;
                }
            });
        });

        // Booking form script
        let serviceIndex = 0;
        document.getElementById('addServiceBtn').addEventListener('click', function () {
            serviceIndex++;
            const servicesContainer = document.getElementById('servicesContainer');
            const newServiceItem = document.createElement('div');
            newServiceItem.classList.add('service-item');
            newServiceItem.setAttribute('data-index', serviceIndex);
            newServiceItem.innerHTML = `
                <div class="row">
                    <div class="col-md-5 mb-2">
                        <label class="form-label"><i class="fas fa-tshirt"></i> Jenis Layanan</label>
                        <select class="form-select service-type" required>
                            <option value="" disabled selected>Pilih layanan</option>
                            <option value="Cuci Basah">Cuci Basah - Rp15.000/kg</option>
                            <option value="Cuci Setrika">Cuci Setrika - Rp20.000/kg</option>
                            <option value="Dry Cleaning">Dry Cleaning - Rp25.000/kg</option>
                            <option value="Cuci Boneka">Cuci Boneka - Rp30.000/unit</option>
                            <option value="Setrika">Setrika - Rp10.000/kg</option>
                            <option value="Cuci Sprei">Cuci Sprei - Rp20.000/unit</option>
                            <option value="Cuci Karpet">Cuci Karpet - Rp50.000/unit</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label"><i class="fas fa-weight"></i> Berat (kg) / Jumlah Unit</label>
                        <input type="number" class="form-control service-quantity" min="1" placeholder="Masukkan berat/jumlah" required>
                    </div>
                    <div class="col-md-3 mb-2 d-flex align-items-end justify-content-end">
                        <button type="button" class="btn btn-danger btn-remove-service">Hapus</button>
                    </div>
                </div>
            `;
            servicesContainer.appendChild(newServiceItem);
        });

        document.getElementById('servicesContainer').addEventListener('click', function (event) {
            if (event.target.classList.contains('btn-remove-service')) {
                const serviceItem = event.target.closest('.service-item');
                serviceItem.remove();
            }
        });

        document.getElementById('bookingForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const address = document.getElementById('address').value;
            const notes = document.getElementById('notes').value || '-';

            const serviceItems = document.querySelectorAll('.service-item');
            const services = [];
            let totalPrice = 0;

            serviceItems.forEach(item => {
                const serviceType = item.querySelector('.service-type').value;
                const quantity = parseFloat(item.querySelector('.service-quantity').value) || 1;

                let pricePerUnit = 0;
                if (serviceType === 'Cuci Basah') {
                    pricePerUnit = 15000;
                } else if (serviceType === 'Cuci Setrika') {
                    pricePerUnit = 20000;
                } else if (serviceType === 'Dry Cleaning') {
                    pricePerUnit = 25000;
                } else if (serviceType === 'Cuci Boneka') {
                    pricePerUnit = 30000;
                } else if (serviceType === 'Setrika') {
                    pricePerUnit = 10000;
                } else if (serviceType === 'Cuci Sprei') {
                    pricePerUnit = 20000;
                } else if (serviceType === 'Cuci Karpet') {
                    pricePerUnit = 50000;
                }

                const subtotal = pricePerUnit * quantity;
                totalPrice += subtotal;

                services.push({
                    type: serviceType,
                    quantity: quantity,
                    subtotal: subtotal
                });
            });

            const formattedTotalPrice = totalPrice.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });

            document.getElementById('bookingFormCard').style.display = 'none';
            document.getElementById('receiptCard').style.display = 'block';

            document.getElementById('receiptName').textContent = name;
            document.getElementById('receiptPhone').textContent = phone;
            document.getElementById('receiptAddress').textContent = address;
            document.getElementById('receiptNotes').textContent = notes;
            document.getElementById('receiptTotal').textContent = formattedTotalPrice;

            const receiptServices = document.getElementById('receiptServices');
            receiptServices.innerHTML = '';
            services.forEach(service => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${service.type}</td>
                    <td>${service.quantity} ${service.type === 'Cuci Boneka' || service.type === 'Cuci Sprei' || service.type === 'Cuci Karpet' ? 'unit' : 'kg'}</td>
                    <td>${service.subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
                `;
                receiptServices.appendChild(row);
            });
        });
    </script>
</body>
</html>