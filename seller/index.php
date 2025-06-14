<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - Supermarket POS</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS for Seller Dashboard (similar to Admin for consistency, with role-specific adjustments) -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5; /* Light background for the whole page */
            display: flex; /* Use flexbox for full-height layout */
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        /* Sidebar Styling */
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

        /* Main Content Area */
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

        /* Dashboard Cards */
        .dashboard-card {
            background-color: #ffffff;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            border: none; /* Remove default card border */
            transition: transform 0.2s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 2.5rem;
            color: #FFD700; /* Gold icon */
            margin-bottom: 1rem;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 0.5rem;
        }
        .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #343a40;
        }
        .card-text {
            color: #6c757d;
            font-size: 0.9rem;
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
                <a class="nav-link active" href="index.php">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pos.php">
                    <i class="fas fa-cash-register"></i> <span>POS Interface</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="sales_history.php">
                    <i class="fas fa-history"></i> <span>Sales History</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">
                    <i class="fas fa-user-circle"></i> <span>My Profile</span>
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
                <a class="navbar-brand me-auto" href="#">Seller Dashboard</a>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> Seller User
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Dashboard Content for Seller -->
        <div class="container-fluid">
            <div class="row">
                <!-- Quick Access Card to POS -->
                <div class="col-md-6 col-lg-4">
                    <a href="pos.php" style="text-decoration: none; color: inherit;">
                        <div class="dashboard-card text-center">
                            <i class="fas fa-cash-register card-icon"></i>
                            <h5 class="card-title">Start New Sale</h5>
                            <p class="card-text">Begin a new transaction.</p>
                            <span class="btn btn-sm btn-outline-secondary mt-2">Go to POS</span>
                        </div>
                    </a>
                </div>
                <!-- Today's Sales Count Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="dashboard-card text-center">
                        <i class="fas fa-chart-line card-icon"></i>
                        <h5 class="card-title">Today's Sales Count</h5>
                        <p class="card-value">25</p>
                        <p class="card-text">Number of transactions processed today.</p>
                    </div>
                </div>
                <!-- Today's Total Revenue Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="dashboard-card text-center">
                        <i class="fas fa-dollar-sign card-icon"></i>
                        <h5 class="card-title">Today's Revenue</h5>
                        <p class="card-value">$875.50</p>
                        <p class="card-text">Total amount of sales collected today.</p>
                    </div>
                </div>
                <!-- View Sales History Card -->
                <div class="col-md-6 col-lg-4">
                    <a href="sales_history.php" style="text-decoration: none; color: inherit;">
                        <div class="dashboard-card text-center">
                            <i class="fas fa-history card-icon"></i>
                            <h5 class="card-title">View Sales History</h5>
                            <p class="card-text">Review your past transactions.</p>
                            <span class="btn btn-sm btn-outline-secondary mt-2">Go to History</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // JavaScript for sidebar toggling and responsiveness (reused from header/footer)
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggler = document.getElementById('sidebarToggler');
            const sidebar = document.getElementById('sidebar');

            // Toggle sidebar on button click for larger screens
            if (sidebarToggler) {
                sidebarToggler.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    sidebarToggler.querySelector('i').classList.toggle('fa-angle-left');
                    sidebarToggler.querySelector('i').classList.toggle('fa-angle-right');
                });
            }

            // Handle Bootstrap Offcanvas for small screens (sidebar)
            var offcanvasSidebar = new bootstrap.Offcanvas(document.getElementById('sidebar'));

            // Show/hide offcanvas via top navbar toggler
            const navbarTogglerSidebar = document.querySelector('.navbar-toggler-sidebar');
            if (navbarTogglerSidebar) {
                navbarTogglerSidebar.addEventListener('click', function() {
                    offcanvasSidebar.toggle();
                });
            }

            // Prevent body scroll when offcanvas is open
            document.getElementById('sidebar').addEventListener('show.bs.offcanvas', function () {
                document.body.style.overflow = 'hidden';
            });
            document.getElementById('sidebar').addEventListener('hide.bs.offcanvas', function () {
                document.body.style.overflow = '';
            });

            // Adjust sidebar behavior based on screen size (for initial load and resize)
            function adjustSidebarOnResize() {
                if (window.innerWidth >= 768) {
                    // On larger screens, ensure sidebar is not off-canvas
                    if (sidebar.classList.contains('show')) {
                        offcanvasSidebar.hide();
                    }
                }
            }

            // Call on initial load
            adjustSidebarOnResize();
            // Call on window resize
            window.addEventListener('resize', adjustSidebarOnResize);
        });
    </script>
</body>
</html>
