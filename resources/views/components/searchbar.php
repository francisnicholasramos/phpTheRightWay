<?php
$action = "/search";
?>

<div class="dashed-border">
<form action="<?= htmlspecialchars($action); ?>" method="GET" class="search-form">
    <input 
        id="search-input"
        name="q"
        dir="ltr"
        role="combobox"
        type="search" 
        aria-expanded="true"
        aria-autocomplete="list"
        autocomplete="off"
        aria-invalid="false"
        value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
    />
    <span>
        quick search
        <button type="submit" class="universal-btn">go</button>
    </span>
    <ul id="search-suggestions"></ul>
</form>
</div>
