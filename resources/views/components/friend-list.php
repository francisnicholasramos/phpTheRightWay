<?php require_once __DIR__ . '/../layouts/Header.php'; ?>
<?php require_once __DIR__ . '/../layouts/Sidebar.php'; ?>

<div class="friends">
  <div class="component-info-header">
      <p>
          <?php if (\App\Services\AuthService::user()->id === $user->id): ?>
              Your Friends
          <?php else: ?>
              <?= htmlspecialchars($user->first_name) ?>'s Friends
          <?php endif; ?>
      </p>
  </div>

    <div class="friend-list-grid" id="friend-list-grid">
        <?php if (empty($friends)): ?>
            <p style="color: #808080; font-size: 13px; white-space: nowrap;">No friends yet.</p>
        <?php else: ?>
            <?php foreach ($friends as $friend): ?>
                <a href="/u/<?= htmlspecialchars($friend['username']) ?>">
                    <div class="friend-list-item">
                        <img src="<?= htmlspecialchars($friend['avatar'] ?: '/assets/default_profile.svg') ?>" loading="lazy" />
                        <span>
                            <?= htmlspecialchars($friend['first_name']) ?>
                            <?php if ($friend['middle_name']): ?>
                                <?= htmlspecialchars($friend['middle_name']) ?>
                            <?php endif; ?>
                            <?= htmlspecialchars($friend['last_name']) ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (count($friends) === 20): ?>
        <button id="load-more-btn" data-username="<?= htmlspecialchars($user->username) ?>" data-offset="20">
            Load more
        </button>
    <?php endif; ?>
</div>

<script src="/js/friend-list.js"></script>
