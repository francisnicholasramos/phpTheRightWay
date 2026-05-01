<?php if (isset($_SESSION['user_id'])) : ?>
    <aside class="sidebar">
        <?php require __DIR__ . '/../components/searchbar.php'; ?>
    </aside>
<?php endif; ?>
