// =================================================================
//                      IMPOR LIBRARY & INISIALISASI
// =================================================================
const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const bcrypt = require('bcryptjs');
const session = require('express-session');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);
const PORT = 3000;

// =================================================================
//                         DATABASE SEMENTARA
// =================================================================
const users = [];
let tim = [];
let produk = [];
let halaman = {
    home: { judul: 'Selamat Datang!', konten: 'Ini adalah halaman utama.' },
    tentang: { judul: 'Tentang Kami', konten: 'Kami adalah perusahaan yang luar biasa.' }
};
let timIdCounter = 1;
let produkIdCounter = 1;

// Buat user admin secara manual
bcrypt.hash('admin123', 10).then(hashedPassword => {
    users.push({ username: 'admin', password: hashedPassword, isAdmin: true });
});

// =================================================================
//                              MIDDLEWARE
// =================================================================
app.use(express.static('public'));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

const sessionMiddleware = session({
    secret: 'kunci-rahasia-super-aman',
    resave: false,
    saveUninitialized: true,
});
app.use(sessionMiddleware);
io.use((socket, next) => sessionMiddleware(socket.request, {}, next));

// Middleware untuk proteksi rute admin
function isAdmin(req, res, next) {
    if (req.session.user && req.session.user.isAdmin) {
        return next();
    }
    res.status(403).json({ message: 'Akses ditolak. Hanya untuk admin.' });
}

// =================================================================
//                               RUTE HTTP
// =================================================================

// Rute utama
app.get('/', (req, res) => {
    if (req.session.user) {
        res.redirect(req.session.user.isAdmin ? '/admin-dashboard.html' : '/chat.html');
    } else {
        res.redirect('/login.html');
    }
});

// --- Rute Autentikasi ---
app.post('/register', async (req, res) => {
    const { username, password } = req.body;
    if (!username || !password) return res.status(400).json({ message: 'Username & password diperlukan.' });
    if (users.find(u => u.username === username)) return res.status(409).json({ message: 'Username sudah ada.' });

    const hashedPassword = await bcrypt.hash(password, 10);
    users.push({ username, password: hashedPassword, isAdmin: false });
    res.status(201).json({ message: 'Registrasi berhasil!' });
});

app.post('/login', async (req, res) => {
    const { username, password } = req.body;
    const user = users.find(u => u.username === username);

    if (user && (await bcrypt.compare(password, user.password))) {
        req.session.user = { username: user.username, isAdmin: user.isAdmin };
        res.status(200).json({ message: 'Login berhasil!', user: req.session.user });
    } else {
        res.status(401).json({ message: 'Username atau password salah.' });
    }
});

app.get('/logout', (req, res) => {
    req.session.destroy(err => {
        if (err) return res.redirect('/');
        res.clearCookie('connect.sid');
        res.redirect('/login.html');
    });
});

// --- Rute API Admin (Diproteksi) ---
// Halaman
app.get('/api/halaman', isAdmin, (req, res) => res.json(halaman));
app.post('/api/halaman', isAdmin, (req, res) => {
    const { home, tentang } = req.body;
    if (home) halaman.home = home;
    if (tentang) halaman.tentang = tentang;
    res.status(200).json({ message: 'Konten halaman berhasil diperbarui.' });
});

// Tim
app.get('/api/tim', isAdmin, (req, res) => res.json(tim));
app.post('/api/tim', isAdmin, (req, res) => {
    const { nama, tugas } = req.body;
    const anggotaBaru = { id: timIdCounter++, nama, tugas };
    tim.push(anggotaBaru);
    res.status(201).json(anggotaBaru);
});

// Produk
app.get('/api/produk', isAdmin, (req, res) => res.json(produk));
app.post('/api/produk', isAdmin, (req, res) => {
    const { nama, deskripsi } = req.body;
    const produkBaru = { id: produkIdCounter++, nama, deskripsi };
    produk.push(produkBaru);
    res.status(201).json(produkBaru);
});

// =================================================================
//                         LOGIKA CHAT SOCKET.IO
// =================================================================
let adminSocket = null;
const userSockets = {};

io.on('connection', (socket) => {
    const session = socket.request.session;
    if (!session || !session.user) return socket.disconnect();
    
    const { username, isAdmin } = session.user;
    console.log(`Pengguna terhubung: ${username}`);

    if (isAdmin) {
        adminSocket = socket;
        socket.emit('user-list', Object.keys(userSockets));
    } else {
        userSockets[username] = socket;
        if (adminSocket) adminSocket.emit('new-user', username);
    }

    socket.on('user message', (msg) => {
        if (adminSocket) adminSocket.emit('message to admin', { from: username, text: msg });
    });

    socket.on('admin message', (data) => {
        const targetSocket = userSockets[data.to];
        if (targetSocket) targetSocket.emit('message to user', { from: 'Admin', text: data.text });
    });

    socket.on('disconnect', () => {
        console.log(`Pengguna terputus: ${username}`);
        if (isAdmin) {
            adminSocket = null;
        } else {
            delete userSockets[username];
            if (adminSocket) adminSocket.emit('user-disconnected', username);
        }
    });
});

// =================================================================
//                          MENJALANKAN SERVER
// =================================================================
server.listen(PORT, () => {
    console.log(`🚀 Server berjalan di http://localhost:${PORT}`);
});