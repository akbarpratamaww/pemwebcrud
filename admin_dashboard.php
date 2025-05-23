<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php';

// Statistik dasar
try {
    // Total Pendapatan
    $totalIncome = $pdo->query("SELECT SUM(p.berat_unit * l.harga_layanan) as total FROM pelanggan p JOIN layanan l ON p.id_layanan = l.id_layanan")->fetchColumn();

    // Total Pesanan
    $totalOrders = $pdo->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();

    // Total Pelanggan (berdasarkan nama unik)
    $totalCustomers = $pdo->query("SELECT COUNT(DISTINCT nama_pelanggan) FROM pelanggan")->fetchColumn();

    // Target Pendapatan (contoh: Rp150.000, bisa disesuaikan)
    $salesTarget = 150000;

    // Total Keuntungan (misalnya 10% dari pendapatan, bisa disesuaikan)
    $totalProfit = $totalIncome ? $totalIncome * 0.1 : 0;

    // Data untuk Sales Overview (Pie Chart)
    $salesOverview = $pdo->query("SELECT l.nama_layanan, SUM(p.berat_unit * l.harga_layanan) as revenue 
                                 FROM pelanggan p 
                                 JOIN layanan l ON p.id_layanan = l.id_layanan 
                                 GROUP BY l.id_layanan")->fetchAll(PDO::FETCH_ASSOC);

    // Data untuk Sales Performance (Line Chart) - Pendapatan per bulan
    $salesPerformance = $pdo->query("SELECT DATE_FORMAT(tanggal_masuk, '%Y-%m') as month, SUM(p.berat_unit * l.harga_layanan) as revenue 
                                    FROM pelanggan p 
                                    JOIN layanan l ON p.id_layanan = l.id_layanan 
                                    GROUP BY DATE_FORMAT(tanggal_masuk, '%Y-%m') 
                                    ORDER BY month")->fetchAll(PDO::FETCH_ASSOC);

    // Data untuk Top Selling Items
    $topSelling = $pdo->query("SELECT l.nama_layanan, COUNT(p.id_pelanggan) as order_count 
                              FROM pelanggan p 
                              JOIN layanan l ON p.id_layanan = l.id_layanan 
                              GROUP BY l.id_layanan 
                              ORDER BY order_count DESC 
                              LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Cahaya Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #F5F5F5;
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #4CAF50;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            color: #FFFFFF;
            transition: all 0.3s;
        }
        .sidebar a {
            color: #FFFFFF;
            padding: 15px 20px;
            text-decoration: none;
            display: block;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #81C784;
        }
        .sidebar a.active {
            background-color: #388E3C;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }
        .dashboard-header {
            background: url('https://images.unsplash.com/photo-1557800634-7eb5e3cc7aa2?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center;
            background-size: cover;
            padding: 20px;
            border-radius: 10px;
            color: #FFFFFF;
            margin-bottom: 20px;
        }
        .dashboard-header h2 {
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }
        .stats-card {
            background-color: #FFFFFF;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            transition: transform 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .stats-card i {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .chart-container {
            background-color: #FFFFFF;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .top-selling-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #FFFFFF;
            border-radius: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .top-selling-item:hover {
            transform: translateX(5px);
        }
        .top-selling-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mb-4">Cahaya Laundry</h4>
        <a href="admin_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="admin_layanan.php"><i class="fas fa-concierge-bell"></i> Layanan</a>
        <a href="admin_pelanggan.php"><i class="fas fa-users"></i> Pelanggan</a>
        <a href="admin_pesanan.php"><i class="fas fa-shopping-basket"></i> Pesanan</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="dashboard-header">
            <h2>Sales Distribution</h2>
            <div class="d-flex justify-content-around flex-wrap">
                <div class="stats-card">
                    <i class="fas fa-money-bill-wave"></i>
                    <h5>Total Pendapatan</h5>
                    <p>Rp. <?php echo number_format($totalIncome ?: 0, 0, ',', '.'); ?></p>
                </div>
                <div class="stats-card">
                    <i class="fas fa-box"></i>
                    <h5>Total Pesanan</h5>
                    <p><?php echo $totalOrders ?: 0; ?></p>
                </div>
                <div class="stats-card">
                    <i class="fas fa-shopping-cart"></i>
                    <h5>Total Pelanggan</h5>
                    <p><?php echo $totalCustomers ?: 0; ?></p>
                </div>
                <div class="stats-card">
                    <i class="fas fa-bullseye"></i>
                    <h5>Target Pendapatan</h5>
                    <p>Rp. <?php echo number_format($salesTarget, 0, ',', '.'); ?> (0%)</p>
                </div>
                <div class="stats-card">
                    <i class="fas fa-chart-line"></i>
                    <h5>Total Keuntungan</h5>
                    <p>Rp. <?php echo number_format($totalProfit ?: 0, 0, ',', '.'); ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 chart-container">
                <h3>Sales Overview</h3>
                <canvas id="salesOverviewChart"></canvas>
            </div>
            <div class="col-md-4 chart-container">
                <h3>Sales Performance</h3>
                <canvas id="salesPerformanceChart"></canvas>
            </div>
            <div class="col-md-4 chart-container">
                <h3>Top Selling Items</h3>
                <div id="topSellingItems">
                    <?php foreach ($topSelling as $index => $item): ?>
                        <div class="top-selling-item">
                            <img src="https://via.placeholder.com/40?text=Icon" alt="<?php echo htmlspecialchars($item['nama_layanan']); ?>">
                            <span><?php echo htmlspecialchars($item['nama_layanan']); ?> - <?php echo $item['order_count']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sales Overview (Pie Chart)
        const salesOverviewCtx = document.getElementById('salesOverviewChart').getContext('2d');
        new Chart(salesOverviewCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($salesOverview, 'nama_layanan')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($salesOverview, 'revenue')); ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Distribusi Pendapatan per Layanan' }
                }
            }
        });

        // Sales Performance (Line Chart)
        const salesPerformanceCtx = document.getElementById('salesPerformanceChart').getContext('2d');
        new Chart(salesPerformanceCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($salesPerformance, 'month')); ?>,
                datasets: [{
                    label: 'Pendapatan',
                    data: <?php echo json_encode(array_column($salesPerformance, 'revenue')); ?>,
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Pendapatan Harian/Bulanan' }
                },
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Rp' } }
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>