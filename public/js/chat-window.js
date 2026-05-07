document.getElementById('chat-window').querySelector('form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const res = await fetch('/sendMessage', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: new FormData(this)
    });
    const data = await res.json();
    if (data.success) window.location.href = '/messages/' + data.chat_id;
});
