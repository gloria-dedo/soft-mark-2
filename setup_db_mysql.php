<?php
$host = '127.0.0.1';
$username = 'root';
$password = ''; // Default XAMPP MySQL password is empty
$dbname = 'erp_store';

try {
    // Connect to MySQL server first (without specifying db) to create db if not exists
    $db = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    // Check if products exist, if not, insert them
    $stmt = $db->query("SELECT COUNT(*) FROM products");
    if ($stmt->fetchColumn() == 0) {
        $products = [
            [
                'name' => 'Accounting System Pro',
                'description' => 'Advanced financial management with automated reporting, tax compliance, and multi-currency support for global enterprises.',
                'price' => 499.99,
                'rating' => 4.8,
                'image_url' => 'assets/images/accounting_pro.jpg',
                'features' => 'Multi-currency, Automated Tax, Advanced Reporting, API Access'
            ],
            [
                'name' => 'Accounting System Standard',
                'description' => 'Essential accounting tools for small to medium businesses. Manage invoices, track expenses, and view cash flow.',
                'price' => 199.99,
                'rating' => 4.5,
                'image_url' => 'assets/images/accounting_std.jpg',
                'features' => 'Invoicing, Expense Tracking, Bank Reconciliation'
            ],
            [
                'name' => 'Asset Management',
                'description' => 'Track, manage, and optimize your company assets. Features barcode scanning and lifecycle management.',
                'price' => 299.99,
                'rating' => 4.6,
                'image_url' => 'assets/images/asset_management.jpg',
                'features' => 'Barcode Scanning, Lifecycle Tracking, Maintenance Scheduling'
            ],
            [
                'name' => 'Inventory Management System',
                'description' => 'Real-time stock tracking, order fulfillment, and automated reordering to prevent stockouts.',
                'price' => 349.99,
                'rating' => 4.7,
                'image_url' => 'assets/images/inventory.jpg',
                'features' => 'Real-time Tracking, Automated Reordering, Multi-warehouse Support'
            ],
            [
                'name' => 'HRIS Platform',
                'description' => 'Comprehensive Human Resources Information System for payroll, employee records, and performance management.',
                'price' => 399.99,
                'rating' => 4.9,
                'image_url' => 'assets/images/hris.jpg',
                'features' => 'Payroll Processing, Employee Self-service, Performance Reviews'
            ],
            [
                'name' => 'CRM Suite',
                'description' => 'Customer Relationship Management to boost sales, manage leads, and improve customer support interactions.',
                'price' => 449.99,
                'rating' => 4.8,
                'image_url' => 'assets/images/crm.jpg',
                'features' => 'Lead Tracking, Email Integration, Support Ticketing'
            ]
        ];

        $insertStmt = $db->prepare("INSERT INTO products (name, description, price, rating, image_url, features) VALUES (:name, :description, :price, :rating, :image_url, :features)");
        
        foreach ($products as $product) {
            $insertStmt->execute($product);
        }
        echo "Database tables created and seeded successfully with products.\n";
    } else {
        echo "Database already has products seeded.\n";
    }

} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage() . "\n");
}
?>
