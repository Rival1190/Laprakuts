<?php
require 'koneksi.php';
// Proteksi halaman, hanya user biasa yang bisa akses
if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin']) {
    header('Location: login.php');
    exit;
}
$username = $_SESSION['user']['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Chat Dokter Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- SEMUA CSS DIMASUKKAN LANGSUNG DI SINI -->
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background: #e9f2ff; /* Latar belakang biru muda lembut */
            margin: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* --- USER CHAT STYLES (DIPERBARUI) --- */
        .chat-container {
            width: 100%;
            max-width: 450px;
            height: 90vh;
            max-height: 750px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .chat-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 10;
            flex-shrink: 0;
        }
        .chat-header h1 { margin: 0; font-size: 1.5rem; font-weight: 700; }
        .chat-header p { margin: 0; font-size: 0.8rem; opacity: 0.9; }

        .chat-messages {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .message-bubble {
            padding: 12px 18px;
            border-radius: 20px;
            max-width: 75%;
            line-height: 1.5;
            word-wrap: break-word;
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .message-bubble.received {
            background-color: #f1f0f0;
            color: #333;
            align-self: flex-start;
            border-bottom-left-radius: 5px;
        }
        .message-bubble.sent {
            background-color: #667eea;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
        }

        .chat-input-area {
            display: flex;
            align-items: center;
            padding: 15px;
            border-top: 1px solid #eee;
            background-color: #fff;
            flex-shrink: 0;
        }
        #message-input {
            flex-grow: 1;
            border: none;
            background-color: #f1f0f0;
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 1rem;
            margin-right: 10px;
        }
        #message-input:focus { outline: none; }
        #send-button {
            background-color: #667eea;
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s, transform 0.2s;
        }
        #send-button:hover { background-color: #5a67d8; transform: scale(1.1); }
        .chat-logout-link { text-align:center; padding: 12px; background-color: #f8f9fa; text-decoration: none; color: #555; font-weight: 600; flex-shrink: 0; }
        .chat-logout-link:hover { background-color: #e9ecef; }
    </style>
</head>
<body>
    <div class="chat-container">
        <!-- Header Chat -->
        <div class="chat-header">
            <h1>Dokter Online</h1>
            <p>Diawasi oleh kemnkes</p>
        </div>

        <!-- Area Pesan -->
        <ul id="messages" class="chat-messages">
            <!-- Pesan dari database akan muncul di sini -->
        </ul>

        <!-- Area Input -->
        <div class="chat-input-area">
            <form id="chat-form" style="display: flex; width: 100%;">
                <input id="message-input" autocomplete="off" placeholder="Ketik pesan Anda..."/>
                <button id="send-button" type="submit">➤</button>
            </form>
        </div>
        
        <!-- Tombol Logout -->
        <a href="logout.php" class="chat-logout-link">Logout</a>
    </div>

<script>
    const messages = document.getElementById('messages');
    const input = document.getElementById('message-input');
    const username = "<?php echo $username; ?>";

    function addMessage(text, type) {
        const item = document.createElement('li');
        item.textContent = text;
        item.className = 'message-bubble ' + type;
        messages.appendChild(item);
        messages.scrollTop = messages.scrollHeight;
    }

    async function fetchMessages() {
        try {
            const res = await fetch(`api.php?action=get_messages`);
            const data = await res.json();
            
            const isScrolledToBottom = messages.scrollHeight - messages.clientHeight <= messages.scrollTop + 1;

            messages.innerHTML = '';
            if (data.length === 0) {
                addMessage('Halo! Ada yang bisa kami bantu?', 'received');
            } else {
                data.forEach(msg => {
                    const type = msg.pengirim === username ? 'sent' : 'received';
                    addMessage(msg.pesan, type);
                });
            }

            if (isScrolledToBottom) {
                messages.scrollTop = messages.scrollHeight;
            }
        } catch (error) {
            console.error("Gagal mengambil pesan:", error);
        }
    }

    document.getElementById('chat-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        if (input.value.trim()) {
            const messageToSend = input.value;
            input.value = '';
            
            addMessage(messageToSend, 'sent');
            
            await fetch('api.php?action=send_message', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ pesan: messageToSend })
            });
        }
    });

    setInterval(fetchMessages, 3000);
    document.addEventListener('DOMContentLoaded', fetchMessages);
</script>
</body>
</html>
