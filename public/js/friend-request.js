(async () => {
    const addFriendBtn = document.getElementById('add-friend');

    if (addFriendBtn) {
    addFriendBtn.addEventListener('click', async function() {
        const isPending = addFriendBtn.dataset.pending === 'true';
        const endpoint = isPending ? '/cancel-request' : '/friend-request';

        const formData = new FormData();
        formData.append('recipient_id', addFriendBtn.dataset.recipientId);

        const res = await fetch(endpoint, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });

        if (!res.ok) return;
      
        const data = await res.json();

        if (data.recipientId && window.notifSocket) {
            window.notifSocket.emit('notification', { recipientId: data.recipientId });
        }

        addFriendBtn.dataset.pending = isPending ? 'false' : 'true';
        addFriendBtn.textContent = isPending ? 'Add friend' : 'Cancel request';
    });
    } 

    // for responding to request
    const acceptBtn = document.querySelector('.accept-btn');
    const declineBtn = document.querySelector('.decline-btn');

    if (acceptBtn) {
        acceptBtn.addEventListener('click', async function() {
            const formData = new FormData();
            formData.append('requester_id', acceptBtn.dataset.requesterId);

            const res = await fetch('/accept-request', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            if (!res.ok) return;

            // replace both buttons with Friends
            acceptBtn.closest('.profile-actions').querySelector('.decline-btn').remove();
            acceptBtn.disabled = true;
            acceptBtn.classList.remove('accept-btn');
        });
    }

    if (declineBtn) {
        declineBtn.addEventListener('click', async function() {
            const formData = new FormData();
            formData.append('requester_id', declineBtn.dataset.requesterId);

            const res = await fetch('/decline-request', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            if (!res.ok) return;

            // replace both buttons with Add Friend
            acceptBtn.remove();
            declineBtn.replaceWith((() => {
                const btn = document.createElement('button');
                btn.id = 'add-friend';
                btn.dataset.recipientId = declineBtn.dataset.requesterId;
                btn.dataset.pending = 'false';
                btn.textContent = 'Add Friend';
                return btn;
            })());
        });
    }
})();
