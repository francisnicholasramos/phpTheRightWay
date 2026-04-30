<?php require_once __DIR__ . '/../layouts/Header.php'; ?>

<?php require __DIR__ . '/../layouts/Sidebar.php'; ?>

<main class="index">
<?php require __DIR__ . '/postForm.php'; ?>

<?php foreach ($posts as $post): ?>
<div class="feed-item">
    <div class="feed-user">
        <div class="feed-item-avatar">
            <img src="<?= htmlspecialchars($post->avatar) ?>" loading="lazy" />
        </div>
        <div>
        <span>
            <?= htmlspecialchars($post->first_name) ?>
            <?= htmlspecialchars($post->middle_name) ?>
            <?= htmlspecialchars($post->last_name) ?>
        </span>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $post->user_id) : ?>
            <small>
                <b><?= $post->visibility?></b>
            </small>
        <?php endif; ?>
        </div>
    </div>

    <div class="feed-content">
        <?= $post->content?>
    </div>

    <div class="feed-action">
        <div>
        <span id="likes-<?= $post->id ?>"><?= $post->likes_count ? "{$post->likes_count}" : '' ?></span>
        <button onclick="likePost('<?= $post->id ?>')">Like</button>
        </div>

        <form method="post" action="/postComment">
            <input type="hidden" name="post_id" value="<?= $post->id ?>" />
            <button type="submit">Post comment</button>
        </form>
        <button>Share</button>
    </div>
</div>
<?php endforeach; ?>

<script>
async function likePost(postId) {
    const formData = new FormData();
    formData.append('post_id', postId);
    
    const response = await fetch('/like', {
        method: 'POST',
        body: formData
    });
    
    const data = await response.json();
    document.getElementById('likes-' + postId).textContent = data.count;
}
</script>

</main>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
