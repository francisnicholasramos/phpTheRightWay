const notifLink = document.getElementById('notification-link');
const notifLinkMobile = document.getElementById('notification-link-mobile');

const socket = io(NOTIF.socketUrl);

socket.on('connect', () => {
    socket.emit('join', NOTIF.userId);
});

window.notifSocket = socket;

function updateNotifBadge(count) {
    const text = count > 0 ? `notifications(${count})` : 'notifications';
    const color = count > 0 ? '#fffc6d' : '';
    const fontWeight = count > 0 ? 'bold' : 'normal';
    if (notifLink) {
        notifLink.textContent = text;
        notifLink.style.color = color;
        notifLink.style.fontWeight = fontWeight;
    }

    if (notifLinkMobile) {
        notifLinkMobile.textContent = text;
        notifLinkMobile.style.color = color;
        notifLinkMobile.style.fontWeight = fontWeight;
    }
}

socket.on('notification', () => {
    const source = notifLink || notifLinkMobile;
    const match = source ? source.textContent.match(/\((\d+)\)/) : null;
    const current = match ? parseInt(match[1]) : 0;
    updateNotifBadge(current + 1);
});

(async () => {
    try {
        const res = await fetch('/notifications/count', { cache: 'no-store' });
        if (!res.ok) return;
        const data = await res.json();
        updateNotifBadge(data.count);
    } catch (_) {}
})();
