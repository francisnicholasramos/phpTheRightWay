<?php require_once __DIR__ . '/../layouts/Header.php'; ?>
<?php require_once __DIR__ . '/../layouts/Sidebar.php'; ?>

<div class="chatbox">
  <a href="/u/<?= htmlspecialchars($recipient->username) ?>">
      <div class="chat-receiver-name">
            <span class="value">
            <?= htmlspecialchars($recipient->first_name) ?>
            <?php if ($recipient->middle_name): ?>
                <?= htmlspecialchars($recipient->middle_name) ?>
            <?php endif; ?>
            <?= htmlspecialchars($recipient->last_name) ?>
            </span>
      </div>
  </a>

  <div class="chat-messages">
      <?php foreach ($messages as $msg): ?>
        <div class="<?= $msg->sender_id === $currentUserId ? 'sender' : 'receiver' ?>">
            <?= htmlspecialchars($msg->message_content) ?>
        </div>
      <?php endforeach; ?>
  </div>

  <form action="/sendMessage" method="POST">
    <input type="hidden" name="recipient_id" value="<?= htmlspecialchars($recipientId) ?>" />
    <div class="chat-input-wrapper">
        <textarea id="message-input" name="message_content" rows="1" placeholder="Type a message"></textarea>
        <button type="submit" id="send-btn" disabled>&#10148;</button>
    </div>
  </form>
</div>

<script src="<?= rtrim($_ENV['SOCKET_URL'] ?? 'http://localhost:3000', '/') ?>/socket.io/socket.io.js"></script>
<script>
    window.CHAT = {
        currentUserId: '<?= htmlspecialchars($currentUserId) ?>',
        recipientId: '<?= htmlspecialchars($recipientId) ?>',
        socketUrl: '<?= $_ENV['SOCKET_URL'] ?? 'http://localhost:3000' ?>'
    };
</script>
<script src="/js/chatbox.js"></script>
