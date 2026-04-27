<?php require_once __DIR__ . '/../layouts/Header.php'; ?>

<?php require __DIR__ . '/../layouts/Sidebar.php'; ?>

<main class="index">
<?php require __DIR__ . '/postForm.php'; ?>

<?php foreach ($posts as $post): ?>
    <p>
        <?= $post->content?>
        <span><b><?= $post->visibility?></b></span>
        <span id="likes-<?= $post->id ?>"><?= $post->likes_count ? "Likes: {$post->likes_count}" : '' ?></span>

        <button onclick="likePost('<?= $post->id ?>')">Like</button>

        <form method="post" action="/postComment">
            <input type="textarea" name="comment" placeholder="comment?"/>
            <input type="hidden" name="post_id" value="<?= $post->id ?>" />
            <button type="submit">Post comment</button>
        </form>
    </p>
<?php endforeach; ?>

<script>
async function likePost(postId) {
    const formData = new FormData();
    formData.append('post_id', postId);
    
    const response = await fetch('/like', {
        method: 'POST',
        body: formData
    });
    
    const data = await response.json();
    document.getElementById('likes-' + postId).textContent = 'Likes: ' + data.count;
}
</script>

<form action="/logout" method="post">
    <button type="submit">Logout</button>
</form>
</main>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
