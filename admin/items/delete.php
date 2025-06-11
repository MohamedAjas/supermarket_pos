<?php
/**
 * delete.php
 * Handles the deletion/deactivation of an item in the Supermarket POS system.
 * This script deactivates an item by setting its 'status' to 'inactive'
 * rather than performing a hard delete, to preserve data integrity.
 * Accessible only by administrators.
 */

// Start a new session or resume an existing one
session_start();

// --- Mock Database Configuration (In a real project, this would be in includes/config.php) ---
// Define database credentials and other configuration settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'supermarket_pos_db');
define('DB_USER', 'your_db_user'); // Replace with your actual database username
define('DB_PASS', 'your_db_password'); // Replace with your actual database password

// --- Mock Database Connection (In a real project, this would be in includes/db.php) ---
/**
 * Establishes a PDO database connection.
 * @return PDO Returns a PDO object on success, or exits with an error on failure.
 */
function connect_db() {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch rows as associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Disable emulation for better security and performance
    ];
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log('Database connection failed: ' . $e->getMessage());
        exit('Database connection failed. Please try again later.');
    }
}

// --- Authentication and Authorization Check (In a real project, this would be in includes/auth.php) ---
// This is a simplified check. A full auth.php would handle redirects and more robust checks.
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['error_message'] = 'Access denied. You must be logged in as an administrator.';
    header('Location: ../../login.php'); // Redirect to login page (assuming two levels up from admin/items/)
    exit();
}

// Check if item_id is provided in the URL (GET request)
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $item_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT); // Sanitize as integer

    // Ensure the ID is a valid positive integer
    if ($item_id === false || $item_id <= 0) {
        $_SESSION['error_message'] = 'Invalid item ID provided.';
        header('Location: index.php'); // Redirect back to item list
        exit();
    }

    try {
        $pdo = connect_db(); // Establish database connection

        // Prepare SQL statement to update item status to 'inactive'
        // Using prepared statements to prevent SQL injection
        $stmt = $pdo->prepare("UPDATE items SET status = 'inactive' WHERE item_id = :item_id");
        $stmt->execute([':item_id' => $item_id]);

        // Check if any row was affected (meaning the item was found and updated)
        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = 'Item successfully deactivated.';
        } else {
            // No rows affected might mean item not found or already inactive.
            $_SESSION['error_message'] = 'Failed to deactivate item. Item not found or already inactive.';
        }

    } catch (PDOException $e) {
        // Log the error for debugging purposes
        error_log("Item deactivation error: " . $e->getMessage());
        $_SESSION['error_message'] = 'An unexpected database error occurred. Please try again later.';
    }
} else {
    $_SESSION['error_message'] = 'No item ID specified for deactivation.';
}

// Redirect back to the item management index page
header('Location: index.php');
exit();
?>
