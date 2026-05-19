const messageLink = document.getElementById('message-link');
const messageLinkMobile = document.getElementById('message-link-mobile');

const messageSocket = io(MESSAGE.socketUrl);

messageSocket.on('connect', () => {
    messageSocket.emit('join', MESSAGE.userId);
});

function updateBadge(count) {
    const text = count > 0 ? `messages(${count})` : 'messages';
    const color = count > 0 ? '#fffc6d' : '';
    const fontWeight = count > 0 ? 'bold' : 'normal';
    if (messageLink) {
        messageLink.textContent = text;
        messageLink.style.color = color;
        messageLink.style.fontWeight = fontWeight;
    }

    if (messageLinkMobile) {
        messageLinkMobile.textContent = text;
        messageLinkMobile.style.color = color;
        messageLinkMobile.style.fontWeight = fontWeight;
    }
}

messageSocket.on('message', () => {
    const source = messageLink || messageLinkMobile;
    const match = source ? source.textContent.match(/\((\d+)\)/) : null; // look for a number inside the parenthesis
    const current = match ? parseInt(match[1]) : 0; // grab the current count
    updateBadge(current + 1);
});

(async () => {
    try {
        // dont cache use the data from the server
        const res = await fetch('/messages/count', {cache: 'no-store'});

        if (!res.ok) return;
        const data = await res.json();
        updateBadge(data.count);
    } catch (_) {} // silents the error
})();
