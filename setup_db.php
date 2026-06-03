<?php
$dbPath = __DIR__ . '/database/erp_store.sqlite';

try {
    $db = new PDO("sqlite:" . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Products table
    $db->exec("CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT NOT NULL,
        price REAL NOT NULL,
        rating REAL NOT NULL,
        image_url TEXT NOT NULL,
        features TEXT NOT NULL
    )");

    // Create Cart table (simplified for single user/session)
    $db->exec("CREATE TABLE IF NOT EXISTS cart (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL DEFAULT 1,
        FOREIGN KEY (product_id) REFERENCES products(id)
    )");

    // Create Wishlist table (simplified for single user/session)
    $db->exec("CREATE TABLE IF NOT EXISTS wishlist (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id)
    )");

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
        echo "Database setup and seeded successfully.\n";
    } else {
        echo "Database already exists and is seeded.\n";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>
