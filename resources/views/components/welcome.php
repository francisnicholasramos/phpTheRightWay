<?php 
$headTitle = str_contains($_SERVER['REQUEST_URI'], '/register') ? 'Registration' : 'Welcome to Socialnetwork!';
?>
<main class="welcome-page">
    <div class="welcome-header">
        <p><?= htmlspecialchars($headTitle)?></p>
    </div>

    <div>

    <?php if (str_contains($_SERVER['REQUEST_URI'], '/register')): ?>
    <main class="auth-signup">
    <p align="center">To register for socialnetwork, just fill in the four fields below. You will have a chance to enter additional information after submission.</p>
    <form action="/signup" method="POST">
        <div>
            <label>First name:</label>
            <input type="text" name="firstname" size="30"/>
        </div>
        <div>
            <label>Middlename:</label>
            <input type="text" name="middlename" size="30" placeholder="(optional)" />
        </div>
        <div>
            <label>Last name:</label>
            <input type="text" name="lastname" size="30"/>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" size="30"placeholder="example@gmail.com" />
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" size="30"/>
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
          <button type="submit">Register Now!</button>
        <div>
    </form>
    </main>
    <?php else: ?>
        <div class="welcome">
        <h3>
          <b>
            [ Welcome to Socialnetwork ]
          </b>
        </h3>
        <p align="center">Socialnetwork is an online directory that connects people through shared connections</p>
        <br/>
        <p>You can use Socialnetwork to:</p>
        <ul>
        <li>Search for people in your community</li>
        <li>Find out who you have in common</li>
        <li>Explore connections through your friends' networks</li>
        <li>See a visualization of your social circle</li>
        </ul>
        <br/>
        <p align="center">To get started, click below to register. If you have already registered, you can log in.</p>
        <span class="auth-btn">
            <button type="button" class="universal-btn" onclick="window.location.href='/register'">register</button>
            <button type="button" class="universal-btn" onclick="window.location.href='/login'">login</button>
        </span>
        </div>
    <?php endif; ?>
    </div>
</main>
