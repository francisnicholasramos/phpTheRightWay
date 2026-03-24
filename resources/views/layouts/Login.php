<?php $title = "Login"; ?>

<?php require_once __DIR__ . '/Header.php'; ?>

<main>
    <form>
        <h2>Login</h2>
        <div>
            <label>Email</label>
            <input type="email" name="email" placeholder="example@gmail.com" />
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" />
        </div>
    </form>
</main>

<?php require_once __DIR__ . '/Footer.php'; ?>
