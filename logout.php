<?php
/**
 * logout.php
 * Handles user logout by destroying the session and redirecting to the login page.
 * This script is accessible from both admin and seller interfaces.
 */

// Start a new session or resume an existing one
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redirect to the login page (relative to the root, as logout.php is in the root)
header('Location: login.php');
exit();
?>
