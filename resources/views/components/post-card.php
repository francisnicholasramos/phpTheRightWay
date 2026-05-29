<div class="feed-item">
    <div class="feed-item-header">
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

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $post->user_id): ?>
        <div class="feed-item-options">
            <button class="options-btn" data-post-id="<?= htmlspecialchars($post->id) ?>">&#8943;</button>
            <div class="options-dropdown" id="dropdown-<?= htmlspecialchars($post->id) ?>">
                <button class="edit-post-btn" data-post-id="<?= htmlspecialchars($post->id) ?>">Edit</button>
            </div>
        </div>
    <?php endif; ?>
    </div>

   <div class="feed-content" data-post-id="<?= htmlspecialchars($post->id) ?>">
      <span class="post-text"><?= htmlspecialchars($post->content) ?></span>
       <?php if (!empty($post->photos)): ?>
           <div class="post-photos post-photos--<?= count($post->photos) ?>">
               <?php foreach ($post->photos as $photo): ?>
                   <a href="<?= htmlspecialchars($photo) ?>" class="post-photo-wrap glightbox" data-gallery="post-<?= htmlspecialchars($post->id) ?>">
                       <img src="<?= htmlspecialchars($photo) ?>" loading="lazy" class="post-photo" crossorigin="anonymous" />
                   </a>
               <?php endforeach; ?>
           </div>
       <?php endif; ?>

      <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $post->user_id): ?>
          <form action="/editPost" method="POST">
              <input type="hidden" name="post_id" value="<?= htmlspecialchars($post->id) ?>" />
              <textarea name="edit-content" class="edit-textarea"><?= htmlspecialchars($post->content) ?></textarea>
              <div class="edit-actions">
                  <button type="submit" class="save-edit-btn">Save</button>
                  <button type="button" class="cancel-edit-btn" data-post-id="<?= htmlspecialchars($post->id) ?>">Cancel</button>
              </div>
          </form>
      <?php endif; ?>
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
