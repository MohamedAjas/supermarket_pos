<?php
/**
 * login.php
 * Handles user authentication for the Supermarket POS system.
 * Allows users to log in as either administrators or sellers.
 */

// Start a new session or resume an existing one
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary configuration, database connection, and functions files
// The paths are relative from the root directory where login.php resides
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Check if the user is already logged in, redirect them to their respective dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header('Location: admin/index.php');
        exit();
    } elseif ($_SESSION['user_role'] === 'seller') {
        header('Location: seller/index.php');
        exit();
    }
}

$error_message = ''; // Initialize error message

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanitize user inputs
    $username_or_email = sanitize_input($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? ''; // Passwords are not sanitized with htmlspecialchars before hashing/verification

    // 2. Validate inputs (basic check)
    if (empty($username_or_email) || empty($password)) {
        redirect('login.php', 'Please enter both username/email and password.', 'error');
    } else {
        try {
            $pdo = connect_db(); // Connect to the database

            // 3. Prepare SQL query to fetch user by username or email
            // Use PDO prepared statements to prevent SQL injection
            $stmt = $pdo->prepare("SELECT user_id, username, email, password_hash, role, status FROM users WHERE username = :username OR email = :email LIMIT 1");
            $stmt->execute([
                ':username' => $username_or_email,
                ':email' => $username_or_email
            ]);
            $user = $stmt->fetch();

            // 4. Verify user and password
            if ($user) {
                // For a real application, 'password_hash' would store a securely hashed password.
                // You would use password_verify($password, $user['password_hash']) here.
                // For demonstration, let's assume a simple direct comparison if 'password_hash' is a plain text password (NOT RECOMMENDED FOR PRODUCTION!)
                // If you plan to implement proper password hashing:
                // if (password_verify($password, $user['password_hash'])) {

                // --- MOCK PASSWORD VERIFICATION (REPLACE WITH REAL HASHING IN PRODUCTION) ---
                // For this example, we'll assume a dummy user:
                // admin_user with password 'adminpass' (role: admin)
                // seller_user with password 'sellerpass' (role: seller)
                $mock_password_valid = false;
                if ($user['username'] === 'admin_user' && $password === 'adminpass' && $user['role'] === 'admin') {
                    $mock_password_valid = true;
                } elseif ($user['username'] === 'seller_user' && $password === 'sellerpass' && $user['role'] === 'seller') {
                    $mock_password_valid = true;
                }
                // --- END MOCK PASSWORD VERIFICATION ---

                if ($mock_password_valid) { // Replace with password_verify if using hashing
                    if ($user['status'] === 'active') {
                        // 5. Authentication successful, set session variables
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['user_role'] = $user['role'];
                        $_SESSION['last_login'] = time(); // Record login time

                        // 6. Redirect based on user role
                        if ($user['role'] === 'admin') {
                            redirect('admin/index.php', 'Welcome back, Admin!', 'success');
                        } elseif ($user['role'] === 'seller') {
                            redirect('seller/index.php', 'Welcome back, Seller!', 'success');
                        }
                    } else {
                        // User account is inactive
                        redirect('login.php', 'Your account is inactive. Please contact support.', 'error');
                    }
                } else {
                    // Invalid password
                    redirect('login.php', 'Invalid username/email or password.', 'error');
                }
            } else {
                // User not found
                redirect('login.php', 'Invalid username/email or password.', 'error');
            }

        } catch (PDOException $e) {
            // Log database errors
            error_log("Login database error: " . $e->getMessage());
            redirect('login.php', 'An unexpected error occurred. Please try again later.', 'error');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Supermarket POS</title>
    <!-- Favicon -->
    <link rel="icon" href="public/images/favicon.ico" type="image/x-icon">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom Global CSS -->
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            background: linear-gradient(135deg, #f0f2f5 0%, #e0e5ec 100%); /* Soft gradient background */
        }
        .login-container {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 100%;
            text-align: left; /* Align form elements to left */
        }
        .login-title {
            font-weight: 700;
            color: #FFD700; /* Gold for main title */
            margin-bottom: 2rem;
            text-align: center;
        }
        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .form-control:focus {
            border-color: #e0b400; /* Gold focus */
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25); /* Light gold glow */
        }
        .btn-login {
            background-color: #FFD700; /* Gold button */
            border-color: #FFD700;
            color: #333; /* Darker text for contrast on gold */
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            transition: background-color 0.3s ease, border-color 0.3s ease, transform 0.2s ease;
            width: 100%; /* Full width button */
        }
        .btn-login:hover {
            background-color: #e0b400; /* Slightly darker gold on hover */
            border-color: #e0b400;
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(255, 215, 0, 0.3);
        }
        .form-check-input:focus {
            border-color: rgba(255, 193, 7, 0.5);
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
        }
        .form-check-input:checked {
            background-color: #FFD700;
            border-color: #FFD700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="login-container">
                    <h2 class="login-title"><i class="fas fa-store me-2"></i> POS System Login</h2>

                    <?php echo display_session_messages(); // Display success/error messages ?>

                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="username_or_email" class="form-label">Username or Email</label>
                            <input type="text" class="form-control" id="username_or_email" name="username_or_email" placeholder="Enter username or email" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                        </div>
                        <div class="mb-4 form-check text-center">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                            <label class="form-check-label" for="remember_me">Remember Me</label>
                        </div>
                        <button type="submit" class="btn btn-login">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom Global JS -->
    <script src="public/js/main.js"></script>
</body>
</html>
