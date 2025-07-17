<?php
// Lokasi file untuk membaca data produk
$productsFile = 'products.json';

// Fungsi untuk membaca produk dari file JSON
function getProducts($filePath) {
    if (!file_exists($filePath)) {
        return [];
    }
    $json_data = file_get_contents($filePath);
    return json_decode($json_data, true) ?: [];
}

// Ambil semua produk untuk ditampilkan
$allProducts = getProducts($productsFile);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Kami - Koleksi Terbaru</title>
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

    <!-- Header -->
    <header class="bg-gray-800/80 backdrop-blur-lg sticky top-0 z-50 border-b border-gray-700">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500">
                TokoKita
            </a>
            <div class="flex items-center space-x-4">
               
                    <i class="fas fa-shopping-cart"></i>
                </button>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto p-4 sm:p-8">
        <!-- Hero Section -->
        <section class="text-center py-12 sm:py-20">
            <h1 class="text-4xl sm:text-6xl font-extrabold text-white leading-tight mb-4">
                Temukan Gaya <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500">Unikmu</span>
            </h1>
            <p class="text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto">
                Jelajahi koleksi terbaru kami yang dirancang khusus untuk mengekspresikan kepribadian Anda.
            </p>
        </section>

        <!-- Product Grid -->
        <section>
            <h2 class="text-3xl font-bold text-white mb-8">Koleksi Kami</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if (empty($allProducts)): ?>
                    <p class="text-gray-400 col-span-full text-center text-lg">Saat ini belum ada produk yang tersedia. Silakan kembali lagi nanti!</p>
                <?php else: ?>
                    <?php foreach ($allProducts as $product): ?>
                        <div class="bg-gray-800 rounded-2xl overflow-hidden shadow-lg transform hover:-translate-y-2 transition-transform duration-300 ease-in-out group">
                            <div class="relative">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="w-full h-72 object-cover"
                                     onerror="this.onerror=null;this.src='https://placehold.co/600x600/1e293b/ffffff?text=Gambar+Rusak';"
                                >
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300"></div>
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-xl text-white mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="text-gray-400 text-sm mb-4 h-10"><?php echo htmlspecialchars($product['description']); ?></p>
                                <div class="flex justify-between items-center">
                                    <p class="text-purple-400 font-extrabold text-2xl">
                                        Rp<?php echo number_format($product['price'], 0, ',', '.'); ?>
                                    </p>
                                    <button class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                        Beli
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 mt-16 border-t border-gray-700">
        <div class="container mx-auto py-8 px-6 text-center text-gray-400">
            <p>&copy; <?php echo date("Y"); ?> TokoKita. Semua Hak Cipta Dilindungi.</p>
        </div>
    </footer>

</body>
</html>
