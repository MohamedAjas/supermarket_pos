<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Interface - Supermarket POS</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS for Seller POS Interface -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5; /* Light background for the whole page */
            display: flex; /* Use flexbox for full-height layout */
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        /* Sidebar Styling - Consistent */
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

        /* POS Specific Styles */
        .pos-container {
            display: flex;
            gap: 1.5rem; /* Space between left and right panels */
            min-height: calc(100vh - 80px); /* Adjust height to fill screen minus navbar */
        }
        .pos-left-panel {
            flex: 3; /* Takes 60% of width, adjust as needed */
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        .pos-right-panel {
            flex: 2; /* Takes 40% of width, adjust as needed */
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }

        .search-bar .form-control {
            border-radius: 0.75rem;
            padding: 0.85rem 1rem;
        }
        .product-list-container {
            margin-top: 1.5rem;
            flex-grow: 1; /* Allow products list to grow */
            overflow-y: auto; /* Scroll for product list */
            max-height: calc(100vh - 250px); /* Adjust based on header/footer height */
            padding-right: 10px; /* Space for scrollbar */
        }
        .product-card {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .product-card:hover {
            background-color: #e2e6ea;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        .product-card img {
            width: 60px;
            height: 60px;
            border-radius: 0.5rem;
            object-fit: cover;
        }
        .product-info {
            flex-grow: 1;
        }
        .product-name {
            font-weight: 600;
            color: #343a40;
            margin-bottom: 0.2rem;
        }
        .product-price {
            font-weight: 700;
            color: #007bff; /* Blue for price */
            font-size: 1.1rem;
        }
        .add-to-cart-btn {
            background-color: #28a745;
            border-color: #28a745;
            color: #fff;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            transition: background-color 0.2s ease;
        }
        .add-to-cart-btn:hover {
            background-color: #218838;
        }

        /* Shopping Cart Styles */
        .cart-table-container {
            flex-grow: 1;
            overflow-y: auto;
            max-height: calc(100vh - 400px); /* Adjust height for scrolling cart */
            margin-bottom: 1rem;
            padding-right: 10px; /* Space for scrollbar */
        }
        .cart-table th, .cart-table td {
            vertical-align: middle;
            font-size: 0.9rem;
            padding: 0.6rem 0.75rem;
        }
        .cart-table td.item-name {
            font-weight: 600;
            color: #343a40;
        }
        .cart-table .quantity-controls button {
            background: none;
            border: 1px solid #ced4da;
            border-radius: 0.5rem;
            padding: 0.2rem 0.5rem;
            color: #495057;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }
        .cart-table .quantity-controls button:hover {
            background-color: #e9ecef;
        }
        .cart-table .quantity-controls input {
            width: 40px;
            text-align: center;
            border: 1px solid #ced4da;
            border-radius: 0.5rem;
            padding: 0.2rem;
            margin: 0 5px;
        }
        .cart-table .remove-item-btn {
            color: #dc3545;
            background: none;
            border: none;
            font-size: 1rem;
            transition: color 0.2s ease;
        }
        .cart-table .remove-item-btn:hover {
            color: #c82333;
        }

        /* Order Summary & Payment */
        .order-summary, .payment-section {
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
            margin-top: 1rem;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .summary-label {
            font-weight: 600;
            color: #555;
        }
        .summary-value {
            font-weight: 700;
            color: #343a40;
        }
        .summary-total {
            font-size: 1.5rem;
            font-weight: 700;
            color: #FFD700; /* Gold for total */
        }
        .payment-input .form-control {
            font-size: 1.2rem;
            font-weight: 700;
            color: #343a40;
        }
        .btn-process-sale {
            background-color: #28a745;
            border-color: #28a745;
            color: #fff;
            padding: 0.85rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 700;
            width: 100%;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .btn-process-sale:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        .btn-cancel-sale {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
            padding: 0.85rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 700;
            width: 100%;
            margin-top: 0.5rem;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .btn-cancel-sale:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }


        /* Responsive adjustments */
        @media (max-width: 992px) { /* Adjust for smaller desktops/tablets */
            .pos-container {
                flex-direction: column; /* Stack panels vertically */
            }
            .pos-left-panel, .pos-right-panel {
                flex: none; /* Remove flex sizing */
                width: 100%; /* Take full width */
            }
            .pos-right-panel {
                margin-top: 1.5rem; /* Add space when stacked */
            }
            .product-list-container, .cart-table-container {
                max-height: 400px; /* Fixed height for scrollable areas on smaller screens */
            }
        }
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
                <a class="nav-link" href="index.php">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="pos.php">
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
                <a class="navbar-brand me-auto" href="#">POS Interface</a>
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

        <!-- POS Content -->
        <div class="container-fluid pos-container">
            <!-- Left Panel: Product Search and List -->
            <div class="pos-left-panel">
                <h4 class="mb-3">Products</h4>
                <div class="input-group mb-3 search-bar">
                    <input type="text" class="form-control" placeholder="Search by name, SKU, or barcode..." aria-label="Search products">
                    <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                </div>
                <div class="product-list-container">
                    <!-- Example Product Cards - Dynamically loaded in real application -->
                    <div class="product-card" data-item-id="101" data-item-name="Fresh Red Apples" data-item-price="2.99">
                        <img src="https://placehold.co/60x60/e0e5ec/343a40?text=Apple" alt="Product Image">
                        <div class="product-info">
                            <div class="product-name">Fresh Red Apples</div>
                            <div class="product-price">$2.99</div>
                        </div>
                        <button class="add-to-cart-btn"><i class="fas fa-cart-plus me-1"></i> Add</button>
                    </div>
                    <div class="product-card" data-item-id="102" data-item-name="Organic Milk (1L)" data-item-price="4.50">
                        <img src="https://placehold.co/60x60/e0e5ec/343a40?text=Milk" alt="Product Image">
                        <div class="product-info">
                            <div class="product-name">Organic Milk (1L)</div>
                            <div class="product-price">$4.50</div>
                        </div>
                        <button class="add-to-cart-btn"><i class="fas fa-cart-plus me-1"></i> Add</button>
                    </div>
                    <div class="product-card" data-item-id="103" data-item-name="Whole Wheat Bread" data-item-price="3.25">
                        <img src="https://placehold.co/60x60/e0e5ec/343a40?text=Bread" alt="Product Image">
                        <div class="product-info">
                            <div class="product-name">Whole Wheat Bread</div>
                            <div class="product-price">$3.25</div>
                        </div>
                        <button class="add-to-cart-btn"><i class="fas fa-cart-plus me-1"></i> Add</button>
                    </div>
                    <div class="product-card" data-item-id="104" data-item-name="Bananas (per lb)" data-item-price="0.79">
                        <img src="https://placehold.co/60x60/e0e5ec/343a40?text=Banana" alt="Product Image">
                        <div class="product-info">
                            <div class="product-name">Bananas (per lb)</div>
                            <div class="product-price">$0.79</div>
                        </div>
                        <button class="add-to-cart-btn"><i class="fas fa-cart-plus me-1"></i> Add</button>
                    </div>
                    <div class="product-card" data-item-id="105" data-item-name="Ground Coffee (250g)" data-item-price="7.99">
                        <img src="https://placehold.co/60x60/e0e5ec/343a40?text=Coffee" alt="Product Image">
                        <div class="product-info">
                            <div class="product-name">Ground Coffee (250g)</div>
                            <div class="product-price">$7.99</div>
                        </div>
                        <button class="add-to-cart-btn"><i class="fas fa-cart-plus me-1"></i> Add</button>
                    </div>
                    <div class="product-card" data-item-id="106" data-item-name="Cheddar Cheese (200g)" data-item-price="5.49">
                        <img src="https://placehold.co/60x60/e0e5ec/343a40?text=Cheese" alt="Product Image">
                        <div class="product-info">
                            <div class="product-name">Cheddar Cheese (200g)</div>
                            <div class="product-price">$5.49</div>
                        </div>
                        <button class="add-to-cart-btn"><i class="fas fa-cart-plus me-1"></i> Add</button>
                    </div>
                     <div class="product-card" data-item-id="107" data-item-name="Sparkling Water (1L)" data-item-price="1.29">
                        <img src="https://placehold.co/60x60/e0e5ec/343a40?text=Water" alt="Product Image">
                        <div class="product-info">
                            <div class="product-name">Sparkling Water (1L)</div>
                            <div class="product-price">$1.29</div>
                        </div>
                        <button class="add-to-cart-btn"><i class="fas fa-cart-plus me-1"></i> Add</button>
                    </div>
                     <div class="product-card" data-item-id="108" data-item-name="Chocolate Bar (Dark)" data-item-price="2.10">
                        <img src="https://placehold.co/60x60/e0e5ec/343a40?text=Choco" alt="Product Image">
                        <div class="product-info">
                            <div class="product-name">Chocolate Bar (Dark)</div>
                            <div class="product-price">$2.10</div>
                        </div>
                        <button class="add-to-cart-btn"><i class="fas fa-cart-plus me-1"></i> Add</button>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Shopping Cart, Summary, Payment -->
            <div class="pos-right-panel">
                <h4 class="mb-3">Shopping Cart</h4>
                <div class="cart-table-container">
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <!-- Cart items will be dynamically added here by JavaScript -->
                            <tr data-item-id="101">
                                <td class="item-name">Fresh Red Apples</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center quantity-controls">
                                        <button class="btn btn-sm btn-minus"><i class="fas fa-minus"></i></button>
                                        <input type="number" class="form-control form-control-sm" value="2" min="1">
                                        <button class="btn btn-sm btn-plus"><i class="fas fa-plus"></i></button>
                                    </div>
                                </td>
                                <td class="text-end">$2.99</td>
                                <td class="text-end">$5.98</td>
                                <td><button class="remove-item-btn"><i class="fas fa-trash-alt"></i></button></td>
                            </tr>
                            <tr data-item-id="103">
                                <td class="item-name">Whole Wheat Bread</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center quantity-controls">
                                        <button class="btn btn-sm btn-minus"><i class="fas fa-minus"></i></button>
                                        <input type="number" class="form-control form-control-sm" value="1" min="1">
                                        <button class="btn btn-sm btn-plus"><i class="fas fa-plus"></i></button>
                                    </div>
                                </td>
                                <td class="text-end">$3.25</td>
                                <td class="text-end">$3.25</td>
                                <td><button class="remove-item-btn"><i class="fas fa-trash-alt"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Order Summary -->
                <div class="order-summary mt-auto"> <!-- Push to bottom -->
                    <div class="summary-row">
                        <span class="summary-label">Subtotal:</span>
                        <span class="summary-value" id="subtotal">$9.23</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Tax (5%):</span>
                        <span class="summary-value" id="tax">$0.46</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label summary-total">Total:</span>
                        <span class="summary-value summary-total" id="total">$9.69</span>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="payment-section mt-3">
                    <h5 class="mb-3">Payment</h5>
                    <div class="mb-3">
                        <label for="amount_paid" class="form-label">Amount Paid ($)</label>
                        <input type="number" step="0.01" min="0" class="form-control payment-input" id="amount_paid" placeholder="Enter amount paid" value="10.00">
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method">
                            <option value="cash" selected>Cash</option>
                            <option value="card">Card</option>
                            <option value="mobile_pay">Mobile Pay</option>
                        </select>
                    </div>
                    <div class="summary-row mb-3">
                        <span class="summary-label">Change Due:</span>
                        <span class="summary-value" id="change_due">$0.31</span>
                    </div>
                    <button class="btn btn-process-sale"><i class="fas fa-check-circle me-2"></i> Process Sale</button>
                    <button class="btn btn-cancel-sale"><i class="fas fa-times-circle me-2"></i> Cancel Sale</button>
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


            // --- POS specific JavaScript for dynamic interactions ---
            const cartItemsContainer = document.getElementById('cart-items');
            const productCards = document.querySelectorAll('.product-card');
            const subtotalEl = document.getElementById('subtotal');
            const taxEl = document.getElementById('tax');
            const totalEl = document.getElementById('total');
            const amountPaidInput = document.getElementById('amount_paid');
            const changeDueEl = document.getElementById('change_due');

            let cart = []; // Array to store cart items: { id, name, price, quantity }
            const TAX_RATE = 0.05; // 5% tax

            // Function to render cart items
            function renderCart() {
                cartItemsContainer.innerHTML = ''; // Clear current cart display
                let currentSubtotal = 0;

                if (cart.length === 0) {
                    cartItemsContainer.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">Cart is empty. Add items from the left panel.</td></tr>';
                } else {
                    cart.forEach(item => {
                        const itemSubtotal = item.price * item.quantity;
                        currentSubtotal += itemSubtotal;

                        const row = `
                            <tr data-item-id="${item.id}">
                                <td class="item-name">${item.name}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center quantity-controls">
                                        <button class="btn btn-sm btn-minus" data-item-id="${item.id}"><i class="fas fa-minus"></i></button>
                                        <input type="number" class="form-control form-control-sm" value="${item.quantity}" min="1" data-item-id="${item.id}">
                                        <button class="btn btn-sm btn-plus" data-item-id="${item.id}"><i class="fas fa-plus"></i></button>
                                    </div>
                                </td>
                                <td class="text-end">$${item.price.toFixed(2)}</td>
                                <td class="text-end">$${itemSubtotal.toFixed(2)}</td>
                                <td><button class="remove-item-btn" data-item-id="${item.id}"><i class="fas fa-trash-alt"></i></button></td>
                            </tr>
                        `;
                        cartItemsContainer.innerHTML += row;
                    });
                }
                updateSummary(currentSubtotal);
            }

            // Function to update summary totals
            function updateSummary(currentSubtotal) {
                const tax = currentSubtotal * TAX_RATE;
                const total = currentSubtotal + tax;
                subtotalEl.textContent = `$${currentSubtotal.toFixed(2)}`;
                taxEl.textContent = `$${tax.toFixed(2)}`;
                totalEl.textContent = `$${total.toFixed(2)}`;

                // Update change due based on amount paid
                updateChangeDue();
            }

            // Function to update change due
            function updateChangeDue() {
                const totalAmount = parseFloat(totalEl.textContent.replace('$', ''));
                const amountPaid = parseFloat(amountPaidInput.value) || 0;
                const change = amountPaid - totalAmount;
                changeDueEl.textContent = `$${Math.max(0, change).toFixed(2)}`;
                // Optionally add styling if change is negative or payment is insufficient
                changeDueEl.style.color = change < 0 ? '#dc3545' : '#28a745';
            }


            // Add event listeners for product cards (Add to Cart)
            productCards.forEach(card => {
                const addButton = card.querySelector('.add-to-cart-btn');
                addButton.addEventListener('click', function(event) {
                    event.stopPropagation(); // Prevent card click event from firing
                    const itemId = card.dataset.itemId;
                    const itemName = card.dataset.itemName;
                    const itemPrice = parseFloat(card.dataset.itemPrice);

                    addItemToCart(itemId, itemName, itemPrice);
                });
            });

            // Function to add item to cart
            function addItemToCart(id, name, price) {
                const existingItem = cart.find(item => item.id === id);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    cart.push({ id, name, price, quantity: 1 });
                }
                renderCart();
            }

            // Event delegation for cart item actions (quantity change, remove)
            cartItemsContainer.addEventListener('click', function(event) {
                const target = event.target;
                const itemId = target.closest('tr').dataset.itemId;

                if (target.closest('.btn-minus')) {
                    updateItemQuantity(itemId, -1);
                } else if (target.closest('.btn-plus')) {
                    updateItemQuantity(itemId, 1);
                } else if (target.closest('.remove-item-btn')) {
                    removeItemFromCart(itemId);
                }
            });

            cartItemsContainer.addEventListener('change', function(event) {
                const target = event.target;
                if (target.tagName === 'INPUT' && target.type === 'number') {
                    const itemId = target.dataset.itemId;
                    const newQuantity = parseInt(target.value);
                    if (newQuantity >= 1) {
                         const itemIndex = cart.findIndex(item => item.id === itemId);
                         if (itemIndex !== -1) {
                             cart[itemIndex].quantity = newQuantity;
                             renderCart();
                         }
                    } else {
                        // If quantity becomes less than 1, remove the item
                        removeItemFromCart(itemId);
                    }
                }
            });

            // Function to update item quantity in cart
            function updateItemQuantity(id, change) {
                const itemIndex = cart.findIndex(item => item.id === id);
                if (itemIndex !== -1) {
                    cart[itemIndex].quantity += change;
                    if (cart[itemIndex].quantity <= 0) {
                        cart.splice(itemIndex, 1); // Remove if quantity is 0 or less
                    }
                    renderCart();
                }
            }

            // Function to remove item from cart
            function removeItemFromCart(id) {
                cart = cart.filter(item => item.id !== id);
                renderCart();
            }

            // Event listener for amount paid input to update change due
            amountPaidInput.addEventListener('input', updateChangeDue);

            // Initial render of cart (with dummy data for display)
            renderCart();

            // Handle Process Sale Button
            document.querySelector('.btn-process-sale').addEventListener('click', function() {
                if (cart.length === 0) {
                    alert('Cart is empty. Please add items to process a sale.'); // Replace with custom modal later
                    return;
                }
                const totalAmount = parseFloat(totalEl.textContent.replace('$', ''));
                const amountPaid = parseFloat(amountPaidInput.value) || 0;

                if (amountPaid < totalAmount) {
                    alert('Amount paid is less than total. Please collect full amount.'); // Replace with custom modal later
                    return;
                }

                const paymentMethod = document.getElementById('payment_method').value;
                const changeGiven = Math.max(0, amountPaid - totalAmount);

                // Here, you would send this data to a PHP script (e.g., process_sale.php)
                const saleData = {
                    cart: cart,
                    total_amount: totalAmount,
                    amount_paid: amountPaid,
                    change_given: changeGiven,
                    payment_method: paymentMethod
                };

                console.log('Processing Sale:', saleData);
                alert('Sale processed successfully! Change: $' + changeGiven.toFixed(2)); // Replace with a success modal and receipt logic
                
                // Reset cart and UI after successful sale
                cart = [];
                amountPaidInput.value = '';
                renderCart(); // Re-render to clear cart
            });

            // Handle Cancel Sale Button
            document.querySelector('.btn-cancel-sale').addEventListener('click', function() {
                if (confirm('Are you sure you want to cancel this sale? All items in the cart will be removed.')) { // Replace with custom modal later
                    cart = [];
                    amountPaidInput.value = '';
                    renderCart();
                    alert('Sale cancelled.'); // Replace with custom modal later
                }
            });

        });
    </script>
</body>
</html>
