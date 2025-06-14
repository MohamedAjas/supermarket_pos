<?php
/**
 * includes/config.php
 * This file contains global configuration settings for the Supermarket POS system.
 * It defines constants for database connection parameters and other system-wide settings.
 *
 * IMPORTANT: For security, ensure this file is placed outside the publicly accessible
 * web root directory (e.g., in the 'includes' folder, which should not be directly
 * accessible via a browser).
 */

// --- Database Configuration ---
// Define your database connection details here.
// Replace 'localhost', 'supermarket_pos_db', 'your_db_user', and 'your_db_password'
// with your actual database server host, database name, username, and password.
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'supermarket_pos_db');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'your_db_user'); // REMEMBER TO CHANGE THIS
}
if (!defined('DB_PASS')) {
    define('DB_PASS', 'your_db_password'); // REMEMBER TO CHANGE THIS
}

// --- Application Settings ---
// Define other global application settings as needed.

// Base URL for the application (useful for generating absolute links)
// Example: define('BASE_URL', 'http://localhost/supermarket_pos/');
// You might dynamically set this or leave it commented if relative paths are sufficient.
// define('BASE_URL', 'http://localhost/supermarket_pos/');


// Number of items per page for pagination (e.g., in lists for admin panel)
if (!defined('ITEMS_PER_PAGE')) {
    define('ITEMS_PER_PAGE', 10);
}

// Tax rate for POS calculations (e.g., 0.05 for 5%)
if (!defined('TAX_RATE')) {
    define('TAX_RATE', 0.05);
}

// Default timezone for date/time functions
if (!defined('DEFAULT_TIMEZONE')) {
    define('DEFAULT_TIMEZONE', 'Asia/Colombo'); // Example: Set to your local timezone
    date_default_timezone_set(DEFAULT_TIMEZONE);
}

// Path for uploaded item images (relative to the root of the project where this file is included)
// This path assumes a structure like 'supermarket_pos/public/images/item_images/'
if (!defined('ITEM_IMAGE_UPLOAD_PATH')) {
    // Determine path based on script location. This is relative to the project root.
    // Assuming config.php is in 'includes/' and images are in 'public/images/item_images/'
    // This path would be used by server-side scripts for saving files.
    define('ITEM_IMAGE_UPLOAD_PATH', dirname(__DIR__) . '/public/images/item_images/');
}

// Max allowed file size for image uploads (in bytes, e.g., 2MB)
if (!defined('MAX_IMAGE_SIZE')) {
    define('MAX_IMAGE_SIZE', 2 * 1024 * 1024); // 2 MB
}

// Allowed image MIME types for uploads
if (!defined('ALLOWED_IMAGE_TYPES')) {
    define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
}

?>
