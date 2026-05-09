<?php require_once __DIR__ . '/../layouts/Header.php'; ?>

<?php require __DIR__ . '/../layouts/Sidebar.php'; ?>

<main class="index">
<div class="component-info-header">
    <p>Newsfeed</p>
</div>
<div class="feed-wrapper">
<?php require __DIR__ . '/postForm.php'; ?>

<?php foreach ($posts as $post): ?>
<div class="feed-item">
    <a href="/u/<?= htmlspecialchars($post->username) ?>">
    <div class="feed-user">
        <div class="feed-item-avatar">
            <img src="<?= htmlspecialchars($post->avatar ?: '/assets/default_profile.svg') ?>" loading="lazy" />
        </div>
        <div>
        <span>
            <?= htmlspecialchars($post->first_name) ?>
            <?= htmlspecialchars($post->middle_name) ?>
            <?= htmlspecialchars($post->last_name) ?>
        </span>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $post->user_id) : ?>
            <small><?= $post->visibility?></small>
        <?php endif; ?>
        </div>
    </div>
    </a>

    <div class="feed-content">
        <?= $post->content?>
    </div>

    <div class="feed-action">
        <div>
            <button 
                id="like-btn-<?= htmlspecialchars($post->id) ?>"
                class="<?= $post->liked_by_me ? 'liked' : '' ?>" 
                data-post-id="<?= htmlspecialchars($post->id) ?>"
            >
                Like
            </button>
            <span id="likes-<?= htmlspecialchars($post->id) ?>" class="likes-count">
                <?= $post->likes_count ? "{$post->likes_count}" : '' ?>
            </span>
        </div>

        <a href="/post/<?= htmlspecialchars($post->id) ?>">Comment</a>
        <button>Share</button>
    </div>
</div>
<?php endforeach; ?>
</div>

<script src="/js/likepost.js"></script>

</main>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
