const express = require('express');
const app = express();
const PORT = 3000;

// Middleware untuk menyajikan file statis dari folder 'public'
app.use(express.static('public'));
// Middleware untuk membaca body JSON dari request
app.use(express.json());

// "Database" sementara untuk menyimpan komentar
let komentar = [
    {
        nama: "Pengguna Pertama",
        isi: "Ini adalah contoh komentar pertama. Keren!",
        waktu: new Date()
    },
    {
        nama: "Admin",
        isi: "Selamat datang di halaman komentar kami.",
        waktu: new Date()
    }
];

// --- API Endpoints ---

// GET: Mengirim semua data komentar
app.get('/api/komentar', (req, res) => {
    res.json(komentar);
});

// POST: Menerima dan menyimpan komentar baru
app.post('/api/komentar', (req, res) => {
    const { nama, isi } = req.body;

    // Validasi sederhana
    if (!nama || !isi) {
        return res.status(400).json({ message: 'Nama dan isi komentar tidak boleh kosong.' });
    }

    const komentarBaru = {
        nama: nama,
        isi: isi,
        waktu: new Date()
    };

    // Tambahkan komentar baru ke awal array agar tampil di paling atas
    komentar.unshift(komentarBaru);

    // Kirim respon sukses bersama dengan data komentar baru
    res.status(201).json(komentarBaru);
});

// Menjalankan server
app.listen(PORT, () => {
    console.log(`Server komentar berjalan di http://localhost:${PORT}`);
});