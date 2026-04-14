<?php $title = "Sign Up"; ?>

<?php require_once __DIR__ . '/../layouts/Header.php'; ?>

<main>
    <form action="/signup" method="POST">
        <h2>Sign Up</h2>
        <div>
            <label>Email</label>
            <input type="email" name="email" placeholder="example@gmail.com" />
        </div>
        <div>
            <label>Username</label>
            <input type="text" name="username" placeholder="username" />
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" />
        </div>
        <?php include __DIR__ . '/../layouts/ErrorMessage.php'; ?>

        <button type="submit">Sign Up</button>
    </form>
</main>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
