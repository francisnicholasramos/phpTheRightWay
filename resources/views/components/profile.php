<?php require_once __DIR__ . '/../layouts/Header.php'; ?>
<?php require_once __DIR__ . '/../layouts/Sidebar.php'; ?>

<main class="profile-container">
    <div class="profile-panel">
        <div class="profile-avatar">
            <img src="<?= htmlspecialchars($user->avatar) ?>" loading="lazy"/>
        </div>
        <div class="profile-actions">
            <a href="/chat/start/<?= htmlspecialchars($user->id) ?>">Send a message</a>
            <a href="">Poke <?= htmlspecialchars($user->first_name) ?>!</a>
        </div>
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
                <span class="value"><?= htmlspecialchars($user->middle_name) ?></span>
                <span class="value"><?= htmlspecialchars($user->last_name) ?></span>
            </div>
            <div class="info-row">
                <span class="label">Joined on:</span>
                <span class="value"><?= (new DateTime($user->created_at)->format('F j, Y')) ?></span>
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
