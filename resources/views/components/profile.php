<?php require_once __DIR__ . '/../layouts/Header.php'; ?>
<?php require_once __DIR__ . '/../layouts/Sidebar.php'; ?>
<main class="profile-container">
    <div class="profile-panel">
        <div class="profile-avatar">
            <img src="<?= htmlspecialchars($user->avatar ?: '/assets/default_profile.png') ?>" loading="lazy"/>
        </div>
        <!-- if user if logged-in and can't do action in your own profile -->
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== $user->id) : ?>
        <div class="profile-actions">
            <?php if ($isFriends): ?>
                <button disabled>Friends</button>
            <?php elseif ($isIncoming): ?>
                <button class="accept-btn" data-requester-id="<?= htmlspecialchars($user->id) ?>">Accept</button>
                <button class="decline-btn" data-requester-id="<?= htmlspecialchars($user->id) ?>">Decline</button>
            <?php else: ?>
                <!-- user's perspective for who intiates the event -->
                <button
                    id="add-friend"
                    data-recipient-id="<?= htmlspecialchars($user->id) ?>"
                    data-pending="<?= $isPending ? 'true' : 'false' ?>"><?= $isPending ? 'Cancel Request' : 'Add Friend' ?>
                </button>
            <?php endif; ?>
            <?php if ($existingChatId): ?>
                <a href="/messages/<?= htmlspecialchars($existingChatId) ?>">Send a message</a>
            <?php else: ?>       
                <a href="#" onclick="document.getElementById('chat-window').style.display='flex'">Send a message</a>
            <?php endif; ?>
            <a href="">Poke!</a>
        </div>
        <?php endif; ?>
    </div>

    <div class="profile-information">
        <div>
            <p class="info-header">Information</p>
        </div>
        <div class="info-section">
            <p>Account Info</p>
            <div class="info-row">
                <span class="label">Name:</span>
                <span class="value"><?= htmlspecialchars($user->first_name) ?></span>
                <?php if ($user->middle_name): ?>
                    <span class="value"><?= htmlspecialchars($user->middle_name) ?></span>
                <?php endif; ?>
                <span class="value"><?= htmlspecialchars($user->last_name) ?></span>
            </div>
            <div class="info-row">
                <span class="label">Joined on:</span>
                <span class="value">
                    <?= (new DateTime($user->created_at))->format('F j, Y') ?>
                </span>
            </div>
        </div>

        <div class="info-section">
            <p>Contact Info</p>
            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value"><?= htmlspecialchars($user->email) ?></span>
            </div>
        </div>
    </div>

    <div id="chat-window" class="chat-window">
        <div class="modal-box">
          <div>
              <p>Send a message</p>
              <button onclick="document.getElementById('chat-window').style.display='none'">✕</button>
          </div>
          <form action="/sendMessage" method="POST">
              <input type="hidden" name="recipient_id" value="<?= htmlspecialchars($user->id) ?>" />
              <textarea name="message_content" placeholder="Write a message..."></textarea>
              <button type="submit">Send</button>
          </form>
        </div>
   </div>
</main>

<script src="/js/friend-request.js"></script>
<script src="/js/chat-window.js"></script>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
