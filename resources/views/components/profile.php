<?php require_once __DIR__ . '/../layouts/Header.php'; ?>
<?php require_once __DIR__ . '/../layouts/Sidebar.php'; ?>
<main class="profile-container">
    <div class="component-info-header">
        <p>Profile</p>
    </div>
    <div class="profile-wrapper">
    <div class="profile-panel">
        <a href="/profiles/<?= htmlspecialchars($user->id) ?>/avatar">
          <div class="profile-avatar">
              <img src="<?= htmlspecialchars($user->avatar ?: '/assets/default_profile.png') ?>" loading="lazy"/>
          </div>
        </a>
        <?php if ($_SESSION['user_id'] === $user->id): ?>
            <div class="profile-actions">
                <a href="/profiles/<?= htmlspecialchars($user->id) ?>" id="edit-profile">Edit Profile</a>
                <a href="/u/<?= htmlspecialchars($user->username) ?>/friends" id="view-friends">View all friends</a>
            </div>
        <?php endif; ?>
        <!-- if user if logged-in and can't do action in your own profile -->
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== $user->id) : ?>
        <div class="profile-actions">
            <?php if ($isFriends): ?>
                <button disabled>Friends &#8730;</button>
            <?php elseif ($isIncoming): ?>
                <button class="accept-btn" data-requester-id="<?= htmlspecialchars($user->id) ?>">Accept &#8730;</button>
                <button class="decline-btn" data-requester-id="<?= htmlspecialchars($user->id) ?>">Decline &#88;</button>
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
                <button id="poke-btn" data-to-user-id="<?= htmlspecialchars($user->id) ?>">Poke!</button>
        </div>
        <?php endif; ?>
        
        <div class="profile-friend-section">
            <div class="profile-category">
                <p class="info-header">Friends</p>
            </div>
            <div class="profile-friend-list">
                <?php if (empty($friends)): ?>
                    <p style="color: #808080; 
                              font-size: 11px; 
                              width: 100%; 
                              white-space: nowrap;
                              margin: auto;"
                    >No friends yet.</p>
                <?php else: ?>
                    <?php foreach ($friends as $friend): ?>
                    <a href="/u/<?= htmlspecialchars($friend['username']) ?>">
                        <div
                          class="friend-item"
                          title="<?= htmlspecialchars($friend['first_name']) ?> <?= htmlspecialchars($friend['middle_name']) ?> <?= htmlspecialchars($friend['last_name']) ?>"
                        >
                            <img src="<?= htmlspecialchars($friend['avatar'] ?: '/assets/default_profile.svg') ?>" loading="lazy" />
                            <span>
                              <small>
                                <?= htmlspecialchars($friend['first_name']) ?>
                              </small>
                            </span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                    <?php if (count($friends) >= 5): ?>
                        <a href="/u/<?= htmlspecialchars($user->username) ?>/friends" class="see-all-friends">See all...</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="profile-information">
        <div class="profile-category">
            <p class="info-header">Information</p>
        </div>
        <div class="info-section">
            <p>Account Info</p>
            <div class="info-row">
                <span class="label">Name:</span>
                <span class="value"><?= htmlspecialchars($user->first_name) ?>
                <?php if ($user->middle_name): ?>
                    <?= htmlspecialchars($user->middle_name) ?>
                <?php endif; ?>
                <?= htmlspecialchars($user->last_name) ?>
            </div>
            <div class="info-row">
                <span class="label">Joined on:</span>
                <span class="value">
                    <?= (new DateTime($user->created_at))->format('F j, Y') ?>
                </span>
            </div>
        </div>

        <div class="info-section">
            <p>Basic Info</p>
            <?php if ($user->bio): ?>
            <div class="info-row">
                <span class="label">Bio:</span>
                <span class="value"><?= htmlspecialchars($user->bio) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($user->hometown): ?>
            <div class="info-row">
                <span class="label">Hometown:</span>
                <span class="value"><?= htmlspecialchars($user->hometown) ?></span>
            </div>
            <?php endif; ?>
            <div class="info-row">
                <span class="label">Gender:</span>
                <span class="value"><?= htmlspecialchars($user->gender) ?></span>
            </div>
            <div class="info-row">
                <span class="label">Birthday:</span>
                <span class="value"><?= htmlspecialchars(date('F j, Y', strtotime( $user->birthday))) ?></span>
            </div>
        </div>

        <div class="info-section">
            <p>Contact Info</p>
            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value"><?= htmlspecialchars($user->email) ?></span>
            </div>
        </div>

        <?php if ($profile): ?>
            <!-- about me -->
            <?php if ($profile->interests || $profile->hobbies || $profile->favorite_music): ?>
                <div class="info-section">
                    <p>About me</p>
                    <?php if ($profile->interests): ?>
                    <div class="info-row">
                        <span class="label">Interests:</span>
                        <span class="value"><?= htmlspecialchars(implode(', ', $profile->interests)) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($profile->hobbies): ?>
                    <div class="info-row">
                        <span class="label">Hobbies:</span>
                        <span class="value"><?= htmlspecialchars(implode(', ', $profile->hobbies)) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($profile->favorite_music): ?>
                    <div class="info-row">
                        <span class="label">Favorite Music:</span>
                        <span class="value"><?= htmlspecialchars(implode(', ', $profile->favorite_music)) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- education -->
            <?php if ($profile?->education): ?>
              <div class="info-section">
                  <p>Education</p>
                  <?php if ($profile->education['school']): ?>
                      <div class="info-row">
                          <span class="label">School:</span>
                          <span class="value"><?= htmlspecialchars($profile->education['school'] ?? '') ?></span>
                      </div>
                  <?php endif; ?>
                  <?php if ($profile->education['field']): ?>
                  <div class="info-row">
                      <span class="label">Field of study:</span>
                      <span class="value"><?= htmlspecialchars($profile->education['field'] ?? '') ?></span>
                  </div>
                  <?php endif; ?>
              </div>
            <?php endif; ?>

            <!-- work -->
            <?php if ($profile?->work): ?>
              <div class="info-section">
                  <p>Work</p>
                  <?php if ($profile->work['company']): ?>
                  <div class="info-row">
                      <span class="label">Company:</span>
                      <span class="value"><?= htmlspecialchars($profile->work['company'] ?? '') ?></span>
                  </div>
                  <?php endif; ?>
                  <?php if ($profile->work['position']): ?>
                  <div class="info-row">
                      <span class="label">Role:</span>
                      <span class="value"><?= htmlspecialchars($profile->work['position'] ?? '') ?></span>
                  </div>
                  <?php endif; ?>
              </div>
            <?php endif; ?>

        <?php endif; ?>
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
   </div>
</main>

<script src="/js/friend-request.js"></script>
<script src="/js/chat-window.js"></script>
<script src="/js/poke.js"></script>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
