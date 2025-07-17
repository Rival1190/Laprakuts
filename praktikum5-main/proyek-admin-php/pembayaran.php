<?php
// --- BAGIAN LOGIKA PHP ---
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Panggil autoloader Composer di awal
// Path ini sudah benar berdasarkan screenshot struktur folder Anda
require_once dirname(__FILE__) . '/vendor/autoload.php';

// Inisialisasi variabel untuk menampung data QRIS
$qris_data = null;

// ===================================================================
// Konfigurasi Midtrans (BAGIAN PALING PENTING)
// ===================================================================
// 1. Login ke Dasbor Midtrans Sandbox, klik "Regenerate", lalu salin kunci BARU Anda.
\Midtrans\Config::$serverKey = 'Mid-server-UNRm7QNS0fpVbuDacOMokyhH';
\Midtrans\Config::$clientKey = 'Mid-client-L9UYe9KiQuTeUfqQ'; // <-- TAMBAHKAN BARIS INI
\Midtrans\Config::$isProduction = false;
// ===================================================================


// Handle jika ada form submission (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari formulir
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $telepon = htmlspecialchars($_POST['telepon']);
    $product_id_post = htmlspecialchars($_POST['product_id']);
    $product_price_post = htmlspecialchars($_POST['product_price']);
    $product_name_post = htmlspecialchars($_POST['product_name']);
    
    // Siapkan parameter untuk Midtrans Core API (metode QRIS)
    $order_id = 'QRIS-' . time();
    $params = [
        'payment_type' => 'qris',
        'transaction_details' => [
            'order_id' => $order_id,
            'gross_amount' => (int)$product_price_post,
        ],
        'customer_details' => [
            'first_name' => $nama,
            'email' => $email,
            'phone' => $telepon,
        ],
        'item_details' => [[
            'id' => $product_id_post,
            'price' => (int)$product_price_post,
            'quantity' => 1,
            'name' => $product_name_post
        ]]
    ];

    // Panggil Midtrans Core API untuk membuat charge
    try {
        $charge = \Midtrans\CoreApi::charge($params);
        $qris_data = $charge;
    } catch (Exception $e) {
        // Tangani jika ada error dari Midtrans
        echo 'Error: ' . $e->getMessage();
        die();
    }
}

// Logika untuk mengambil data produk untuk tampilan awal (GET request)
if (!isset($_GET['id'])) {
    die("ERROR: ID produk tidak ditemukan.");
}
$productId = $_GET['id'];
function getProducts($filePath) {
    if (!file_exists($filePath)) return [];
    return json_decode(file_get_contents($filePath), true) ?: [];
}
$allProducts = getProducts('products.json');
$product = null;
foreach ($allProducts as $p) {
    if ($p['id'] == $productId) {
        $product = $p;
        break;
    }
}
if (!$product) {
    die("ERROR: Produk tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
    <head>
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
</head>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-4 sm:p-8 max-w-4xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Detail Pembayaran</h1>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Ringkasan Pesanan 🛒</h2>
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-64 object-cover rounded-lg mb-4">
                <h3 class="text-2xl font-semibold text-gray-900"><?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="text-gray-500 mt-2"><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between items-center">
                    <span class="text-lg font-medium text-gray-700">Total Pembayaran:</span>
                    <span class="text-2xl font-bold text-purple-700">Rp <?php echo number_format($product['price']); ?></span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <?php if ($qris_data): ?>
                    <div class="text-center">
                        <h2 class="text-2xl font-bold mb-2">Pindai QRIS untuk Membayar</h2>
                        <p class="text-gray-600 mb-4">Gunakan aplikasi E-Wallet atau Mobile Banking Anda.</p>
                       <div id="qrcode" class="mx-auto w-64 h-64 p-2 border rounded-lg bg-white"></div>
                        <div class="mt-4">
                            <p class="text-lg font-medium">Total:</p>
                            <p class="text-3xl font-bold text-purple-700">Rp <?php echo number_format($qris_data->gross_amount); ?></p>
                        </div>
                        <p class="text-xs text-gray-500 mt-6">Kode QR ini akan kedaluwarsa sesuai kebijakan Midtrans.</p>
                    </div>
                <?php else: ?>
                    <h2 class="text-xl font-bold mb-4">Isi Detail Pesanan 📝</h2>
                    <form method="POST" action="" class="space-y-4">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                            <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                        </div>
                        <div>
                            <label for="telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="tel" id="telepon" name="telepon" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                        </div>
                        <button type="submit" class="w-full mt-2 bg-purple-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-purple-700 transition-all duration-300 shadow-lg">
                            Buat Kode QRIS
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    // Cek apakah elemen #qrcode ada di halaman
    const qrcodeElement = document.getElementById("qrcode");
    
    if (qrcodeElement) {
        // Buat objek QRCode baru yang menargetkan div kita
        var qrcode = new QRCode(qrcodeElement, {
            width : 240,
            height : 240,
            correctLevel : QRCode.CorrectLevel.H
        });
        
        // Ambil data teks dari PHP dan buat QR Code-nya
        qrcode.makeCode("<?php echo $qris_data->qr_string; ?>");
    }
</script>

</body>
</html>
</body>
</html>