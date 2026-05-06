<div class="conversations">
<?php if (empty($conversations)): ?>
          <p>No conversations yet.</p>
      <?php else: ?>
          <?php foreach ($conversations as $convo): ?>
              <a href="/messages/<?= htmlspecialchars($convo['chat_id']) ?>" class="conversation-item">
                  <div class="conversation-name">
                      <div class="conversation-avatar">
                          <img src="<?= htmlspecialchars($convo['avatar'] ?: '/assets/default_profile.svg') ?>" loading="lazy" />
                      </div>
                    
                      <div>
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
