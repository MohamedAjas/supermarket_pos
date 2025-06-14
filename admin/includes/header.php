<?php
/**
 * includes/header.php
 * This file contains the common HTML <head> section, CSS styling,
 * sidebar navigation, and top navigation bar for all admin panel pages.
 * It also includes session start and basic authentication/authorization checks.
 *
 * Variables expected to be set by the including page:
 * - $page_title: The title of the current page.
 * - $current_page: A string indicating the current active page for sidebar highlighting (e.g., 'dashboard', 'users', 'items', 'categories', 'suppliers', 'reports').
 */

session_start();

// --- Mock Database Configuration (In a real project, this would be in includes/config.php) ---
// Define database credentials and other configuration settings
// These are included here for header to work standalone in preview,
// but should ideally be in your global config.php file.
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', 'supermarket_pos_db');
if (!defined('DB_USER')) define('DB_USER', 'your_db_user'); // Replace with your actual database username
if (!defined('DB_PASS')) define('DB_PASS', 'your_db_password'); // Replace with your actual database password

// --- Basic Authentication and Authorization Check ---
// In a real project, consider moving this to a dedicated auth.php in includes/
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    // If not logged in, redirect to login page
    header('Location: ../login.php'); // Adjust path based on where header.php is included
    exit();
}

// Optional: Further role-based checks for specific pages can be done on individual pages.
// For admin panel, we generally ensure the user is an 'admin'
if ($_SESSION['user_role'] !== 'admin' && basename($_SERVER['PHP_SELF']) !== 'login.php' && basename($_SERVER['PHP_SELF']) !== 'logout.php') {
    // If a seller tries to access admin page, redirect to their dashboard or login
    if ($_SESSION['user_role'] === 'seller') {
        header('Location: ../seller/index.php'); // Redirect to seller dashboard
    } else {
        header('Location: ../login.php'); // Fallback to login
    }
    exit();
}

// Default values if not set by the including page
$page_title = $page_title ?? 'Admin Panel';
$current_page = $current_page ?? 'dashboard'; // Default active menu item

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Supermarket POS</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS for Admin Dashboard -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5; /* Light background for the whole page */
            display: flex; /* Use flexbox for full-height layout */
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        /* Sidebar Styling - Consistent with admin/index.php */
        .sidebar {
            width: 250px; /* Fixed width for sidebar */
            background-color: #ffffff;
            color: #343a40;
            padding: 1.5rem 1rem;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05); /* Subtle shadow */
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e9ecef;
            transition: all 0.3s ease; /* Smooth transition for sidebar collapse */
        }
        .sidebar.collapsed {
            width: 80px; /* Collapsed width */
        }
        .sidebar-header {
            text-align: center;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        .sidebar-header h3 {
            font-weight: 700;
            color: #FFD700; /* Gold for brand name */
            font-size: 1.5rem;
            margin-bottom: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar.collapsed .sidebar-header h3 {
            opacity: 0; /* Hide text when collapsed */
        }
        .sidebar-header .logo-icon {
            font-size: 2rem;
            color: #FFD700;
            display: none; /* Hidden by default */
        }
        .sidebar.collapsed .sidebar-header .logo-icon {
            display: inline-block; /* Show when collapsed */
        }

        .sidebar-nav .nav-link {
            color: #495057;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.75rem; /* Rounded links */
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: background-color 0.2s ease, color 0.2s ease, padding 0.2s ease;
        }
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background-color: rgba(255, 215, 0, 0.1); /* Light gold background */
            color: #e0b400; /* Gold text */
            font-weight: 600;
        }
        .sidebar-nav .nav-link i {
            font-size: 1.2rem;
            width: 20px; /* Fixed width for icons */
            text-align: center;
        }
        .sidebar.collapsed .sidebar-nav .nav-link span {
            display: none; /* Hide text in collapsed mode */
        }
        .sidebar.collapsed .sidebar-nav .nav-link {
            justify-content: center;
            padding: 0.75rem 0.5rem; /* Adjust padding for collapsed state */
        }
        .sidebar-toggler {
            background-color: #FFD700;
            border: none;
            color: #333;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 1.1rem;
            cursor: pointer;
            align-self: flex-end; /* Push to bottom right */
            margin-top: auto; /* Push to the bottom */
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .sidebar-toggler:hover {
            background-color: #e0b400;
            transform: scale(1.05);
        }

        /* Main Content Area - Consistent with admin/index.php */
        .main-content {
            flex-grow: 1; /* Take remaining width */
            padding: 1.5rem;
            background-color: #f8f9fa; /* Lighter background for content */
            transition: margin-left 0.3s ease; /* Smooth transition for content shift */
        }
        .main-content.shifted {
            margin-left: 80px; /* Adjust margin when sidebar is collapsed */
        }
        .navbar-top {
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .navbar-top .navbar-brand {
            font-weight: 700;
            color: #343a40;
            font-size: 1.7rem;
        }
        .navbar-top .nav-link {
            color: #6c757d;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        .navbar-top .nav-link:hover {
            color: #FFD700;
        }

        /* Custom Styles for Reports Table */
        .report-table-card { /* General card style for reports, users, items, suppliers */
            background-color: #ffffff;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: none;
        }
        .table {
            margin-top: 1.5rem;
            border-radius: 0.75rem; /* Rounded table corners */
            overflow: hidden; /* Ensures rounded corners apply to table content */
        }
        .table thead {
            background-color: #f8f9fa; /* Light header background */
        }
        .table th, .table td {
            vertical-align: middle;
            padding: 0.8rem 1rem;
            border-color: #e9ecef;
        }
        .table th {
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #fbfbfc; /* Very light stripe */
        }
        .report-summary-card { /* Specific card for report summaries */
            background-color: #ffffff;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            border: none;
        }
        .summary-item {
            font-size: 1.1rem;
            font-weight: 600;
            color: #343a40;
        }
        .summary-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #FFD700;
        }
        .btn-back {
            background-color: #6c757d; /* Gray for back button */
            border-color: #6c757d;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .btn-back:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .btn-action {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            margin-right: 0.5rem;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .btn-action:hover {
            transform: translateY(-1px);
        }
        .btn-edit {
            background-color: #e0b400; /* Gold for edit */
            border-color: #e0b400;
            color: #333;
        }
        .btn-edit:hover {
            background-color: #c9a400;
            border-color: #c9a400;
        }
        .btn-delete {
            background-color: #dc3545; /* Red for delete */
            border-color: #dc3545;
            color: #fff;
        }
        .btn-delete:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .btn-add-item, .btn-add-user, .btn-add-supplier, .btn-add-category {
            background-color: #28a745; /* Green for add */
            border-color: #28a745;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .btn-add-item:hover, .btn-add-user:hover, .btn-add-supplier:hover, .btn-add-category:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        .form-control-sm {
            border-radius: 0.5rem;
        }
        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select, .form-control-file {
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .form-control:focus, .form-select:focus, .form-control-file:focus {
            border-color: #e0b400; /* Gold focus border */
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25); /* Lighter gold shadow */
        }
        .btn-submit {
            background-color: #FFD700; /* Gold for form submit */
            border-color: #FFD700;
            color: #333;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .btn-submit:hover {
            background-color: #e0b400;
            transform: translateY(-2px);
        }
        .btn-cancel {
            background-color: #6c757d; /* Gray for cancel */
            border-color: #6c757d;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .btn-cancel:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -250px; /* Hide sidebar off-screen */
                height: 100%;
                z-index: 1030; /* Above navbar */
            }
            .sidebar.show {
                left: 0; /* Show sidebar */
            }
            .main-content {
                margin-left: 0 !important; /* No margin on small screens */
            }
            .navbar-toggler-sidebar {
                display: block !important; /* Show hamburger icon */
            }
            .sidebar.collapsed {
                width: 250px; /* On small screens, always show full width when 'show' */
            }
            .sidebar.collapsed .sidebar-header h3 {
                opacity: 1; /* Always show text when on small screens */
            }
            .sidebar.collapsed .sidebar-header .logo-icon {
                display: none; /* Hide icon when full width on small screens */
            }
            .sidebar.collapsed .sidebar-nav .nav-link span {
                display: inline; /* Always show text on small screens */
            }
            .sidebar.collapsed .sidebar-nav .nav-link {
                justify-content: flex-start;
                padding: 0.75rem 1rem;
            }
            .sidebar-toggler {
                display: none; /* Hide collapsible button on small screens */
            }
        }
        /* Mobile hamburger icon for sidebar */
        .navbar-toggler-sidebar {
            border: none;
            font-size: 1.5rem;
            color: #343a40;
            display: none; /* Hidden by default on larger screens */
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>POS System</h3>
            <i class="fas fa-store logo-icon"></i> <!-- Icon for collapsed view -->
        </div>
        <ul class="nav flex-column sidebar-nav">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>" href="../index.php">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'users') ? 'active' : ''; ?>" href="../users/index.php">
                    <i class="fas fa-users"></i> <span>User Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'items') ? 'active' : ''; ?>" href="../items/index.php">
                    <i class="fas fa-boxes"></i> <span>Item Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'categories') ? 'active' : ''; ?>" href="../categories/index.php">
                    <i class="fas fa-tags"></i> <span>Categories</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'suppliers') ? 'active' : ''; ?>" href="../suppliers/index.php">
                    <i class="fas fa-truck-moving"></i> <span>Suppliers</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'reports') ? 'active' : ''; ?>" href="../reports/index.php">
                    <i class="fas fa-chart-line"></i> <span>Reports</span>
                </a>
            </li>
        </ul>
        <button class="sidebar-toggler" id="sidebarToggler"><i class="fas fa-angle-left"></i></button>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <nav class="navbar navbar-top navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler-sidebar" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle sidebar">
                    <span class="fas fa-bars"></span>
                </button>
                <a class="navbar-brand me-auto" href="#"><?php echo htmlspecialchars($page_title); ?></a>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin User'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../../logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
