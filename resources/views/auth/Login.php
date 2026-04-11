<?php $title = "Login"; ?>

<?php require_once __DIR__ . '/../layouts/Header.php'; ?>

<main>
    <form action="/login" method="POST">
        <div>
            <label>Email</label>
            <input type="email" name="email" placeholder="example@gmail.com" />
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" />
        </div>

        <button type="submit">Login</button>
    </form>
</main>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
