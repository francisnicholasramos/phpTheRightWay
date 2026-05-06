const notifLink = document.getElementById('notification-link');

const socket = io(NOTIF.socketUrl);

socket.on('connect', () => {
    socket.emit('join', NOTIF.userId);
});

window.notifSocket = socket;

function updateBadge(count) {
    notifLink.textContent = count > 0 ? `notifications(${count})` : 'notifications';
}

socket.on('notification', () => {
    const match = notifLink.textContent.match(/\((\d+)\)/);
    const current = match ? parseInt(match[1]) : 0;
    updateBadge(current + 1);
});

(async () => {
    const res = await fetch('/notifications/count');
    const data = await res.json();
    updateBadge(data.count);
})();
