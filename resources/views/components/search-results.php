<div class="search-results-container">
    <div class="user-results">
        <?php foreach ($results['users'] as $result): ?>
            <div class="user">
                <div class="avatar">
                    <img src="<?= htmlspecialchars($result->avatar ?: '/assets/default_profile.svg') ?>" loading="lazy" alt="<?= $result->first_name ?>"/>
                </div>
                <a href="/u/<?= htmlspecialchars($result->username) ?>">
                    <?= htmlspecialchars($result->first_name . ' ' . 
                    ($result->middle_name ? $result->middle_name . ' ' : '') .
                    $result->last_name)?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="post-results">

    </div>
</div>

