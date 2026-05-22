<?php require_once __DIR__ . '/../layouts/Header.php'; ?>
<?php require_once __DIR__ . '/../layouts/Sidebar.php'; ?>

<div class="photos">
    <div class="component-info-header">
        <p>
            <?php if (\App\Services\AuthService::user()->id === $user->id): ?>
                Your Photos
            <?php else: ?>
                <?= htmlspecialchars($user->first_name) ?>'s Photos
            <?php endif; ?>
        </p>
    </div>

    <?php if (empty($photos)): ?>
        <p class="photos-empty">No photos yet.</p>
    <?php else: ?>
        <div class="photo-list-grid">
            <?php foreach ($photos as $photo): ?>
                <a
                    href="<?= htmlspecialchars($photo['url']) ?>"
                    class="photo-grid-item glightbox"
                    data-gallery="user-photos"
                    <?php if ($photo['type'] === 'post' && $photo['post_id']): ?>
                        data-description="<a href='/post/<?= htmlspecialchars($photo['post_id']) ?>' style='font-size:16px;'>View on post &rarr;</a>"
                    <?php endif; ?>
                >
                    <img src="<?= htmlspecialchars($photo['url']) ?>" loading="lazy" alt="" />
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
