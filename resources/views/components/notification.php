<?php require_once __DIR__ . '/../layouts/Header.php'; ?>
<?php require_once __DIR__ . '/../layouts/Sidebar.php'; ?>

<div class="notification">
    <div class="component-info-header">
        <p>Notifications</p>
    </div>
    <?php if (empty($notifications)): ?>
        <div style="border: 1px solid #cccccc; padding: 5px;">
            <p style="color: #808080;">No notifications yet</p>
        </div>
    <?php else: ?>
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
                    <p class="notification-event">
                        <?php if ($notification['entity_type'] === 'like'): ?>
                            likes your post.
                        <?php elseif ($notification['entity_type'] === 'friend_request'): ?>
                            sent you a friend request.
                        <?php elseif ($notification['entity_type'] === 'poke'): ?>
                            poked you.
                        <?php elseif ($notification['entity_type'] === 'comment'): ?>
                            commented on your post.
                        <?php endif; ?>
                    </p>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
