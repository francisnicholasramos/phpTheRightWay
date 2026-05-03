<?php require_once __DIR__ . '/../layouts/Header.php'; ?>
<?php require_once __DIR__ . '/../layouts/Sidebar.php'; ?>

<main class="profile-container">
    <div class="profile-panel">
        <div class="profile-avatar">
            <img src="<?= htmlspecialchars($user->avatar ?: '/assets/default.png') ?>" loading="lazy"/>
        </div>
        <!-- if user if logged-in and can't do action in your own profile -->
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== $user->id) : ?>
        <div class="profile-actions">
            <a href="/chat/start/<?= htmlspecialchars($user->id) ?>">Send a message</a>
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
</main>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
