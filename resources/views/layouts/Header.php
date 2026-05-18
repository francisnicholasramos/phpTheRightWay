<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>socialnetwork</title>
        <link href="/css/main.css" rel="stylesheet">
        <link href="/css/header.css" rel="stylesheet">
        <link href="/css/index.css" rel="stylesheet">
    </head>
    <body>
    <header>
        <div class="niko">
            <img src="/assets/binary.png" class="binary-niko" loading="lazy" alt="logo"/>
        </div>
        <div class="mobile-logo">
            <p>[ socialnetwork ]</p>
        </div>
        <div class="social-network">
            <img src="/assets/social_network.png" class="logo" loading="lazy" alt="logo"/>
            <nav class="nav-links">
                <?php if (!isset($_SESSION['user_id'])) : ?>
                    <li><a href="/login">login</a></li>
                    <li><a href="/register">register</a></li>
                <?php else: ?>
                    <div class="hide-on-mobile">
                        <li><a href="/feed">home</a></li>
                        <li>
                            <a href="/u/<?= \App\Services\AuthService::user()->username ?>">profile</a>
                        </li>
                        <li><a href="/messages">messages</a></li>
                        <li><a href="/notifications" id="notification-link">notifications</a></li>
                    </div>
                    <button id="mobile-search">search</button>
                    <form action="/logout" method="post">
                        <button type="submit">logout</button>
                    </form>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    
    <div class="layout-container">
    
    <?php if (isset($_SESSION['user_id'])) : ?>
    <div class="mobile-nav">
        <li><a href="/feed">home</a></li>
        <li>
            <a href="/u/<?= \App\Services\AuthService::user()->username ?>">profile</a>
        </li>
        <li><a href="/messages">messages</a></li>
        <li><a href="/notifications" id="notification-link-mobile">notifications</a></li>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <script src="<?= rtrim($_ENV['SOCKET_URL'] ?? 'http://localhost:3000', '/') ?>/socket.io/socket.io.js"></script>
        <script>
            window.NOTIF = {
                userId: '<?= htmlspecialchars($_SESSION['user_id']) ?>',
                socketUrl: '<?= $_ENV['SOCKET_URL'] ?? 'http://localhost:3000' ?>'
            };
        </script>
        <script src="/js/mobile-search.js"></script>
        <script src="/js/notification-badge.js"></script>
    <?php endif; ?>
