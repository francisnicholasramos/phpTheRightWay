<div class="search-results-container">
    <div class="user-results">
        <?php foreach ($results['users'] as $result): ?>
            <a href="/u/<?= htmlspecialchars($result->username) ?>">
            <div class="user">
                <div class="avatar">
                    <img src="<?= htmlspecialchars($result->avatar) ?>" loading="lazy"/>
                </div>
                <?= htmlspecialchars($result->first_name . ' ' . 
                ($result->middle_name ? $result->middle_name . ' ' : '') .
                $result->last_name)?>
            </div>
            </a>
        <?php endforeach; ?>
    </div>
    
    <div class="post-results">

    </div>
</div>

