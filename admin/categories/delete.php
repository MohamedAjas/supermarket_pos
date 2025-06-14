<?php
/**
 * delete.php
 * Handles the deletion/deactivation of a category in the Supermarket POS system.
 * This script deactivates a category by setting its 'status' to 'inactive'
 * rather than performing a hard delete, to preserve data integrity and historical data.
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
    header('Location: ../../login.php'); // Redirect to login page (assuming two levels up from admin/categories/)
    exit();
}

// Check if category_id is provided in the URL (GET request)
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $category_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT); // Sanitize as integer

    // Ensure the ID is a valid positive integer
    if ($category_id === false || $category_id <= 0) {
        $_SESSION['error_message'] = 'Invalid category ID provided.';
        header('Location: index.php'); // Redirect back to category list
        exit();
    }

    try {
        $pdo = connect_db(); // Establish database connection

        // --- Important: Check for associated active items before deactivating ---
        // Prevents orphaned items or issues with sales history
        $stmt_check_items = $pdo->prepare("SELECT COUNT(*) FROM items WHERE category_id = :category_id AND status = 'active'");
        $stmt_check_items->execute([':category_id' => $category_id]);
        $active_items_count = $stmt_check_items->fetchColumn();

        if ($active_items_count > 0) {
            $_SESSION['error_message'] = 'Cannot deactivate category. ' . $active_items_count . ' active items are still associated with this category. Please reassign or deactivate items first.';
            header('Location: index.php');
            exit();
        }

        // Prepare SQL statement to update category status to 'inactive'
        // Using prepared statements to prevent SQL injection
        $stmt = $pdo->prepare("UPDATE categories SET status = 'inactive' WHERE category_id = :category_id");
        $stmt->execute([':category_id' => $category_id]);

        // Check if any row was affected (meaning the category was found and updated)
        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = 'Category successfully deactivated.';
        } else {
            // No rows affected might mean category not found or already inactive.
            $_SESSION['error_message'] = 'Failed to deactivate category. Category not found or already inactive.';
        }

    } catch (PDOException $e) {
        // Log the error for debugging purposes
        error_log("Category deactivation error: " . $e->getMessage());
        $_SESSION['error_message'] = 'An unexpected database error occurred. Please try again later.';
    }
} else {
    $_SESSION['error_message'] = 'No category ID specified for deactivation.';
}

// Redirect back to the category management index page
header('Location: index.php');
exit();
?>
