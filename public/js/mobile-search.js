(() => {
    const search = document.getElementById('mobile-search');

    search.addEventListener('click', () => {
        const sidebarOnMobile = document.querySelector('.sidebar');
        sidebarOnMobile.style.display = 'flex';
    });
})();
