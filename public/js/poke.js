const pokeBtn = document.getElementById('poke-btn');

if (pokeBtn) {
    pokeBtn.addEventListener('click', async function() {
        const formData = new FormData();
        formData.append('to_user_id', pokeBtn.dataset.toUserId);

        const res = await fetch('/poke', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });

        if (!res.ok) return;

        const data = await res.json();

        if (data.recipientId && window.notifSocket) {
            window.notifSocket.emit('notification', { recipientId: data.recipientId });
        }
    });
}
