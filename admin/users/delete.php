<?php
/**
 * delete.php
 * Handles the deletion/deactivation of a user account in the Supermarket POS system.
 * This script deactivates a user by setting their 'status' to 'inactive'
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
    header('Location: ../../login.php'); // Redirect to login page
    exit();
}

// Check if user_id is provided in the URL (GET request)
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT); // Sanitize as integer

    // Ensure the ID is a valid positive integer
    if ($user_id === false || $user_id <= 0) {
        $_SESSION['error_message'] = 'Invalid user ID provided.';
        header('Location: index.php'); // Redirect back to user list
        exit();
    }

    try {
        $pdo = connect_db(); // Establish database connection

        // Prevent an admin from deactivating their own account
        if ($user_id == $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'You cannot deactivate your own administrator account.';
            header('Location: index.php');
            exit();
        }

        // Prepare SQL statement to update user status to 'inactive'
        // Using prepared statements to prevent SQL injection
        $stmt = $pdo->prepare("UPDATE users SET status = 'inactive' WHERE user_id = :user_id AND role != 'admin'"); // Add role check to prevent accidental admin deactivation
        $stmt->execute([':user_id' => $user_id]);

        // Check if any row was affected (meaning the user was found and updated)
        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = 'User successfully deactivated.';
        } else {
            // No rows affected might mean user not found or already inactive, or it was an admin user
            $_SESSION['error_message'] = 'Failed to deactivate user. User not found or is an administrator.';
        }

    } catch (PDOException $e) {
        // Log the error for debugging purposes
        error_log("User deactivation error: " . $e->getMessage());
        $_SESSION['error_message'] = 'An unexpected database error occurred. Please try again later.';
    }
} else {
    $_SESSION['error_message'] = 'No user ID specified for deactivation.';
}

// Redirect back to the user management index page
header('Location: index.php');
exit();
?>
