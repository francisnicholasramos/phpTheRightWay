async function likePost(postId) {
    const formData = new FormData();
    formData.append('post_id', postId);
    
    const response = await fetch('/like', {
        method: 'POST',
        body: formData
    });
    
    const data = await response.json();
    document.getElementById('likes-' + postId).textContent = data.count || '';
    document.getElementById('like-btn-' + postId).classList.toggle('liked', data.liked);

    if (data.recipientId && window.notifSocket) {
        window.notifSocket.emit('notification', { recipientId: data.recipientId });
    }
}

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.feed-action button[data-post-id]');
    if (btn) likePost(btn.dataset.postId);
});
