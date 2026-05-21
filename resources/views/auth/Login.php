<?php $title = "Login"; ?>

<?php require_once __DIR__ . '/../layouts/Header.php'; ?>

<main class="auth">
    <?php if ($_SERVER['REQUEST_URI'] === '/login'): ?>
    <div class="auth-nav">
      <a href="/">Main</a>
      <a href="/login">Login</a>
      <a href="/register">Register</a>
    </div>
    <?php else: ?>
      <form action="/login" method="POST">
          <div>
              <label for="email">Email:</label>
              <input type="text" id="email" name="email" />
          </div>
          <div>
              <label for="password">Password:</label>
              <input type="password" id="password" name="password" />
          </div>

          <?php $flashKey = 'login_error'; include __DIR__ . '/../layouts/ErrorMessage.php'; ?>

          <div>
              <button type="button" onclick="window.location.href='/register'">register</button>
              <button type="submit">login</button>
          </div>
      </form>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/../components/welcome.php'; ?>

<?php require_once __DIR__ . '/../layouts/Footer.php'; ?>
