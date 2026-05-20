<?php require_once __DIR__ . '/../layouts/Header.php'; ?>
<?php require __DIR__ . '/../layouts/Sidebar.php'; ?>

<div class="post-detail">
    <div class="component-info-header">
        <p>View post</p>
    </div>
    <div class="post-detail-wrapper">
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
        </div>
    </div>

    <div class="comment-detail-wrapper">
        <h3>Comments</h3>
        <div class="separator"></div>

        <div class="comments-section">
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

        <form class="comment-form" method="post" action="/postComment">
            <input type="hidden" name="post_id" value="<?= htmlspecialchars($post->id) ?>" />
            <div class="comment-input-wrapper">
                <div class="comment-avatar">
                    <img src="<?= htmlspecialchars(\App\Services\AuthService::user()->avatar ?: '/assets/default_profile.svg') ?>" loading="lazy" />
                </div>
                <textarea name="comment" placeholder="Write a comment..." rows="2"></textarea>
                <button type="submit" class="universal-btn">Post</button>
            </div>
        </form>
        <?php if (!$comments): ?>
            <div class="separator"></div>
            <p class="no-comments">No comments yet.</p>
        <?php endif; ?>
    </div>
</div>

<script src="/js/likepost.js"></script>
<script>
    const textarea = document.querySelector('.comment-input-wrapper textarea');
    const button = document.querySelector('.comment-input-wrapper button');
    button.style.display = 'none';
    textarea.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
        button.style.display = this.value.trim() ? 'block' : 'none';
    });
</script>
</main>
<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
