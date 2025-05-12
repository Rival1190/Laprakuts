<?php
$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];

$userFile = 'user.json';

// Ambil data user
$user = json_decode(file_get_contents($userFile), true);

// Cek apakah email sudah ada
foreach ($user as $user) {
    if ($user['email'] === $email) {
        die("Email sudah terdaftar!");
    }
    break;
}

// Simpan user baru
$user[] = [
    "nama" => $nama,
    "email" => $email,
    "password" => $password // Belum di-hash, hanya untuk demo!
];

// Simpan ke file
file_put_contents($userFile, json_encode($user, JSON_PRETTY_PRINT));
echo "Pendaftaran berhasil!";
?>
