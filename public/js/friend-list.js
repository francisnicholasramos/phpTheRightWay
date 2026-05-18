const btn = document.getElementById('load-more-btn');

if (btn) {
    btn.addEventListener('click', async () => {
        const username = btn.dataset.username;
        const offset = parseInt(btn.dataset.offset);

        btn.disabled = true;
        btn.textContent = 'Loading...';

        const res = await fetch(`/u/${username}/friends/more?offset=${offset}`);
        const friends = await res.json();

        const grid = document.getElementById('friend-list-grid');

        friends.forEach(friend => {
            const a = document.createElement('a');
            a.href = `/u/${friend.username}`;

            const div = document.createElement('div');
            div.className = 'friend-list-item';

            const img = document.createElement('img');
            img.src = friend.avatar || '/assets/default_profile.svg';
            img.loading = 'lazy';

            const span = document.createElement('span');
            span.textContent = [friend.first_name, friend.middle_name, friend.last_name]
                .filter(Boolean)
                .join(' ');

            div.appendChild(img);
            div.appendChild(span);
            a.appendChild(div);
            grid.appendChild(a);
        });

        if (friends.length < 20) {
            btn.remove();
            return;
        }

        btn.dataset.offset = offset + friends.length;
        btn.disabled = false;
        btn.textContent = 'Load more';
    });
}
