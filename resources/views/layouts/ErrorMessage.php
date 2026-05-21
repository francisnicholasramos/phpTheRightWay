<?php 

$session = new \Core\Session();
$_fk = $flashKey ?? 'error';
$message = $session->flash($_fk);

if ($message) {
    $class = $_fk === 'success' ? 'message success' : 'message';
    echo "<div class='{$class}'>" . htmlspecialchars($message) . "</div>";
}
