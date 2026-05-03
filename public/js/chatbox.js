const input = document.getElementById('message-input');
const btn = document.getElementById('send-btn');
const form = document.querySelector('form[action="/sendMessage"]');
const messagesContainer = document.querySelector('.chat-messages');

input.addEventListener('input', () => {
    btn.style.display = input.value.trim() ? 'block' : 'none';
});

const socket = io('http://localhost:3000');

socket.on('connect', () => {
    socket.emit('join', CHAT.currentUserId);
});

socket.on('message', (data) => {
    appendMessage(data.content, 'receiver');
});

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const content = input.value.trim();
    if (!content) return;

    const formData = new FormData(form);

    const res = await fetch('/sendMessage', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData,
    });

    if (!res.ok) return;

    appendMessage(content, 'sender');
    socket.emit('message', {
        recipientId: CHAT.recipientId,
        senderId: CHAT.currentUserId,
        content,
    });

    input.value = '';
    btn.style.display = 'none';
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
});

function appendMessage(content, type) {
    const div = document.createElement('div');
    div.className = type;
    div.textContent = content;
    messagesContainer.appendChild(div);
}
