<?php require_once __DIR__ . '/../layouts/Header.php'; ?>
<?php require __DIR__ . '/../layouts/Sidebar.php'; ?>

<div class="post-detail">
    <div class="component-info-header">
        <p>View post</p>
    </div>
    <a href="/u/<?= htmlspecialchars($post->username) ?>">
    <div class="feed-user">
        <div class="feed-item-avatar">
            <img src="<?= htmlspecialchars($post->avatar ?: '/assets/default_profile.svg') ?>" loading="lazy" />
        </div>
        <div>
            <span>
                <?= htmlspecialchars($post->first_name) ?>
                <?= htmlspecialchars($post->middle_name ?? '') ?>
                <?= htmlspecialchars($post->last_name) ?>
            </span>
        </div>
    </div>
    </a>

    <div class="feed-content">
        <?= htmlspecialchars($post->content) ?>
    </div>


    <form class="comment-form" method="post" action="/postComment">
    <h3>Comments</h3>
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post->id) ?>" />
        <textarea name="comment" placeholder="Write a comment..." rows="2"></textarea>
        <button type="submit" class="universal-btn">Comment</button>
    </form>

    <div class="comments-section">
        <?php if (!$comments): ?>
            <p class="no-comments">No comments yet. Be the first to share your thoughts!</p>
        <?php endif; ?>
        <?php foreach ($comments as $comment): ?>
        <div class="comment-item">
            <div class="comment-avatar">
                <img src="<?= htmlspecialchars($comment->avatar ?: '/assets/default_profile.svg') ?>" loading="lazy" />
            </div>
            <div class="comment-body">
                <strong>
                    <?= htmlspecialchars($comment->first_name ?? '') ?>
                    <?= htmlspecialchars($comment->middle_name ?? '') ?>
                    <?= htmlspecialchars($comment->last_name ?? '') ?>
                </strong>
                <p><?= htmlspecialchars($comment->content) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</main>
<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
