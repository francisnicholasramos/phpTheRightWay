<?php if (isset($_SESSION['user_id'])) : ?>
    <aside class="sidebar">
        <?php require __DIR__ . '/../components/searchbar.php'; ?>

        <div class="sidebar-panel">
            <div class="sidebar-actions">
                <a href="/u/<?= \App\Services\AuthService::user()->username ?>">My Profile</a>
                <a href="/u/<?= \App\Services\AuthService::user()->username ?>/friends">My Friends</a>
                <a href="">My Photos</a>
            </div>
        </div>
    </aside>
<?php endif; ?>
<div class="main-content">
