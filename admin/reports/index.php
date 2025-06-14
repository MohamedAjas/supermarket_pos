<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Supermarket POS</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS for Admin Dashboard (reused for consistency) -->
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

        /* Custom Styles for Reports Section */
        .reports-card {
            background-color: #ffffff;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 1.5rem;
        }
        .report-section-header {
            font-weight: 600;
            color: #343a40;
            margin-bottom: 1.5rem;
        }
        .btn-view-report {
            background-color: #FFD700; /* Gold */
            border-color: #FFD700;
            color: #333;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .btn-view-report:hover {
            background-color: #e0b400;
            transform: translateY(-2px);
            color: #333;
        }
        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .form-control:focus, .form-select:focus {
            border-color: #e0b400; /* Gold focus border */
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25); /* Lighter gold shadow */
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
    <!-- Sidebar - Consistent with admin/index.php -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>POS System</h3>
            <i class="fas fa-store logo-icon"></i> <!-- Icon for collapsed view -->
        </div>
        <ul class="nav flex-column sidebar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../users/index.php">
                    <i class="fas fa-users"></i> <span>User Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../items/index.php">
                    <i class="fas fa-boxes"></i> <span>Item Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../categories/index.php">
                    <i class="fas fa-tags"></i> <span>Categories</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../suppliers/index.php">
                    <i class="fas fa-truck-moving"></i> <span>Suppliers</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="index.php">
                    <i class="fas fa-chart-line"></i> <span>Reports</span>
                </a>
            </li>
        </ul>
        <button class="sidebar-toggler" id="sidebarToggler"><i class="fas fa-angle-left"></i></button>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar - Consistent with admin/index.php -->
        <nav class="navbar navbar-top navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler-sidebar" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle sidebar">
                    <span class="fas fa-bars"></span>
                </button>
                <a class="navbar-brand me-auto" href="#">Reports</a>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> Admin User
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

        <!-- Reports Content -->
        <div class="container-fluid">
            <div class="reports-card">
                <h3 class="mb-4">Generate Reports</h3>

                <!-- Sales Report Section -->
                <div class="mb-5">
                    <h4 class="report-section-header"><i class="fas fa-chart-bar me-2"></i> Sales Reports</h4>
                    <form action="sales_report.php" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="sales_start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="sales_start_date" name="start_date">
                        </div>
                        <div class="col-md-4">
                            <label for="sales_end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="sales_end_date" name="end_date">
                        </div>
                        <div class="col-md-4">
                            <label for="sales_seller" class="form-label">Seller (Optional)</label>
                            <select class="form-select" id="sales_seller" name="seller_id">
                                <option value="">All Sellers</option>
                                <option value="1">John Doe</option>
                                <option value="2">Jane Smith</option>
                                <!-- Dynamically load sellers -->
                            </select>
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-view-report"><i class="fas fa-file-invoice-dollar me-2"></i> View Sales Report</button>
                        </div>
                    </form>
                </div>

                <hr class="my-5">

                <!-- Stock Report Section -->
                <div class="mb-5">
                    <h4 class="report-section-header"><i class="fas fa-boxes me-2"></i> Stock Reports</h4>
                    <form action="stock_report.php" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="stock_category" class="form-label">Category (Optional)</label>
                            <select class="form-select" id="stock_category" name="category_id">
                                <option value="">All Categories</option>
                                <option value="1">Fruits</option>
                                <option value="2">Dairy</option>
                                <!-- Dynamically load categories -->
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="stock_supplier" class="form-label">Supplier (Optional)</label>
                            <select class="form-select" id="stock_supplier" name="supplier_id">
                                <option value="">All Suppliers</option>
                                <option value="1">Supplier A</option>
                                <option value="2">Supplier B</option>
                                <!-- Dynamically load suppliers -->
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="stock_status" class="form-label">Stock Status</label>
                            <select class="form-select" id="stock_status" name="status">
                                <option value="">All Statuses</option>
                                <option value="low_stock">Low Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="active">Active (In Stock)</option>
                            </select>
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-view-report"><i class="fas fa-warehouse me-2"></i> View Stock Report</button>
                        </div>
                    </form>
                </div>

                <hr class="my-5">

                <!-- User Activity Report Section (Example) -->
                <div class="mb-3">
                    <h4 class="report-section-header"><i class="fas fa-user-check me-2"></i> User Activity Report</h4>
                    <form action="user_activity_report.php" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="user_activity_start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="user_activity_start_date" name="start_date">
                        </div>
                        <div class="col-md-4">
                            <label for="user_activity_end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="user_activity_end_date" name="end_date">
                        </div>
                         <div class="col-md-4">
                            <label for="user_activity_user" class="form-label">User (Optional)</label>
                            <select class="form-select" id="user_activity_user" name="user_id">
                                <option value="">All Users</option>
                                <option value="1">Admin User</option>
                                <option value="2">John Doe (Seller)</option>
                                <!-- Dynamically load users -->
                            </select>
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-view-report"><i class="fas fa-user-clock me-2"></i> View User Activity</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // JavaScript for sidebar toggling (reused from admin/index.php)
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggler = document.getElementById('sidebarToggler');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            sidebarToggler.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                sidebarToggler.querySelector('i').classList.toggle('fa-angle-left');
                sidebarToggler.querySelector('i').classList.toggle('fa-angle-right');
            });

            // Handle Bootstrap Offcanvas for small screens (sidebar)
            var offcanvasSidebar = new bootstrap.Offcanvas(document.getElementById('sidebar'));
            document.querySelector('.navbar-toggler-sidebar').addEventListener('click', function() {
                offcanvasSidebar.toggle();
            });

            // Prevent body scroll when offcanvas is open
            document.getElementById('sidebar').addEventListener('show.bs.offcanvas', function () {
                document.body.style.overflow = 'hidden';
            });
            document.getElementById('sidebar').addEventListener('hide.bs.offcanvas', function () {
                document.body.style.overflow = '';
            });
        });
    </script>
</body>
</html>
