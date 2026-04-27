<?php
$action = "/createPost";
?>

<section class="post-form">
    <form action="<?= htmlspecialchars($action) ?>" method="post">
        <div>
            <span>
            <label for="audience">Share with:</label>
            <select id="audience" name="audience">
                <option value="public">Public</option>
                <option value="friends">Friends</option>
                <option value="private">Private</option>
            </select>
            </span>
        <div>
        <textarea name="content" placeholder="What's on your mind?" required></textarea>
        <button type="submit">Post</button>
    </form>
</section>
