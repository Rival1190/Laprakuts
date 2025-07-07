<?php
// Detail koneksi database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; // Password default XAMPP biasanya kosong
$db_name = 'proyek_admin';

// Membuat koneksi
$koneksi = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}

// Memulai sesi PHP di satu tempat agar konsisten
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
