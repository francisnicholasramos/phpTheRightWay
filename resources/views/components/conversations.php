<div class="conversations">
<div class="component-info-header">
    <p>Inbox chats</p>
</div>
<?php if (empty($conversations)): ?>
        <div style="border: 1px solid #cccccc; padding: 5px;">
            <p style="color: #808080;">No conversations yet.</p>
        </div>
      <?php else: ?>
          <?php foreach ($conversations as $convo): ?>
              <a href="/messages/<?= htmlspecialchars($convo['chat_id']) ?>" class="conversation-item <?= $convo['has_unread'] ? 'unread' : '' ?>">
                  <div class="conversation-name">
                      <div class="conversation-avatar">
                          <img src="<?= htmlspecialchars($convo['avatar'] ?: '/assets/default_profile.svg') ?>" loading="lazy" />
                      </div>
                    
                      <div class="convo-name <?= $convo['has_unread'] ? 'unread' : ''?>">
                          <?= htmlspecialchars($convo['first_name']) ?>
                          <?php if ($convo['middle_name']): ?>
                          <?= htmlspecialchars($convo['middle_name']) ?>
                          <?php endif; ?>
                          <?= htmlspecialchars($convo['last_name']) ?>

                          <?php if ($convo['last_message']): ?>
                              <div class="conversation-preview">
                                  <?= htmlspecialchars($convo['last_message']) ?>
                              </div>
                          <?php endif; ?>
                      </div>
                  </div>
              </a>
          <?php endforeach; ?>
      <?php endif; ?>
</div>
