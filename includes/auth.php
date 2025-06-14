<?php
/**
 * includes/auth.php
 * This file contains functions for user authentication and authorization checks.
 * It verifies if a user is logged in and if they have the necessary role
 * to access specific pages or functionalities within the Supermarket POS system.
 */

// Ensure session is started before using session variables
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary configuration and database connection files
// These paths are relative from 'includes/'
require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/functions.php'; // For redirect() and display_session_messages()


/**
 * Checks if a user is logged in.
 * Redirects to the login page if not authenticated.
 */
function check_login() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['user_role'])) {
        // If not logged in, redirect to the main login page
        // Assumes login.php is one directory up from the includes folder,
        // or a path relative to the root like /login.php
        redirect('../../login.php', 'Please log in to access this page.', 'error');
    }
}

/**
 * Checks if the logged-in user has the 'admin' role.
 * Calls check_login() first, then verifies the role.
 * Redirects if not an admin.
 */
function check_auth_admin() {
    check_login(); // Ensure user is logged in first

    if ($_SESSION['user_role'] !== 'admin') {
        // If not an admin, redirect based on their actual role or to a generic access denied page
        if ($_SESSION['user_role'] === 'seller') {
            redirect('../../seller/index.php', 'Access denied. You do not have administrator privileges.', 'error');
        } else {
            // Fallback for unexpected roles or if role is somehow invalid
            redirect('../../login.php', 'Access denied. Invalid user role.', 'error');
        }
    }
    // If the function reaches here, the user is an authenticated admin.
}

/**
 * Checks if the logged-in user has the 'seller' role.
 * Calls check_login() first, then verifies the role.
 * Redirects if not a seller.
 */
function check_auth_seller() {
    check_login(); // Ensure user is logged in first

    if ($_SESSION['user_role'] !== 'seller') {
        // If not a seller, redirect based on their actual role or to a generic access denied page
        if ($_SESSION['user_role'] === 'admin') {
            redirect('../../admin/index.php', 'Access denied. You do not have seller privileges.', 'error');
        } else {
            // Fallback for unexpected roles or if role is somehow invalid
            redirect('../../login.php', 'Access denied. Invalid user role.', 'error');
        }
    }
    // If the function reaches here, the user is an authenticated seller.
}

// You can add more specific authorization functions here as needed,
// e.g., check_permission($permission_name) etc.
?>
