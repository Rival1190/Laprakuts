// Ganti dengan konfigurasi Firebase proyekmu
const firebaseConfig = {
  apiKey: "API_KEY",
  authDomain: "PROJECT_ID.firebaseapp.com",
  databaseURL: "https://chat-mindspace-default-rtdb.firebaseio.com/",
  projectId: "PROJECT_ID",
  storageBucket: "PROJECT_ID.appspot.com",
  messagingSenderId: "SENDER_ID",
  appId: "APP_ID"
};

// Inisialisasi Firebase
firebase.initializeApp(firebaseConfig);
const db = firebase.database();

// Fungsi kirim pesan
function sendMessage() {
  const input = document.getElementById('user-input');
  const message = input.value.trim();
  if (message === '') return;

  const data = {
    sender: "user", // atau "admin"
    message: message,
    timestamp: Date.now()
  };

  db.ref("messages").push(data);
  input.value = '';
}

// Tampilkan pesan secara realtime
db.ref("messages").on("child_added", snapshot => {
  const data = snapshot.val();
  const chatBox = document.getElementById('chat-box');

  const msgDiv = document.createElement('div');
  msgDiv.classList.add('message');
  msgDiv.classList.add(data.sender === 'admin' ? 'admin-message' : 'user-message');
  msgDiv.textContent = data.message;

  chatBox.appendChild(msgDiv);
  chatBox.scrollTop = chatBox.scrollHeight;
});
