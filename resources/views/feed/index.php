<?php require_once __DIR__ . '/../layouts/Header.php'; ?>

<?php require __DIR__ . '/../layouts/Sidebar.php'; ?>

<main class="index">
<div class="component-info-header">
    <p>Newsfeed</p>
</div>

<div class="feed-wrapper">
    <?php require __DIR__ . '/postForm.php'; ?>

    <?php foreach ($posts as $post): ?>
        <?php require __DIR__ . '/../components/post-card.php'; ?>
    <?php endforeach; ?>

    <?php if (count($posts) === 10): ?>
        <button id="load-more-posts-btn" data-offset="10">See more...</button>
    <?php endif; ?>
</div>

<script src="/js/likepost.js"></script>
<script src="/js/edit-post.js"></script>
<script src="/js/post-photo-bg.js"></script>
<script src="/js/feed-load-more.js"></script>
</main>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
