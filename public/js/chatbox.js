const input = document.getElementById('message-input');
const btn = document.getElementById('send-btn');
const form = document.querySelector('form[action="/sendMessage"]');
const messagesContainer = document.querySelector('.chat-messages');

input.addEventListener('input', () => {
    btn.disabled = !input.value.trim();
});

const chatSocket = io(CHAT.socketUrl);

chatSocket.on('connect', () => {
    chatSocket.emit('join', CHAT.currentUserId);
});

chatSocket.on('message', (data) => {
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
    chatSocket.emit('message', {
        recipientId: CHAT.recipientId,
        senderId: CHAT.currentUserId,
        content,
    });

    input.value = '';
    btn.disabled = true;
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
});

function appendMessage(content, type) {
    const div = document.createElement('div');
    div.className = type;
    div.textContent = content;
    messagesContainer.appendChild(div);
}
