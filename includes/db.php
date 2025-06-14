<?php
/**
 * includes/db.php
 * This file is responsible for establishing a secure PDO database connection.
 * It uses configuration parameters defined in includes/config.php.
 *
 * This file should be included at the beginning of any script that needs
 * to interact with the database.
 */

// Ensure config.php is included to get database credentials
// The path is relative to where db.php is located (inside 'includes')
require_once dirname(__FILE__) . '/config.php';

/**
 * Establishes a PDO database connection.
 * @return PDO Returns a PDO object on success.
 * @throws PDOException If the database connection fails.
 */
function connect_db() {
    // Data Source Name (DSN) string for MySQL
    // Using defined constants for host and database name
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    // Options for PDO connection
    $options = [
        // Throw exceptions on errors, which allows for robust error handling
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Fetch rows as associative arrays by default
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Disable emulation of prepared statements for better security and performance
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        // Create a new PDO instance using the defined credentials and options
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Log the exact error message for debugging purposes
        error_log('Database connection failed: ' . $e->getMessage());

        // In a production environment, display a generic error message to the user
        // Do NOT expose sensitive database error details to the public.
        exit('Database connection failed. Please try again later.');
    }
}

// Example usage (optional, typically removed from actual db.php)
// You would call connect_db() in your main scripts as needed.
/*
try {
    $pdo = connect_db();
    // Connection successful, you can now use $pdo for database operations
    // echo "Database connected successfully!";
} catch (PDOException $e) {
    // Error already handled by the function
    // echo "Failed to connect to database.";
}
*/
?>
