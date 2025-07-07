<?php
/*
// BLOK KODE INI DI-NONAKTIFKAN AGAR TIDAK PERLU LOGIN
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header('Location: login.php');
    exit;
}
*/

// Untuk menandai link menu yang aktif
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <h3>Admin Panel</h3>
    <a href="admin-dashboard.php" class="<?php echo ($currentPage == 'admin-dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
    <a href="admin-halaman.php" class="<?php echo ($currentPage == 'admin-halaman.php') ? 'active' : ''; ?>">Pengaturan Halaman</a>
    <a href="admin-tim.php" class="<?php echo ($currentPage == 'admin-tim.php') ? 'active' : ''; ?>">Pengaturan TIM</a>
    <a href="admin-produk.php" class="<?php echo ($currentPage == 'admin-produk.php') ? 'active' : ''; ?>">Pengaturan Produk</a>
    <a href="admin-chat.php" class="<?php echo ($currentPage == 'admin-chat.php') ? 'active' : ''; ?>">Chat</a>
    <a href="logout.php" class="logout">Logout</a>
</div>
