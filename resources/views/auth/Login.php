<?php $title = "Login"; ?>

<?php require_once __DIR__ . '/../layouts/Header.php'; ?>

<main class="auth">
    <form action="/login" method="POST">
        <div>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" />
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" />
        </div>

        <?php include __DIR__ . '/../layouts/ErrorMessage.php'; ?>

        <div>
            <button type="submit">register</button>
            <button type="submit">login</button>
        </div>
    </form>
</main>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
