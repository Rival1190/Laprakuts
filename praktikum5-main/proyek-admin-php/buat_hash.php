<?php
// Ganti teks di bawah ini dengan password baru yang Anda inginkan
$passwordBaru = 'rahasia123'; 

$hash = password_hash($passwordBaru, PASSWORD_BCRYPT);

echo "Gunakan hash di bawah ini untuk dimasukkan ke database:<br><br>";
echo "<strong>" . $hash . "</strong>";
?>