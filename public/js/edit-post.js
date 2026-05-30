// toggle dropdown
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('options-btn')) {
    const postId = e.target.dataset.postId;
    const dropdown = document.getElementById('dropdown-' + postId);
    dropdown.classList.toggle('open');
  } else {
    document.querySelectorAll('.options-dropdown.open').forEach(d => d.classList.remove('open'));
  }
});

// edit post
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('edit-post-btn')) {
    const postId = e.target.dataset.postId;
    const feedContent = document.querySelector('.feed-content[data-post-id="' + postId + '"]');
    feedContent.classList.add('editing');
    document.getElementById('dropdown-' + postId).classList.remove('open');
  }
});

// cancel edit
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('cancel-edit-btn')) {
    const postId = e.target.dataset.postId;
    const feedContent = document.querySelector('.feed-content[data-post-id="' + postId + '"]');
    const textarea = feedContent.querySelector('.edit-textarea');
    textarea.value = feedContent.querySelector('.post-text').textContent.trim();
    feedContent.classList.remove('editing');
  }
});

// delete photo in edit mode
document.addEventListener('click', function (e) {
  if (!e.target.classList.contains('delete-photo-btn')) return;

  const photoId = e.target.dataset.photoId;
  const item    = document.querySelector('.edit-photo-item[data-photo-id="' + photoId + '"]');

  fetch('/deletePostPhoto', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'photo_id=' + encodeURIComponent(photoId)
  })
  .then(res => res.json())
  .then(data => {
    if (data.message === 'ok') item.remove();
  });
});

// delete post
document.addEventListener('click', function (e) {
  if (!e.target.classList.contains('delete-post-btn')) return;

  const postId = e.target.dataset.postId;

  if (!confirm('Delete this post?')) return;

  fetch('/deletePost', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'post_id=' + encodeURIComponent(postId)
  })
  .then(res => {
    if (!res.ok) throw new Error('Forbidden');
    e.target.closest('.feed-item').remove();
  })
  .catch(() => alert('Something went wrong.'));
});
