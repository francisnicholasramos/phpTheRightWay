<div>
  <?php foreach ($messages as $msg): ?>
    <div class="<?= $msg->sender_id === $currentUserId ? 'sent' : 'received' ?>">
        <?= htmlspecialchars($msg->message_content) ?>
    </div>
  <?php endforeach; ?>
  
  <form action="/sendMessage" method="POST">
    <input type="hidden" name="recipient_id" value="<?= htmlspecialchars($recipientId) ?>" />
    <textarea name="message_content">
    </textarea>
    
    <button type="submit">Send</button>
  </form>
</div>


