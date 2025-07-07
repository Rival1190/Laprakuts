<?php require 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Pengaturan TIM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php require 'admin_sidebar.php'; ?>
        <div class="main-content">
            <h2>Pengaturan TIM</h2>
            <form id="form-tim">
                <div class="form-group"><label for="nama">Nama Anggota</label><input type="text" id="nama" required></div>
                <div class="form-group"><label for="tugas">Tugas</label><input type="text" id="tugas" required></div>
                <button type="submit">Tambah Anggota</button>
            </form>
            <table class="data-table">
                <thead><tr><th>ID</th><th>Nama</th><th>Tugas</th></tr></thead>
                <tbody id="daftar-tim"></tbody>
            </table>
        </div>
    </div>
<script>
    const formTim = document.getElementById('form-tim');
    const tbodyDaftarTim = document.getElementById('daftar-tim');
    async function fetchTim() {
        const res = await fetch('api.php?action=get_tim');
        const dataTim = await res.json();
        tbodyDaftarTim.innerHTML = '';
        dataTim.forEach(anggota => {
            tbodyDaftarTim.innerHTML += `<tr><td>${anggota.id}</td><td>${anggota.nama}</td><td>${anggota.tugas}</td></tr>`;
        });
    }
    formTim.addEventListener('submit', async (e) => {
        e.preventDefault();
        await fetch('api.php?action=add_tim', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ nama: document.getElementById('nama').value, tugas: document.getElementById('tugas').value })
        });
        formTim.reset();
        fetchTim();
    });
    document.addEventListener('DOMContentLoaded', fetchTim);
</script>
</body>
</html>
