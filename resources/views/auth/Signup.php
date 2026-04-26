<?php $title = "Sign Up"; ?>

<?php require_once __DIR__ . '/../layouts/Header.php'; ?>

<main class="auth">
    <form action="/signup" method="POST">
        <div>
            <label>First name</label>
            <input type="text" name="firstname"/>
        </div>
        <div>
            <label>Middlename</label>
            <input type="text" name="middlename" placeholder="(optional)" />
        </div>
        <div>
            <label>Last name</label>
            <input type="text" name="lastname" />
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" placeholder="example@gmail.com" />
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" />
        </div>
        <div class="gender">
            <label>Gender: </label>
            <div>
                <label for="male">Male</label>
                <input type="radio" id="male" name="gender" value="male"/>
            </div>
            <div>
                <label for="female">Female</label>
                <input type="radio" id="female" name="gender" value="female"/>
            </div>
        </div>
        <?php include __DIR__ . '/../layouts/ErrorMessage.php'; ?>

        <div>
          <button type="submit">login</button>
          <button type="submit">register</button>
        <div>
    </form>
</main>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
