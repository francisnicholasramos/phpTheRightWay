<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>thesocialnetwork</title>
        <link href="/css/main.css" rel="stylesheet">
        <link href="/css/header.css" rel="stylesheet">
        <script>
            window.userId = <?php echo json_encode($_SESSION['user_id'] ?? null); ?>;
            let notificationCount = 0;

            async function updateNotificationBadge() {
                try {
                    const response = await fetch('/notifications/count');
                    const data = await response.json();
                    notificationCount = data.count || 0;
                    
                    const badge = document.getElementById('notif-badge');
                    if (badge) {
                        if (notificationCount > 0) {
                            badge.textContent = 'notifications(' + notificationCount + ')';
                        } else {
                            badge.textContent = 'notifications';
                        }
                    }
                } catch (error) {
                    console.error('Error updating notification badge:', error);
                }
            }

            // Update badge on page load
            if (window.userId) {
                document.addEventListener('DOMContentLoaded', updateNotificationBadge);
                // Poll for new notifications every 5 seconds (fallback if WebSocket fails)
                setInterval(updateNotificationBadge, 100);
            }
        </script>
    </head>
    <body>
    <header>
        <div class="niko">
            <img src="/assets/4000.png" class="binary-niko" alt="logo"/>
        </div>
        <div class="social-network">
            <img src="/assets/social_network.png" class="logo" alt="logo"/>
            <nav class="nav-links">
                <?php if (!isset($_SESSION['user_id'])) : ?>
                    <li><a href="/login">login</a></li>
                    <li><a href="/signup">register</a></li>
                <?php else: ?>
                    <li><a href="/feed">home</a></li>
                    <li><a href="">profile</a></li>
                    <li><a href="">messages</a></li>
                    <li><a href="" id="notif-badge">notifications</a></li>
                    <li><a href="">friends</a></li>
                <?php endif; ?>
            </nav>
        </div>
    </header>
