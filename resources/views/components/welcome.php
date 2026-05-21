<?php 
$headTitle = match(true) {
      str_contains($_SERVER['REQUEST_URI'], '/register') => 'Registration',
      str_contains($_SERVER['REQUEST_URI'], '/reset-password') => 'Reset password',
      str_contains($_SERVER['REQUEST_URI'], '/forgot-password') => 'Forgot password',
      str_contains($_SERVER['REQUEST_URI'], '/login') => 'Login',
      default => 'Welcome to Socialnetwork!'
  };
?>
<main class="welcome-page">
    <div class="welcome-header">
        <p><?= htmlspecialchars($headTitle)?></p>
    </div>

    <div>

    <?php if ($_SERVER['REQUEST_URI'] === '/register'): ?>
    <main class="auth-signup">
    <p align="center">To register for socialnetwork, just fill in the four fields below. You will have a chance to enter additional information after submission.</p>
    <form action="/signup" method="POST">
        <div>
            <label>First name:</label>
            <input type="text" name="firstname" />
        </div>
        <div>
            <label>Middlename:</label>
            <input type="text" name="middlename"  placeholder="(optional)" />
        </div>
        <div>
            <label>Last name:</label>
            <input type="text" name="lastname" />
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" placeholder="example@gmail.com" />
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" />
        </div>
        <div>
            <label for="birthday">Birthday:</label>
            <input type="date" name="birthday" />
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
    <?php elseif ($_SERVER['REQUEST_URI'] === '/login'): ?>
        <form class="login-form" action="/login" method="POST">
            <div>
                <label>Email:</label>
                <input type="text" name="email" />
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="password" />
            </div>

            <?php $flashKey = 'login_error'; include __DIR__ . '/../layouts/ErrorMessage.php'; ?>

            <span class="auth-btn">
                <button type="submit" class="universal-btn">login</button>
                <button type="button" class="universal-btn" onclick="window.location.href='/register'">register</button>
            </span>
            <span>
                If you have forgotten your password,
                <a href="/forgot-password">click</a>
                to reset it.
            </span>
        </form>
    <?php elseif (str_contains($_SERVER['REQUEST_URI'], '/reset-password')): ?>
        <form class="reset-form" action="/reset-password" method="POST">
            <p>Enter your new password below.</p>
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>" />
            <div>
                <label>New password:</label>
                <input type="password" name="password" />
            </div>
            <div>
                <label>Confirm password:</label>
                <input type="password" name="password_confirm" />
            </div>

            <?php $flashKey = 'error'; include __DIR__ . '/../layouts/ErrorMessage.php'; ?>

            <button type="submit" class="universal-btn">Reset password</button>
        </form>
    <?php elseif ($_SERVER['REQUEST_URI'] === '/forgot-password'): ?>
        <form class="reset-form" action="/forgot-password" method="POST">
            <p>Enter your email and we'll send you a link to reset your password.</p>
            <input type="text" id="email" name="email" placeholder="Email address" />

            <?php $flashKey = 'error'; include __DIR__ . '/../layouts/ErrorMessage.php'; ?>
            <?php $flashKey = 'success'; include __DIR__ . '/../layouts/ErrorMessage.php'; ?>

            <button type="submit" class="universal-btn">Send reset link</button>
        </form>
    <?php else: ?>
        <div class="welcome">
            <div>
            <h3>
              <b>
                [ Welcome to Socialnetwork ]
              </b>
            </h3>
            </div>

            <div>
              <p>Socialnetwork is an online directory that connects people through shared connections</p>
            </div>

            <div>
                <p>You can use Socialnetwork to:</p>
                <ul>
                    <li>Search for people in your community</li>
                    <li>Find out who you have in common</li>
                    <li>Explore connections through your friends' networks</li>
                    <li>See a visualization of your social circle</li>
                </ul>
            </div>

            <div>
            <p text-align="center">To get started, click below to register. If you have already registered, you can log in.</p>
            <span class="auth-btn">
                <button type="button" class="universal-btn" onclick="window.location.href='/register'">register</button>
                <button type="button" class="universal-btn" onclick="window.location.href='/login'">login</button>
            </span>
            </div>
        </div>
    <?php endif; ?>
    </div>
</main>
