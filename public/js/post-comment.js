const commentForm = document.getElementById('comment-form');

if (commentForm) {
    commentForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const textarea = this.querySelector('textarea[name="comment"]');
        const button = this.querySelector('button[type="submit"]');

        button.disabled = true;

        try {
            const response = await fetch('/postComment', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!response.ok || data.error) {
                alert(data.error || 'Something went wrong.');
                return;
            }

            if (data.recipientId && window.notifSocket) {
                window.notifSocket.emit('notification', { recipientId: data.recipientId });
            }

            window.location.reload();
        } catch (_) {
            alert('Failed to post comment. Please try again.');
        } finally {
            button.disabled = false;
        }
    });
}
