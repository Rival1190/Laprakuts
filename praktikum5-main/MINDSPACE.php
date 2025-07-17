<?php
$productsFile = 'proyek-admin-php/products.json';

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



// !!! TAMBAHKAN DUA BARIS INI UNTUK DEBUG !!!

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MINDSPACE with Tailwind CSS</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    /* Sedikit kustomisasi di atas Tailwind */
    body {
      font-family: 'Inter', sans-serif;
      scroll-behavior: smooth;
    }
    @keyframes new-comment-animation {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .new-comment {
      animation: new-comment-animation 0.5s ease-out;
    }
  </style>
</head>
<body class="bg-gray-50">

  <nav id="navbar" class="bg-purple-800 sticky top-0 z-50 transition-all duration-300">
    <div class="container mx-auto px-4">
      <div class="flex justify-between items-center py-3">
        <div class="flex items-center space-x-3">
          <a href="#" class="text-white text-2xl font-bold">MINDSPACE</a>
          <img src="logo-Photoroom-Photoroom.png" alt="Mindspace Logo" class="w-12 h-12">
        </div>
        
        <div class="hidden md:flex items-center space-x-6">
          <a href="http://localhost/1_UTS%20WEB/praktikum5-main/proyek-admin-php/login.php" target="_blank" class="text-white italic hover:text-purple-200">Chat Dokter</a>
          <a href="Tesdepresi.html" class="text-white italic hover:text-purple-200">Cek Kesehatan</a>
          <a href="http://localhost/1_UTS%20WEB/praktikum5-main/proyek-admin-php/login.php" class="bg-white text-purple-800 font-bold py-2 px-4 rounded-lg hover:bg-purple-100 transition duration-300">Login</a>
        </div>

        <div class="md:hidden">
          <button id="mobile-menu-button" class="text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
          </button>
        </div>
      </div>
    </div>
    <div id="mobile-menu" class="hidden md:hidden px-4 pt-2 pb-4 space-y-2">
      <a href="http://localhost/1_UTS%20WEB/praktikum5-main/proyek-admin-php/login.php" target="_blank" class="block text-white italic hover:text-purple-200">Chat Dokter</a>
      <a href="Tesdepresi.html" class="block text-white italic hover:text-purple-200">Cek Kesehatan</a>
      <a href="http://localhost/1_UTS%20WEB/praktikum5-main/proyek-admin-php/login.php" class="block bg-white text-purple-800 font-bold py-2 px-4 rounded-lg hover:bg-purple-100 transition duration-300 text-center mt-2">Login</a>
    </div>
  </nav>

  <section id="jumbo" class="py-12" data-aos="fade-up">
    <div class="container mx-auto px-4">
      <div class="bg-purple-50 rounded-2xl p-8">
        <div class="grid md:grid-cols-2 gap-8 items-center">
          <div class="text-center md:text-left">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-4">Solusi Mental di Tanganmu</h2>
            <img src="https://www.pngitem.com/pimgs/m/512-5121238_doctor-logo-cliparthot-of-the-more-and-animasi.png" class="mx-auto md:mx-0 w-3/4 max-w-xs" alt="Doctor Illustration">
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white p-4 rounded-xl shadow-md flex items-start space-x-4" data-aos="fade-up">
              <div class="bg-blue-100 p-2 rounded-full"><img src="chat-left-text.svg" class="w-6 h-6" alt="Chat Icon"></div>
              <div>
                <p class="font-bold"><a href="http://localhost/1_UTS%20WEB/praktikum5-main/proyek-admin-php/login.php" class="hover:underline">Chat Dengan Dokter</a></p>
                <small class="text-gray-500">50+ spesialis tersedia 24 jam</small>
              </div>
            </div>
             <div class="bg-white p-4 rounded-xl shadow-md flex items-start space-x-4" data-aos="fade-up" data-aos-delay="100">
              <div class="bg-green-100 p-2 rounded-full"><img src="shop.svg" class="w-6 h-6" alt="Shop Icon"></div>
              <div>
                <p class="font-bold"><a href="https://shopee.co.id/search?keyword=toko%20pisikologi" class="hover:underline">Toko Psikolog</a></p>
                <small class="text-gray-500">Pahami & pelajari psikologi</small>
              </div>
            </div>
             <div class="bg-white p-4 rounded-xl shadow-md flex items-start space-x-4 sm:col-span-2" data-aos="fade-up" data-aos-delay="200">
              <div class="bg-red-100 p-2 rounded-full"><img src="hospital.svg" class="w-6 h-6" alt="Clinic Icon"></div>
              <div>
                <p class="font-bold"><a href="https://www.google.com/maps/search/kelinik/@-7.7421683,110.4142924,15z/data=!3m1!4b1?entry=ttu&g_ep=EgoyMDI1MDYzMC4wIKXMDSoASAFQAw%3D%3D" class="hover:underline">Klinik Terdekat</a></p>
                <small class="text-gray-500">Cari klinik terdekat denganmu</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="about" class="py-12 bg-cover bg-center text-white" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url(bg.jpg);" data-aos="fade-up">
    <div class="container mx-auto px-4">
      <h3 class="text-2xl font-bold">VISI</h3>
      <p class="mt-2 max-w-3xl">Menjadi layanan psikolog profesional dan terpercaya yang berperan sebagai mitra utama dalam mendukung kesehatan mental dan kesejahteraan emosional setiap individu.</p>
      <h3 class="text-2xl font-bold mt-6">MISI</h3>
      <ul class="mt-2 space-y-1 list-disc list-inside">
        <li>Memberikan layanan psikologi yang profesional, empatik, dan berbasis ilmiah.</li>
        <li>Meningkatkan kesadaran masyarakat tentang pentingnya kesehatan mental.</li>
        <li>Mendampingi proses pemulihan dan pertumbuhan psikologis klien.</li>
      </ul>
    </div>
  </section>
   <section id="products" class="py-12 bg-white">
   <section id="produk" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-10">
            Produk & Layanan Kami
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            <?php if (empty($allProducts)): ?>
                <p class="col-span-full text-center text-gray-500">
                    Belum ada produk yang tersedia saat ini.
                </p>
            <?php else: ?>
                <?php foreach (array_reverse($allProducts) as $product): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col group transform hover:-translate-y-2 transition-transform duration-300">
                        
                        <div class="relative">
                            <img src="<?php echo 'proyek-admin-php/' . htmlspecialchars($product['image']); ?>"
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 class="w-full h-56 object-cover">
                        </div>
                        
                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 truncate">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </h3>
                            
                            <p class="text-xl font-semibold text-purple-700 my-2">
                                Rp <?php echo number_format($product['price'], 0, ',', '.'); ?>
                            </p>
                            
                            <p class="text-sm text-gray-600 flex-grow">
                                <?php echo htmlspecialchars($product['description']); ?>
                            </p>
                           <a href="proyek-admin-php/pembayaran.php?id=<?php echo $product['id']; ?>" class="mt-4 w-full text-center bg-purple-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors">
    Lihat Detail
</a>
                            
                           
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        </div>
    </div>
</section>
   </section>
  
  <section id="team" class="py-12">
    <div class="container mx-auto px-4">
        <h3 class="text-3xl font-bold mb-8 text-center text-gray-800">Tim Support Kami</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            <div class="text-center">
                <div class="bg-white p-4 rounded-xl shadow-lg transform hover:-translate-y-1 transition-transform duration-300">
                    <img class="w-32 h-32 rounded-full mx-auto object-cover" src="WhatsApp Image 2025-05-12 at 08.20.26_ce4b8e89.jpg" alt="Foto Aditya">
                    <div class="mt-4">
                        <h5 class="font-bold text-lg">Aditya Uzman</h5>
                        <a href="mail.html" class="text-sm text-purple-600 hover:underline">Lihat Profil</a>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-white p-4 rounded-xl shadow-lg transform hover:-translate-y-1 transition-transform duration-300">
                    <img class="w-32 h-32 rounded-full mx-auto object-cover" src="Aqsa.jpeg" alt="Foto Aqsa">
                    <div class="mt-4">
                        <h5 class="font-bold text-lg">Muh. Aidil Aqsa</h5>
                        <a href="aqsa.html" class="text-sm text-purple-600 hover:underline">Lihat Profil</a>
                    </div>
                </div>
            </div>
             <div class="text-center">
                <div class="bg-white p-4 rounded-xl shadow-lg transform hover:-translate-y-1 transition-transform duration-300">
                    <img class="w-32 h-32 rounded-full mx-auto object-cover" src="Foto Rival.jpeg" alt="Foto Rival">
                    <div class="mt-4">
                        <h5 class="font-bold text-lg">Rival Maliyanto</h5>
                        <a href="Rival.html" class="text-sm text-purple-600 hover:underline">Lihat Profil</a>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-white p-4 rounded-xl shadow-lg transform hover:-translate-y-1 transition-transform duration-300">
                    <img class="w-32 h-32 rounded-full mx-auto object-cover" src="Hilman.jpeg" alt="Foto Hilman">
                    <div class="mt-4">
                        <h5 class="font-bold text-lg">Hilman</h5>
                        <a href="Hilman.html" class="text-sm text-purple-600 hover:underline">Lihat Profil</a>
                    </div>
                </div>
            </div>
             <div class="text-center">
                <div class="bg-white p-4 rounded-xl shadow-lg transform hover:-translate-y-1 transition-transform duration-300">
                    <img class="w-32 h-32 rounded-full mx-auto object-cover" src="foto putu.jpeg" alt="Foto Putu">
                    <div class="mt-4">
                        <h5 class="font-bold text-lg">Putu</h5>
                        <a href="Putu.html" class="text-sm text-purple-600 hover:underline">Lihat Profil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>

  <section id="testimonials" class="py-12 bg-gray-100">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Kata Mereka tentang MINDSPACE</h2>
      <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-xl shadow-lg flex flex-col">
          <p class="text-gray-600 italic flex-grow">“Sangat membantu.. malam2 butuh obat, gak perlu keluar rumah”</p>
          <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
            <div class="font-bold text-sm text-purple-800">SAINEM WIYONO</div>
            <a href="http://localhost/1_UTS%20WEB/praktikum5-main/proyek-admin-php/produk.php#" class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold hover:bg-purple-200 transition">Beli Obat</a>
          </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg flex flex-col">
          <p class="text-gray-600 italic flex-grow">“Sangat Helpful!!! Terima kasih yaa, sangat menghemat waktu dan respon dokternya juga baik.”</p>
          <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
            <div class="font-bold text-sm text-purple-800">LINTANG ANINDHITYA I.</div>
            <a href="http://localhost/1_UTS%20WEB/praktikum5-main/proyek-admin-php/login.php" class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold hover:bg-purple-200 transition">Chat Dokter</a>
          </div>
        </div>
      </div>
      
      <div class="bg-white p-8 rounded-xl shadow-lg mt-12 max-w-2xl mx-auto">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Berikan Komentar Anda</h3>
        <form id="comment-form" class="space-y-4">
          <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Anda</label>
            <input type="text" id="nama" name="nama" required placeholder="Contoh: Budi" class="mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
          </div>
          <div>
            <label for="komentar" class="block text-sm font-medium text-gray-700">Komentar</label>
            <textarea id="komentar" name="komentar" rows="4" required placeholder="Tulis sesuatu yang menarik..." class="mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"></textarea>
          </div>
          <div class="text-right">
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">Kirim Komentar</button>
          </div>
        </form>
      </div>

      <div id="comment-section" class="space-y-6 mt-12 max-w-2xl mx-auto">
        </div>
    </div>
  </section>

  <footer class="bg-gray-800 text-gray-300 py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <h4 class="font-bold text-white mb-3">Follow Kami</h4>
                <ul class="space-y-2">
                    <li><a href="https://instagram.com/sayamaiil" class="hover:text-white flex items-center gap-2"><img src="instagram.svg" class="w-5 h-5" alt="Instagram"> Instagram</a></li>
                    <li><a href="https://wa.me/6289515004836" class="hover:text-white flex items-center gap-2"><img src="whatsapp.svg" class="w-5 h-5" alt="Whatsapp"> Whatsapp</a></li>
                    <li><a href="#" class="hover:text-white flex items-center gap-2"><img src="facebook.svg" class="w-5 h-5" alt="Facebook"> Facebook</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-white mb-3">Developer</h4>
                <ul>
                    <li><a href="#" class="hover:text-white">Aditya Uzman Chandra N.</a></li>
                </ul>
            </div>
             <div>
                <h4 class="font-bold text-white mb-3">Kolaborasi</h4>
                <ul class="space-y-2">
                    <li><a href="coomingsoon.html" class="hover:text-white">Karir Tim</a></li>
                    <li><a href="coomingsoon.html" class="hover:text-white">Support</a></li>
                    <li><a href="coomingsoon.html" class="hover:text-white">Kritik & Saran</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-6 text-center text-sm">
            <p>&copy; 2025 MINDSPACE. All rights reserved.</p>
        </div>
    </div>
  </footer>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 800,
      once: true
    });

    // Navbar scroll logic
    let lastScrollTop = 0;
    const navbar = document.getElementById('navbar');
    window.addEventListener("scroll", function() {
      let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      navbar.style.top = (scrollTop > lastScrollTop) ? "-100px" : "0";
      lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });

    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });

    // Comment Section Logic
    document.addEventListener('DOMContentLoaded', () => {
        const comments = [];
        const commentSection = document.getElementById('comment-section');
        const commentForm = document.getElementById('comment-form');

        function displayComments() {
            commentSection.innerHTML = '';
            if (comments.length === 0) {
                commentSection.innerHTML = '<p class="text-center text-gray-500">Belum ada komentar. Jadilah yang pertama!</p>';
                return;
            }
            comments.forEach(comment => {
                const commentElement = document.createElement('div');
                // Gunakan kelas Tailwind
                commentElement.className = 'bg-white p-5 rounded-xl shadow-md flex items-start space-x-4 new-comment';
                commentElement.innerHTML = `
                    <img src="${comment.avatar}" alt="Avatar" class="w-10 h-10 rounded-full flex-shrink-0">
                    <div class="flex-grow">
                        <div class="flex items-center justify-between mb-1">
                            <p class="font-bold text-gray-900">${comment.nama}</p>
                            <p class="text-xs text-gray-500">${comment.waktu}</p>
                        </div>
                        <p class="text-gray-700 leading-relaxed">${comment.komentar}</p>
                    </div>
                `;
                commentSection.appendChild(commentElement);
            });
        }

        commentForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const namaInput = document.getElementById('nama');
            const komentarInput = document.getElementById('komentar');
            const newComment = {
                nama: namaInput.value.trim(),
                komentar: komentarInput.value.trim(),
                waktu: "Baru saja",
                avatar: `https://i.pravatar.cc/40?u=${Date.now()}`
            };
            if (newComment.nama && newComment.komentar) {
                comments.unshift(newComment);
                displayComments();
                commentForm.reset();
            }
        });

        displayComments();
    });
  </script>
</body>
</html>