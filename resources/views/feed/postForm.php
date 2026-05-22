<?php
$action = "/createPost";
?>

<section class="post-form">
    <form action="<?= htmlspecialchars($action) ?>" method="post" enctype="multipart/form-data">
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
        <div class="post-action">
            <textarea name="content" placeholder="What's on your mind?" required></textarea>
            <button type="submit">Post</button>
        </div>
        <label class="post-image-label" for="post-images">
            <b>📷 Add Photo</b>
            <small>(Max of 4 images per post)</small>
        </label>
        <div class="post-image-preview" id="post-image-preview"></div>
        <input type="file" id="post-images" name="images[]" accept="image/*" multiple style="display:none" />
    </form>
<script>
   const input = document.getElementById('post-images');
   const preview = document.getElementById('post-image-preview');

   input.addEventListener('change', () => {
       preview.innerHTML = '';
       const files = Array.from(input.files).slice(0, 4);
       files.forEach(file => {
           const img = document.createElement('img');
           img.src = URL.createObjectURL(file);
           img.className = 'post-photo-thumb';
           preview.appendChild(img);
       });
   });
</script>
</section>
