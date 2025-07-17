<?php
// Lokasi file untuk menyimpan data produk
$productsFile = 'products.json';

// Inisialisasi pesan untuk ditampilkan ke user
$successMessage = '';
$errorMessage = '';

// Fungsi untuk membaca produk dari file JSON
function getProducts($filePath) {
    if (!file_exists($filePath)) {
        return [];
    }
    $json_data = file_get_contents($filePath);
    return json_decode($json_data, true) ?: [];
}

// Fungsi untuk menyimpan produk ke file JSON
function saveProducts($filePath, $products) {
    file_put_contents($filePath, json_encode($products, JSON_PRETTY_PRINT));
}

// --- LOGIKA PEMROSESAN FORM ---

// Cek jika ada aksi 'delete'
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $productIdToDelete = $_POST['product_id'];
    $products = getProducts($productsFile);
    $productFound = false;
    
    // Cari dan hapus produk
    foreach ($products as $index => $product) {
        if ($product['id'] == $productIdToDelete) {
            // Hapus file gambar jika ada
            if (file_exists($product['image'])) {
                unlink($product['image']);
            }
            // Hapus produk dari array
            unset($products[$index]);
            $productFound = true;
            break;
        }
    }

    if ($productFound) {
        // Simpan array produk yang sudah diperbarui (re-index array)
        saveProducts($productsFile, array_values($products));
        $successMessage = 'Produk berhasil dihapus!';
    } else {
        $errorMessage = 'Produk tidak ditemukan.';
    }
}
// Cek jika ada aksi 'add' (tambah produk)
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dan lakukan sanitasi dasar
    $productName = htmlspecialchars(trim($_POST['product_name']));
    $productPrice = filter_var(trim($_POST['product_price']), FILTER_SANITIZE_NUMBER_INT);
    $productDescription = htmlspecialchars(trim($_POST['product_description']));

    // Validasi input
    if (empty($productName) || empty($productPrice) || empty($productDescription) || !isset($_FILES['product_image']) || $_FILES['product_image']['error'] != 0) {
        $errorMessage = 'Semua field wajib diisi dan gambar harus diunggah.';
    } else {
        // Proses upload gambar
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $imageFileType = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));
        $uniqueFileName = uniqid('product_', true) . '.' . $imageFileType;
        $targetFile = $targetDir . $uniqueFileName;
        
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($imageFileType, $allowedTypes)) {
            $errorMessage = 'Maaf, hanya file JPG, JPEG, PNG, WEBP & GIF yang diizinkan.';
        } else {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
                $newProduct = [
                    'id' => uniqid(),
                    'name' => $productName,
                    'price' => (int)$productPrice,
                    'description' => $productDescription,
                    'image' => $targetFile
                ];

                $products = getProducts($productsFile);
                $products[] = $newProduct;
                saveProducts($productsFile, $products);
                
                $successMessage = 'Produk berhasil ditambahkan!';
            } else {
                $errorMessage = 'Maaf, terjadi kesalahan saat mengunggah file Anda.';
            }
        }
    }
}

// Ambil semua produk untuk ditampilkan di halaman
$allProducts = getProducts($productsFile);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manajemen Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-gray-200">

    <div class="flex">
        
        <!-- Sidebar -->
        <?php // include 'admin_sidebar.php'; // Anda bisa uncomment ini untuk memasukkan sidebar ?>
        <!-- Placeholder untuk Sidebar jika file tidak ada -->
        <aside class="w-64 min-h-screen bg-gray-800 p-4 hidden md:block">
            <h1 class="text-white text-2xl font-bold mb-8">Admin</h1>
            <nav class="flex flex-col space-y-2">
                
                <a href="#" class="bg-purple-600 text-white px-3 py-2 rounded-md">Produk</a>
              
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 sm:p-8">
            <div class="container mx-auto max-w-5xl">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500 mb-8">
                    Manajemen Produk
                </h1>

                <!-- Form Tambah Produk -->
                <div class="bg-gray-800/70 backdrop-blur-sm border border-gray-700 rounded-2xl p-6 sm:p-8 shadow-2xl mb-12">
                    <h2 class="text-2xl font-bold text-white mb-6">Tambah Produk Baru</h2>

                    <!-- Tampilkan pesan sukses atau error -->
                    <?php if ($successMessage): ?>
                        <div class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg mb-6" role="alert">
                            <p><?php echo $successMessage; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($errorMessage): ?>
                        <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg mb-6" role="alert">
                            <p><?php echo $errorMessage; ?></p>
                        </div>
                    <?php endif; ?>

                    <form action="admin-produk.php" method="post" enctype="multipart/form-data" class="space-y-6">
                        <div>
                            <label for="product_name" class="block mb-2 text-sm font-medium text-gray-300">Nama Produk</label>
                            <input type="text" name="product_name" id="product_name" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" placeholder="Contoh: Astral Hoodie" required>
                        </div>
                        <div>
                            <label for="product_price" class="block mb-2 text-sm font-medium text-gray-300">Harga (Rp)</label>
                            <input type="number" name="product_price" id="product_price" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" placeholder="Contoh: 349000" required>
                        </div>
                        <div>
                            <label for="product_description" class="block mb-2 text-sm font-medium text-gray-300">Deskripsi Produk</label>
                            <textarea name="product_description" id="product_description" rows="4" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" placeholder="Jelaskan tentang produk Anda..." required></textarea>
                        </div>
                        <div>
                            <label for="product_image" class="block mb-2 text-sm font-medium text-gray-300">Gambar Produk</label>
                            <input type="file" name="product_image" id="product_image" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700" required>
                        </div>
                        <button type="submit" class="w-full py-3 px-6 bg-gradient-to-r from-purple-600 to-pink-500 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-purple-400/50 transition-all duration-300">
                            Simpan Produk
                        </button>
                    </form>
                </div>

                <!-- Daftar Produk yang Sudah Ada -->
                <div class="bg-gray-800/70 backdrop-blur-sm border border-gray-700 rounded-2xl p-6 sm:p-8 shadow-2xl">
                    <h2 class="text-2xl font-bold text-white mb-6">Daftar Produk</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php if (empty($allProducts)): ?>
                            <p class="text-gray-400 col-span-full text-center">Belum ada produk yang ditambahkan.</p>
                        <?php else: ?>
                            <?php foreach (array_reverse($allProducts) as $product): ?>
                                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-md flex flex-col">
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-48 object-cover">
                                    <div class="p-4 flex flex-col flex-grow">
                                        <h3 class="font-bold text-lg text-white"><?php echo htmlspecialchars($product['name']); ?></h3>
                                        <p class="text-purple-400 font-semibold mt-1">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                                        <p class="text-gray-400 text-sm mt-2 flex-grow"><?php echo htmlspecialchars($product['description']); ?></p>
                                        
                                        <!-- Tombol Aksi -->
                                        <div class="mt-4 pt-4 border-t border-gray-700 flex items-center gap-2">
                                            <button class="flex-1 text-sm bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded-md transition-colors">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </button>
                                            <form action="admin-produk.php" method="post" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <button type="submit" class="w-full text-sm bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded-md transition-colors">
                                                    <i class="fas fa-trash mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
