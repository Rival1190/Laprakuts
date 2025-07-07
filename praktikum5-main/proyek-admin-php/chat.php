<?php
require 'koneksi.php';
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="chat-container">
        <div class="chat-header"><h1>Dokter Online</h1><p>Diawasi oleh kemnkes</p></div>
        <ul id="messages" class="chat-messages"></ul>
        <div class="chat-input-area">
            <form id="chat-form" style="display: flex; width: 100%;">
                <input id="message-input" autocomplete="off" placeholder="Ketik pesan Anda..."/>
                <button id="send-button" type="submit">➤</button>
            </form>
        </div>
        <a href="logout.php" style="text-align:center; padding: 10px; background-color: #f1f1f1; text-decoration: none; color: #333;">Logout</a>
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
        const res = await fetch(`api.php?action=get_messages`);
        const data = await res.json();
        messages.innerHTML = '';
        data.forEach(msg => {
            const type = msg.pengirim === username ? 'sent' : 'received';
            addMessage(msg.pesan, type);
        });
    }

    document.getElementById('chat-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        if (input.value) {
            addMessage(input.value, 'sent');
            const messageToSend = input.value;
            input.value = '';
            await fetch('api.php?action=send_message', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ pesan: messageToSend }) // User tidak perlu mengirim 'penerima', server tahu itu 'admin'
            });
        }
    });

    setInterval(fetchMessages, 3000);
    document.addEventListener('DOMContentLoaded', fetchMessages);
</script>
</body>
</html>
