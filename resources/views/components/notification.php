<?php require_once __DIR__ . '/../layouts/Header.php'; ?>
<?php require_once __DIR__ . '/../layouts/Sidebar.php'; ?>

<div class="notification">
    <div class="component-info-header">
        <p>Notifications</p>
    </div>
    <?php foreach ($notifications as $notification): ?>
    <div class="notification-item">
        <a href="">
            <div class="notification-avatar">
                <img src="<?= htmlspecialchars($notification['avatar'] ?: '/assets/default_profile.png') ?>" loading="lazy" />
            </div>
            <div class="notification-data">
                <p><?= htmlspecialchars($notification['first_name']) ?></p>

                <?php if ($notification['middle_name']): ?>
                    <p><?= htmlspecialchars($notification['middle_name']) ?></p>
                <?php endif; ?>

                <p><?= htmlspecialchars($notification['last_name']) ?></p>
                <p class="notification-event">liked your post</p>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
