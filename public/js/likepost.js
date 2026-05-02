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
}

document.querySelectorAll('[data-post-id]').forEach(btn => {
    btn.addEventListener("click", () => likePost(btn.dataset.postId));
});
