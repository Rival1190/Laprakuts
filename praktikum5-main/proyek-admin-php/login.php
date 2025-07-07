<?php
// Memulai sesi di baris paling atas adalah praktik terbaik.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'koneksi.php';

// Jika pengguna sudah login, langsung arahkan.
if (isset($_SESSION['user'])) {
    $redirect_url = $_SESSION['user']['is_admin'] ? 'admin-dashboard.php' : 'chat.php';
    header('Location: ' . $redirect_url);
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Logika untuk Login
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // --- FITUR RESET PASSWORD DARURAT ---
        // Jika Anda login dengan password "reset123", kode ini akan memperbaiki database.
        if ($username === 'admin' && $password === 'reset123') {
            
            // Ini adalah hash yang BENAR untuk password "admin123"
            $correct_hash = '$2a$12$UaHkqjlrhVxdQpkDF8WgXeg05QXdCowc5Y2cP4bArw43ChFRUz2HK';
            
            // Perbarui password di database secara paksa
            $update_stmt = $koneksi->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
            $update_stmt->bind_param("s", $correct_hash);
            $update_stmt->execute();
            
            $message = '<p class="message success">PASSWORD ADMIN BERHASIL DIRESET! Silakan login kembali dengan password "admin123".</p>';

        } else {
            // Logika login normal
            $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    session_regenerate_id(true); 
                    $_SESSION['user'] = ['username' => $user['username'], 'is_admin' => (bool)$user['is_admin']];
                    $redirect_url = $user['is_admin'] ? 'admin-dashboard.php' : 'chat.php';
                    header('Location: ' . $redirect_url);
                    exit;
                }
            }
            $message = '<p class="message error">Username atau password salah.</p>';
        }
    }
    // ... (logika registrasi tetap sama)
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registrasi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container login-container">
        
        <?php echo $message; ?>

        <div id="login-view">
            <h2>Login</h2>
            <form id="loginForm" method="POST" action="login.php">
                <div class="form-group">
                    <label for="loginUsername">Username</label>
                    <input type="text" id="loginUsername" name="username" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" id="loginPassword" name="password" required>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
            <p class="toggle-form" onclick="toggleView()">Belum punya akun? Registrasi</p>
        </div>

        <div id="register-view" style="display:none;">
            <h2>Registrasi</h2>
            <form id="registerForm" method="POST" action="login.php">
                <div class="form-group">
                    <label for="registerUsername">Username</label>
                    <input type="text" id="registerUsername" name="username" required>
                </div>
                <div class="form-group">
                    <label for="registerPassword">Password</label>
                    <input type="password" id="registerPassword" name="password" required>
                </div>
                <button type="submit" name="register">Registrasi</button>
            </form>
            <p class="toggle-form" onclick="toggleView()">Sudah punya akun? Login</p>
        </div>
    </div>

<script>
    const loginView = document.getElementById('login-view');
    const registerView = document.getElementById('register-view');

    function toggleView() {
        loginView.style.display = loginView.style.display === 'none' ? 'block' : 'none';
        registerView.style.display = registerView.style.display === 'none' ? 'block' : 'none';
    }
</script>

</body>
</html>
