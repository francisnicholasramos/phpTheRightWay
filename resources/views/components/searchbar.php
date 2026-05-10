<?php
$action = "/search";
?>

<div class="dashed-border">
<form action="<?= htmlspecialchars($action); ?>" method="GET" onsubmit="if(!this.q.value.trim()) return false;" class="search-form">
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
        placeholder="Search"
    />
    <ul id="search-suggestions"></ul>
</form>
</div>
