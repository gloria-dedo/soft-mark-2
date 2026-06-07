<?php
$host = '127.0.0.1';
$username = 'root';
$password = ''; // Default XAMPP MySQL password is empty
$dbname = 'erp_store';

try {
    // Connect to MySQL server first (without specifying db) to create db if not exists
    $db = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create database if not exists
    $db->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database `$dbname` checked/created successfully.\n";

    // Now connect to the specific database
    $db->exec("USE `$dbname`");

    // Create Products table
    $db->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        rating DECIMAL(3,2) NOT NULL,
        image_url VARCHAR(255) NOT NULL,
        features TEXT NOT NULL
    ) ENGINE=InnoDB");
    echo "Table `products` checked/created successfully.\n";

    // Create Cart table
    $db->exec("CREATE TABLE IF NOT EXISTS cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        quantity INT NOT NULL DEFAULT 1,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    echo "Table `cart` checked/created successfully.\n";

    // Create Wishlist table
    $db->exec("CREATE TABLE IF NOT EXISTS wishlist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    echo "Table `wishlist` checked/created successfully.\n";

    // Truncate products and re-seed so image updates always apply
    $db->exec("SET FOREIGN_KEY_CHECKS=0");
    $db->exec("TRUNCATE TABLE cart");
    $db->exec("TRUNCATE TABLE wishlist");
    $db->exec("TRUNCATE TABLE products");
    $db->exec("SET FOREIGN_KEY_CHECKS=1");

    $products = [
        [
            'name'        => 'Accounting System Pro',
            'description' => 'Advanced financial management with automated reporting, tax compliance, and multi-currency support for global enterprises.',
            'price'       => 499.99,
            'rating'      => 4.8,
            'image_url'   => 'assets/images/image16.jpeg',
            'features'    => 'Multi-currency, Automated Tax, Advanced Reporting, API Access'
        ],
        [
            'name'        => 'Accounting System Standard',
            'description' => 'Essential accounting tools for small to medium businesses. Manage invoices, track expenses, and view cash flow.',
            'price'       => 199.99,
            'rating'      => 4.5,
            'image_url'   => 'assets/images/image17.jpeg',
            'features'    => 'Invoicing, Expense Tracking, Bank Reconciliation'
        ],
        [
            'name'        => 'Asset Management',
            'description' => 'Track, manage, and optimize your company assets. Features barcode scanning and lifecycle management.',
            'price'       => 299.99,
            'rating'      => 4.6,
            'image_url'   => 'assets/images/image7.jpeg',
            'features'    => 'Barcode Scanning, Lifecycle Tracking, Maintenance Scheduling'
        ],
        [
            'name'        => 'Inventory Management System',
            'description' => 'Real-time stock tracking, order fulfillment, and automated reordering to prevent stockouts.',
            'price'       => 349.99,
            'rating'      => 4.7,
            'image_url'   => 'assets/images/image6.jpeg',
            'features'    => 'Real-time Tracking, Automated Reordering, Multi-warehouse Support'
        ],
        [
            'name'        => 'HRIS Platform',
            'description' => 'Comprehensive Human Resources Information System for payroll, employee records, and performance management.',
            'price'       => 399.99,
            'rating'      => 4.9,
            'image_url'   => 'assets/images/image10.jpeg',
            'features'    => 'Payroll Processing, Employee Self-service, Performance Reviews'
        ],
        [
            'name'        => 'CRM Suite',
            'description' => 'Customer Relationship Management to boost sales, manage leads, and improve customer support interactions.',
            'price'       => 449.99,
            'rating'      => 4.8,
            'image_url'   => 'assets/images/image11.jpeg',
            'features'    => 'Lead Tracking, Email Integration, Support Ticketing'
        ],
        [
            'name'        => 'Supply Chain Management',
            'description' => 'End-to-end supply chain visibility from procurement to delivery. Manage vendors, purchase orders, and logistics.',
            'price'       => 379.99,
            'rating'      => 4.7,
            'image_url'   => 'assets/images/image15.jpeg',
            'features'    => 'Vendor Portal, PO Automation, Logistics Tracking, Demand Forecasting'
        ],
        [
            'name'        => 'Project Management Suite',
            'description' => 'Plan, track, and deliver projects on time and within budget. Supports agile and waterfall methodologies.',
            'price'       => 329.99,
            'rating'      => 4.6,
            'image_url'   => 'assets/images/image9.jpeg',
            'features'    => 'Gantt Charts, Resource Allocation, Time Tracking, Budget Management'
        ],
        [
            'name'        => 'Manufacturing MRP',
            'description' => 'Material Requirements Planning for production scheduling, bill of materials, and shop floor control.',
            'price'       => 549.99,
            'rating'      => 4.8,
            'image_url'   => 'assets/images/image13.jpeg',
            'features'    => 'Bill of Materials, Production Scheduling, Quality Control, Machine Utilisation'
        ],
        [
            'name'        => 'Payroll Management',
            'description' => 'Accurate, automated payroll processing with tax calculation, benefits administration and payslip generation.',
            'price'       => 249.99,
            'rating'      => 4.9,
            'image_url'   => 'assets/images/image3.jpeg',
            'features'    => 'Automated Tax, Employee Self-service, Direct Deposit, Benefits Management'
        ],
        [
            'name'        => 'Business Intelligence',
            'description' => 'Transform raw data into powerful insights with real-time analytics, custom reports, and interactive dashboards.',
            'price'       => 459.99,
            'rating'      => 4.7,
            'image_url'   => 'assets/images/image18.jpeg',
            'features'    => 'Custom Reports, KPI Tracking, Data Visualisation, Scheduled Exports'
        ],
        [
            'name'        => 'Point of Sale (POS)',
            'description' => 'Fast, reliable POS system with inventory sync, customer loyalty, and multi-location support.',
            'price'       => 229.99,
            'rating'      => 4.5,
            'image_url'   => 'assets/images/image4.jpeg',
            'features'    => 'Inventory Sync, Loyalty Programs, Receipt Printing, Offline Mode'
        ],
        [
            'name'        => 'Fleet Management',
            'description' => 'Track and manage your vehicle fleet with GPS tracking, maintenance scheduling, and fuel management.',
            'price'       => 319.99,
            'rating'      => 4.4,
            'image_url'   => 'assets/images/image19.jpeg',
            'features'    => 'GPS Tracking, Maintenance Alerts, Fuel Monitoring, Driver Management'
        ],
        [
            'name'        => 'Document Management',
            'description' => 'Centralised document repository with version control, digital signatures, and workflow approvals.',
            'price'       => 189.99,
            'rating'      => 4.6,
            'image_url'   => 'assets/images/image8.jpeg',
            'features'    => 'Version Control, e-Signatures, Approval Workflows, Full-text Search'
        ],
        [
            'name'        => 'Quality Management',
            'description' => 'Maintain product and process quality with inspection checklists, non-conformance reporting, and CAPA.',
            'price'       => 299.99,
            'rating'      => 4.5,
            'image_url'   => 'assets/images/image5.jpeg',
            'features'    => 'Inspection Checklists, CAPA Tracking, Audit Trails, ISO Compliance'
        ],
        [
            'name'        => 'Marketing Automation Suite',
            'description' => 'Create, manage, and track marketing campaigns across multiple channels from a single dashboard.',
            'price'       => 349.99,
            'rating'      => 4.5,
            'image_url'   => 'assets/images/image12.jpeg',
            'features'    => 'Email Marketing, Campaign Tracking, Lead Scoring'
        ],
        [
            'name'        => 'Enterprise Help Desk',
            'description' => 'Provide exceptional customer support with an integrated ticketing system and knowledge base.',
            'price'       => 199.99,
            'rating'      => 4.6,
            'image_url'   => 'assets/images/image14.jpeg',
            'features'    => 'Ticketing System, Knowledge Base, SLA Management'
        ],
    ];

    $insertStmt = $db->prepare("INSERT INTO products (name, description, price, rating, image_url, features) VALUES (:name, :description, :price, :rating, :image_url, :features)");

    foreach ($products as $product) {
        $insertStmt->execute($product);
        echo "  + Seeded: {$product['name']}\n";
    }

    echo "\nDatabase setup and seeded successfully with " . count($products) . " products.\n";

} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage() . "\n");
}
?>
