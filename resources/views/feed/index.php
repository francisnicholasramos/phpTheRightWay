<?php require_once __DIR__ . '/../layouts/Header.php'; ?>

<h2>Hello world!</h2>

<?php foreach ($posts as $post): ?>
    <p>
        <?= $post->content?>
    </p>

<?php endforeach; ?>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
