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


