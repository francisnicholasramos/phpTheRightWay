<?php 

$session = new \Core\Session();
$message = $session->flash('error');

if ($message) {
    echo "<div class='message'>" . htmlspecialchars($message) . "</div>";
}
