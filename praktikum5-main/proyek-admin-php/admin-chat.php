<?php
// Memuat koneksi dan memulai sesi
require 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Chat Pengguna</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php 
        // PENTING: Baris ini memuat sidebar sekaligus menjalankan proteksi.
        // Hanya admin yang sudah login yang bisa melewati baris ini.
        require 'admin_sidebar.php'; 
        ?>

        <!-- Bagian Khusus untuk Layout Chat Admin -->
        <div class="main-content" style="display: flex; padding: 0; height: 100vh;">
            <!-- Sidebar untuk Daftar User -->
            <div id="user-list" class="sidebar" style="background-color: #f8f9fa; color: #333; border-right: 1px solid #ddd; display: flex; flex-direction: column;">
                <h3 style="color: #333; padding-bottom: 1rem; margin-bottom: 1rem; border-bottom: 1px solid #eee;">User Chats</h3>
                <div class="user-list-container" style="flex-grow: 1; overflow-y: auto;">
                    <!-- Daftar user akan diisi oleh JavaScript -->
                </div>
            </div>

            <!-- Jendela Chat Utama -->
            <div class="chat-window" style="flex-grow: 1; padding: 0; display: flex; flex-direction: column;">
                <div class="chat-header" style="background-color: #fff; padding: 1.25rem; border-bottom: 1px solid #eee;">
                    <h2 id="chat-title" style="margin: 0; font-size: 1.25rem;">Pilih pengguna untuk memulai chat</h2>
                </div>
                <ul id="messages" class="chat-messages"></ul>
                <div class="chat-input-area">
                    <form id="chat-form" style="display: none; width: 100%;">
                        <input id="message-input" autocomplete="off" placeholder="Tulis balasan..."/>
                        <button id="send-button" type="submit">➤</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    const userListContainer = document.querySelector('.user-list-container');
    const messages = document.getElementById('messages');
    const chatForm = document.getElementById('chat-form');
    const chatTitle = document.getElementById('chat-title');
    const input = document.getElementById('message-input');
    let activeUser = null;

    function addMessage(text, type) {
        const item = document.createElement('li');
        item.textContent = text;
        item.className = 'message-bubble ' + type;
        messages.appendChild(item);
        messages.scrollTop = messages.scrollHeight;
    }

    async function fetchMessages(user) {
        if (!user) return;
        try {
            const res = await fetch(`api.php?action=get_messages&user=${user}`);
            const data = await res.json();
            messages.innerHTML = '';
            data.forEach(msg => {
                const type = msg.pengirim === 'admin' ? 'sent' : 'received';
                addMessage(msg.pesan, type);
            });
        } catch (error) {
            console.error('Gagal mengambil pesan:', error);
        }
    }

    function switchUser(username, element) {
        activeUser = username;
        chatTitle.textContent = `Chat dengan ${username}`;
        chatForm.style.display = 'flex';
        
        document.querySelectorAll('.user-list-container a').forEach(el => el.classList.remove('active'));
        element.classList.add('active');

        fetchMessages(username);
    }
    
    async function loadChatUsers() {
        try {
            const res = await fetch('api.php?action=get_chat_users');
            if (!res.ok) {
                // Jika gagal (misal: error 403 Forbidden karena bukan admin), lempar error
                throw new Error(`Server merespon dengan status ${res.status}`);
            }
            
            const users = await res.json();
            userListContainer.innerHTML = '';
            if (users.length === 0) {
                userListContainer.innerHTML = '<p style="padding: 1rem; color: #888;">Belum ada chat.</p>';
            } else {
                users.forEach(user => {
                    const userItem = document.createElement('a');
                    userItem.href = '#';
                    userItem.textContent = user.pengirim;
                    userItem.dataset.user = user.pengirim;
                    userItem.onclick = (e) => { e.preventDefault(); switchUser(user.pengirim, userItem); };
                    userListContainer.appendChild(userItem);
                });
            }
        } catch (error) {
            console.error('Gagal memuat daftar user:', error);
            userListContainer.innerHTML = '<p style="padding: 1rem; color: red;">Gagal memuat user. Pastikan Anda login sebagai admin.</p>';
        }
    }

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (input.value && activeUser) {
            addMessage(input.value, 'sent');
            const messageToSend = input.value;
            input.value = '';

            await fetch('api.php?action=send_message', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ pesan: messageToSend, penerima: activeUser })
            });
        }
    });

    document.addEventListener('DOMContentLoaded', loadChatUsers);
    
    // Refresh secara berkala
    setInterval(() => {
        loadChatUsers();
        if (activeUser) {
            fetchMessages(activeUser);
        }
    }, 5000);
</script>
</body>
</html>
