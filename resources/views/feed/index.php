<?php require_once __DIR__ . '/../layouts/Header.php'; ?>

<script>
const ws = new WebSocket('ws://localhost:8080');

ws.onmessage = (event) => {
    const data = JSON.parse(event.data);
    if (data.type === 'like_update') {
        document.getElementById('likes-' + data.post_id).innerText = 'Likes: ' + data.count;
    }
};
</script>

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
    document.getElementById('likes-' + postId).innerText = 'Likes: ' + data.count;
}
</script>

<form action="/logout" method="post">
    <button type="submit">Logout</button>
</form>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
