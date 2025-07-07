<?php
// Memulai sesi dan koneksi, diperlukan untuk memeriksa status login
require 'koneksi.php'; 

// Ambil data ringkasan dari database
$total_tim = $koneksi->query("SELECT COUNT(*) as total FROM tim")->fetch_assoc()['total'];
$total_produk = $koneksi->query("SELECT COUNT(*) as total FROM produk")->fetch_assoc()['total'];
$total_user = $koneksi->query("SELECT COUNT(*) as total FROM users WHERE is_admin = 0")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php 
        // Memuat menu navigasi sidebar
        require 'admin_sidebar.php'; 
        ?>

        <!-- Area Konten Utama -->
        <div class="main-content">
            <h2>Dashboard Admin</h2>
            
            <!-- Sapaan, hanya tampil jika login -->
            <?php if (isset($_SESSION['user'])): ?>
                <p class="welcome-message">Selamat datang kembali, <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong>!</p>
            <?php else: ?>
                <p class="welcome-message">Selamat datang di Panel Admin!</p>
            <?php endif; ?>
            
            <!-- Kartu Ringkasan -->
            <div class="summary-cards">
                <!-- Kartu Total Tim -->
                <div class="card">
                    <div class="card-icon" style="background-color: #3498db;"><span>👥</span></div>
                    <div class="card-content">
                        <h3>Total Anggota Tim</h3>
                        <p><?php echo $total_tim; ?></p>
                    </div>
                </div>

                <!-- Kartu Total Produk -->
                <div class="card">
                    <div class="card-icon" style="background-color: #2ecc71;"><span>📦</span></div>
                    <div class="card-content">
                        <h3>Total Produk</h3>
                        <p><?php echo $total_produk; ?></p>
                    </div>
                </div>

                <!-- Kartu Total User -->
                <div class="card">
                    <div class="card-icon" style="background-color: #e74c3c;"><span>👤</span></div>
                    <div class="card-content">
                        <h3>Total Pengguna</h3>
                        <p><?php echo $total_user; ?></p>
                    </div>
                </div>
            </div>

            <div class="info-box">
                <h3 style="margin-top: 0;">Pusat Kendali</h3>
                <p>Ini adalah pusat kendali untuk website Anda. Silakan pilih menu di samping untuk mulai mengelola konten.</p>
            </div>
        </div>
    </div>
</body>
</html>
