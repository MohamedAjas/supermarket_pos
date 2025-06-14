<?php
/**
 * includes/functions.php
 * This file contains global utility functions for the Supermarket POS system.
 * These functions are designed to be reusable across various parts of the application,
 * promoting code reusability and maintainability.
 */

// Ensure config.php and db.php are included if any function depends on them
// dirname(__FILE__) gives the directory of the current file (includes/)
// '/config.php' is one level up relative to includes/
// require_once dirname(__FILE__) . '/config.php';
// require_once dirname(__FILE__) . '/db.php';


/**
 * Sanitizes input data to prevent XSS attacks and other vulnerabilities.
 * It removes leading/trailing whitespace, strips HTML tags, and converts special characters to HTML entities.
 *
 * @param string $data The input string to sanitize.
 * @return string The sanitized string.
 */
function sanitize_input($data) {
    $data = trim($data); // Remove whitespace from the beginning and end of string
    $data = stripslashes($data); // Remove backslashes
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); // Convert special characters to HTML entities
    return $data;
}

/**
 * Redirects the user to a specified URL.
 * It's safer to use this function to ensure headers are sent correctly and script execution stops.
 *
 * @param string $url The URL to redirect to.
 * @param string $message (Optional) A message to store in session (e.g., for success/error alerts).
 * @param string $message_type (Optional) Type of message ('success', 'error', 'warning', 'info').
 */
function redirect($url, $message = null, $message_type = 'info') {
    if ($message !== null) {
        // Assuming session is already started in header.php or main script
        // or ensure session_start() is called before this function if used stand-alone
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[$message_type . '_message'] = $message;
    }
    header('Location: ' . $url);
    exit(); // Always exit after a header redirect
}

/**
 * Displays a session-based message (success, error, warning, info) and then clears it.
 * This function would typically be called in your HTML templates.
 *
 * @return string HTML for the alert message, or empty string if no message.
 */
function display_session_messages() {
    $html = '';
    $message_types = ['success', 'error', 'warning', 'info'];

    foreach ($message_types as $type) {
        $session_key = $type . '_message';
        if (isset($_SESSION[$session_key])) {
            $message = htmlspecialchars($_SESSION[$session_key]);
            $html .= "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>";
            $html .= $message;
            $html .= "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            $html .= "</div>";
            unset($_SESSION[$session_key]); // Clear the message after displaying
        }
    }
    return $html;
}

/**
 * Generates a unique barcode/SKU based on a prefix and timestamp (example).
 * In a real system, you might generate sequential numbers or check for uniqueness against the DB.
 *
 * @param string $prefix A prefix for the SKU/barcode (e.g., 'PROD', 'SUP').
 * @return string A generated unique identifier.
 */
function generate_unique_code($prefix = 'ITEM') {
    // A simple example: Prefix + current timestamp + random number
    return $prefix . '_' . time() . '_' . mt_rand(1000, 9999);
}

// Add more generic functions as needed, e.g.:
// - Function for consistent date formatting
// - Function to handle file uploads (more complex, might be a dedicated class/file)
// - Functions for currency formatting
// - Helper for pagination logic
?>
