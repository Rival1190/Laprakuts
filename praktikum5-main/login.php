<?php
$email = $_POST['email'];
$password = $_POST['password'];

$userFile = 'users.json';
$user = json_decode(file_get_contents($userFile), true);

$found = false;

foreach ($user as $user) {
    if ($user['email'] === $email && $user['password'] === $password) {
        $found = true;
        
    }
}

if ($found) {
    echo "Login berhasil!";
    // Simpan session di sini kalau mau
} else {
    echo "Email atau password salah!";
}
?>
