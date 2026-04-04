<?php require_once __DIR__ . '/../layouts/Header.php'; ?>

<h2>thefacebook</h2>

<form action="/createPost" method="post">
    <textarea name="content" placeholder="What's on your mind?"></textarea>
    <select name="audience">
        <option value="public">Public</option>
        <option value="friends">Friends</option>
        <option value="private">Private</option>
    </select>
    <button type="submit">Post</button>
</form>
<?php foreach ($posts as $post): ?>
    <p>
        <?= $post->content?>
        <span><b><?= $post->visibility?></b></span>
        <span><?= $post->likes_count ? "Likes: {$post->likes_count}" : '' ?></span>

        <form method="post" action="/like">
            <input type="hidden" name="post_id" value="<?= $post->id ?>" />
            <button type="submit">Like</button>
        </form>

        <form method="post" action="/postComment">
            <input type="textarea" name="comment" placeholder="comment?"/>
            <input type="hidden" name="post_id" value="<?= $post->id ?>" />
            <button type="submit">Post comment</button>
        </form>
    </p>
<?php endforeach; ?>

<form action="/logout" method="post">
    <button type="submit">Logout</button>
</form>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
