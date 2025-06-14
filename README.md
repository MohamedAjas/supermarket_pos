Supermarket Point of Sale (POS) System
Project Overview
This is a web-based Supermarket Point of Sale (POS) system designed to manage daily supermarket operations, including sales, inventory, user management (Admin & Seller roles), supplier management, category management, and reporting. The system aims to provide an intuitive and efficient interface for both administrative staff and sales personnel.

Features
Admin Panel
Dashboard: Overview of key metrics (e.g., total sales, popular items, low stock alerts).

User Management: Add, edit, and manage seller accounts. Soft deletion (deactivation) of users to maintain data integrity.

Item Management: Add, edit, and manage product details including name, SKU, barcode, price, cost, stock quantity, minimum stock level, and images. Soft deletion (deactivation) of items.

Category Management: Manage product categories for better organization.

Supplier Management: Manage supplier information. Soft deletion (deactivation) of suppliers with a check for associated active items.

Reports: Generate various reports (e.g., Sales Reports by date/seller, Stock Reports by category/supplier/status, User Activity Reports).

Seller Panel
Dashboard: Quick overview of daily sales metrics for the logged-in seller.

POS Interface: A streamlined interface for processing sales, including product search, dynamic shopping cart, quantity adjustments, payment processing, and change calculation.

Sales History: View past sales transactions for the logged-in seller.

My Profile: View and update personal account information, including password changes.

Technologies Used
Frontend:

HTML5

CSS3 (Custom styles + Bootstrap 5)

JavaScript (Vanilla JS + Bootstrap 5 JS Bundle)

Google Fonts (Inter)

Font Awesome (Icons)

Backend:

PHP (for server-side logic, database interaction, session management, file uploads)

Database:

MySQL (or MariaDB)

Web Server:

Apache or Nginx (configured to serve PHP)

Project Structure
.
├── admin/                        # Admin panel files
│   ├── categories/               # Category Management
│   │   ├── index.php             # List categories
│   │   ├── add.php               # Add new category (frontend placeholder)
│   │   ├── edit.php              # Edit category (frontend placeholder)
│   │   └── delete.php            # Delete/Deactivate category (PHP logic)
│   ├── items/                    # Item (Product) Management
│   │   ├── index.php             # List items
│   │   ├── add.php               # Add new item (frontend placeholder)
│   │   ├── edit.php              # Edit item (frontend placeholder)
│   │   └── delete.php            # Delete/Deactivate item (PHP logic)
│   ├── reports/                  # Reporting Module
│   │   ├── index.php             # Report selection page
│   │   ├── sales_report.php      # Sales report details (frontend placeholder)
│   │   ├── stock_report.php      # Stock report details (frontend placeholder)
│   │   └── user_activity_report.php # User activity report (placeholder)
│   ├── suppliers/                # Supplier Management
│   │   ├── index.php             # List suppliers
│   │   ├── add.php               # Add new supplier (frontend placeholder)
│   │   ├── edit.php              # Edit supplier (frontend placeholder)
│   │   └── delete.php            # Delete/Deactivate supplier (PHP logic)
│   └── index.php                 # Admin Dashboard homepage
├── assets/                       # Static assets (self-hosted libraries)
│   ├── bootstrap/
│   │   ├── css/
│   │   │   └── bootstrap.min.css # Placeholder for Bootstrap CSS
│   │   └── js/
│   │       └── bootstrap.bundle.min.js # Placeholder for Bootstrap JS
│   ├── jquery/
│   │   └── jquery.min.js         # Placeholder for jQuery (if needed)
│   └── fonts/                    # Custom fonts (if any)
├── includes/                     # Reusable PHP includes
│   ├── auth.php                  # Authentication and authorization functions
│   ├── config.php                # Global configuration settings (DB credentials etc.)
│   ├── db.php                    # Database connection script (PDO)
│   ├── functions.php             # Common utility functions (sanitization, redirect, messages)
│   └── header.php                # Global HTML header, navigation, and common styles for Admin
│   └── footer.php                # Global HTML footer and common JS for Admin
├── public/                       # Publicly accessible assets and pages
│   ├── css/
│   │   └── style.css             # Global public CSS styles
│   ├── js/
│   │   ├── main.js               # Global public JavaScript (custom alerts, general validation)
│   │   └── pos_script.js         # POS interface specific JavaScript
│   ├── images/
│   │   ├── item_images/          # Directory for uploaded product images
│   │   └── favicon.ico           # Website favicon
│   └── index.php                 # Public landing page
├── seller/                       # Seller panel files
│   ├── includes/                 # Reusable PHP includes for Seller Panel
│   │   ├── header.php            # Global HTML header, navigation, and common styles for Seller
│   │   └── footer.php            # Global HTML footer and common JS for Seller
│   ├── index.php                 # Seller Dashboard homepage
│   ├── pos.php                   # Point of Sale (POS) Interface
│   ├── profile.php               # Seller profile management
│   └── sales_history.php         # Seller's sales history
├── login.php                     # User login page
├── logout.php                    # User logout script
└── README.md                     # Project README file (this file)

Setup Instructions
1. Prerequisites
Web server (Apache, Nginx) with PHP 7.4+ installed

MySQL or MariaDB database server

Composer (optional, for PHP dependency management)

2. Database Setup
Create Database:
Create a new MySQL database, e.g., supermarket_pos_db.

CREATE DATABASE supermarket_pos_db;
USE supermarket_pos_db;

Create Tables:
Execute the following SQL queries to create the necessary tables.

-- users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL, -- Store hashed passwords
    full_name VARCHAR(100),
    role ENUM('admin', 'seller') NOT NULL DEFAULT 'seller',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert initial mock admin and seller users (replace passwords with hashed ones in production)
INSERT INTO users (username, email, password_hash, full_name, role, status) VALUES
('admin_user', 'admin@example.com', 'adminpass', 'Administrator', 'admin', 'active'), -- IMPORTANT: Replace 'adminpass' with a strong hash using password_hash()
('seller_john', 'john.doe@example.com', 'sellerpass', 'John Doe', 'seller', 'active'); -- IMPORTANT: Replace 'sellerpass' with a strong hash using password_hash()


-- categories table
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- suppliers table
CREATE TABLE suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(100) NOT NULL UNIQUE,
    contact_person VARCHAR(100),
    phone_number VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- items table
CREATE TABLE items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    category_id INT,
    supplier_id INT,
    sku VARCHAR(50) UNIQUE,
    barcode VARCHAR(50) UNIQUE,
    price DECIMAL(10, 2) NOT NULL,
    cost_price DECIMAL(10, 2),
    stock_quantity INT NOT NULL DEFAULT 0,
    min_stock_level INT DEFAULT 0,
    image_path VARCHAR(255),
    description TEXT,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id)
);

-- sales table (to store overall sale transactions)
CREATE TABLE sales (
    sale_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- Seller who made the sale
    sale_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2) NOT NULL,
    amount_paid DECIMAL(10, 2) NOT NULL,
    change_given DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('cash', 'card', 'mobile_pay') NOT NULL,
    customer_name VARCHAR(100), -- Optional
    status ENUM('completed', 'refunded', 'pending') NOT NULL DEFAULT 'completed',
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- sale_items table (to store individual items within a sale)
CREATE TABLE sale_items (
    sale_item_id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_sale DECIMAL(10, 2) NOT NULL, -- Price of item at the time of sale
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(sale_id),
    FOREIGN KEY (item_id) REFERENCES items(item_id)
);

3. Configure Database Connection
Open includes/config.php.

Update the DB_USER and DB_PASS constants with your MySQL database username and password. Ensure DB_NAME matches the database name you created.

define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');

4. Web Server Configuration
Place the project files in your web server's document root (e.g., htdocs for Apache, www for Nginx).

Ensure your web server is configured to process PHP files.

Security Recommendation: Configure your web server to prevent direct access to the includes/ directory.

5. Accessing the Application
Open your web browser.

Navigate to the URL where you have deployed the project:

Public Landing Page: http://localhost/your_project_folder/public/index.php (or just http://localhost/your_project_folder/ if configured as root)

Login Page: http://localhost/your_project_folder/login.php

6. Default Login Credentials (for testing)
Admin:

Username: admin_user

Password: adminpass

Seller:

Username: seller_user

Password: sellerpass

IMPORTANT SECURITY NOTE:
The provided password_hash in the INSERT statements are plain text for initial setup convenience. In a production environment, you MUST replace 'adminpass' and 'sellerpass' with securely hashed passwords generated using PHP's password_hash() function.
For example, to generate a hash for 'adminpass':
echo password_hash('adminpass', PASSWORD_DEFAULT);
Then use the generated hash in your INSERT statement.

Future Enhancements
Implement robust password hashing using password_hash() and password_verify().

Complete backend logic for all CRUD operations in admin and seller panels.

Implement client-side and server-side validation for all forms.

Add pagination and search/filter functionality for all data tables.

Develop a comprehensive reporting module with data visualization.

Implement image upload functionality on the server side for item images.

Add a proper customer management module.

Improve UI/UX with more advanced JavaScript features (e.g., AJAX for dynamic updates).

Error logging and display improvements.

User session management improvements (e.g., session timeouts, brute-force protection).

Add unit and integration tests.

Implement receipt generation and printing.

Barcode scanning integration.