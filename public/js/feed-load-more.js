const btn = document.getElementById('load-more-posts-btn');

if (btn) {
    btn.addEventListener('click', async () => {
        const offset = parseInt(btn.dataset.offset);
        btn.disabled = true;
        btn.textContent = 'Loading...';

        const res = await fetch(`/feed/more?offset=${offset}`);
        const data = await res.json();

        btn.insertAdjacentHTML('beforebegin', data.html);

        if (typeof GLightbox !== 'undefined') {
            GLightbox({ selector: '.glightbox' });
        }

        document.querySelectorAll('.post-photos--1').forEach(container => {
            if (!container.dataset.bgApplied) {
                applyDominantBg(container);
                container.dataset.bgApplied = '1';
            }
        });

        if (!data.hasMore) {
            btn.remove();
            return;
        }

        btn.dataset.offset = offset + 10;
        btn.disabled = false;
        btn.textContent = 'Load more';
    });
}
