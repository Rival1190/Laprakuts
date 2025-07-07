<?php
require 'koneksi.php';
header('Content-Type: application/json');

// Menampilkan error PHP untuk debugging (bisa dihapus di versi produksi)
ini_set('display_errors', 1);
error_reporting(E_ALL);

$action = $_GET['action'] ?? '';
$user_session = $_SESSION['user'] ?? null;

// --- PENGECEKAN SESI DI AWAL ---
// Untuk semua aksi yang butuh login, pastikan sesi ada.
$protected_actions = ['get_chat_users', 'get_messages', 'send_message', 'get_tim', 'add_tim'];
if (in_array($action, $protected_actions) && !$user_session) {
    http_response_code(401); // Unauthorized
    echo json_encode(['message' => 'Sesi tidak ditemukan. Silakan login kembali.']);
    exit;
}

// Fungsi untuk memeriksa apakah pengguna adalah admin
function isAdmin() {
    global $user_session;
    if (!$user_session['is_admin']) {
        http_response_code(403); // Forbidden
        echo json_encode(['message' => 'Akses ditolak. Hanya untuk admin.']);
        exit;
    }
}

switch ($action) {
    // --- API UNTUK CHAT (SUDAH DIPERBAIKI) ---

    case 'get_chat_users':
        isAdmin();
        $result = $koneksi->query("SELECT DISTINCT pengirim FROM chat_messages WHERE penerima = 'admin' ORDER BY pengirim ASC");
        
        // --- PENGECEKAN ERROR DATABASE ---
        if (!$result) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Query database untuk mengambil user gagal: ' . $koneksi->error]);
            exit;
        }
        
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        break;

    case 'get_messages':
        $target_user = $user_session['is_admin'] ? ($_GET['user'] ?? null) : $user_session['username'];
        
        if (!$target_user) {
            echo json_encode([]);
            exit;
        }

        $query = "SELECT * FROM chat_messages 
                  WHERE (pengirim = ? AND penerima = 'admin') 
                     OR (pengirim = 'admin' AND penerima = ?) 
                  ORDER BY waktu ASC";
        
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("ss", $target_user, $target_user);
        $stmt->execute();
        $result = $stmt->get_result();
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        break;

    case 'send_message':
        $data = json_decode(file_get_contents('php://input'), true);
        $pengirim = $user_session['username'];
        $penerima = $user_session['is_admin'] ? $data['penerima'] : 'admin';
        
        $stmt = $koneksi->prepare("INSERT INTO chat_messages (pengirim, penerima, pesan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $pengirim, $penerima, $data['pesan']);
        $stmt->execute();
        echo json_encode(['message' => 'Pesan terkirim.']);
        break;

    // --- API LAINNYA (Contoh) ---
    case 'get_tim':
        isAdmin();
        $result = $koneksi->query("SELECT * FROM tim");
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['message' => 'Aksi tidak ditemukan.']);
        break;
}

$koneksi->close();
?>
