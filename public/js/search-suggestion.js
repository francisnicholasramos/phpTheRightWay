const input = document.getElementById('search-input');
const suggestions = document.getElementById('search-suggestions');

let debounceTimer;

input.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    const query = input.value.trim();

    if (query === '') {
        suggestions.textContent = '';
        return;
    }

    debounceTimer = setTimeout(async () => {
        const res = await fetch(`/search/suggest?q=${encodeURIComponent(query)}`);

        const data = await res.json();

        suggestions.textContent = '';

        data.users?.forEach(user => {
            const li = document.createElement('li');
            li.textContent = user.username;
            suggestions.appendChild(li);
        });
    }, 300);

});
