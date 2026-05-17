const notifLink = document.getElementById('notification-link');
const notifLinkMobile = document.getElementById('notification-link-mobile');

const socket = io(NOTIF.socketUrl);

socket.on('connect', () => {
    socket.emit('join', NOTIF.userId);
});

window.notifSocket = socket;

function updateBadge(count) {
    const text = count > 0 ? `notifications(${count})` : 'notifications';
    if (notifLink) notifLink.textContent = text;
    if (notifLinkMobile) notifLinkMobile.textContent = text;
}

socket.on('notification', () => {
    const source = notifLink || notifLinkMobile;
    const match = source ? source.textContent.match(/\((\d+)\)/) : null;
    const current = match ? parseInt(match[1]) : 0;
    updateBadge(current + 1);
});

(async () => {
    try {
        const res = await fetch('/notifications/count', { cache: 'no-store' });
        if (!res.ok) return;
        const data = await res.json();
        updateBadge(data.count);
    } catch (_) {}
})();
