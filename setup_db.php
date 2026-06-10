<?php
$host     = '127.0.0.1';
$username = 'root';
$password = '';
$dbname   = 'erp_store';

try {
    $db = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create database
    $db->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database `$dbname` checked/created.\n";

    $db->exec("USE `$dbname`");

    // Products table — name is UNIQUE so we can upsert without wiping data
    $db->exec("CREATE TABLE IF NOT EXISTS products (
        id        INT AUTO_INCREMENT PRIMARY KEY,
        name      VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        price     DECIMAL(10,2) NOT NULL,
        rating    DECIMAL(3,2) NOT NULL,
        image_url VARCHAR(255) NOT NULL,
        features  TEXT NOT NULL,
        UNIQUE KEY uq_name (name)
    ) ENGINE=InnoDB");

    // Add unique key to existing installs that don't have it yet
    try { $db->exec("ALTER TABLE products ADD UNIQUE KEY uq_name (name)"); }
    catch (PDOException $e) { /* already exists */ }

    echo "Table `products` ready.\n";

    // Cart table
    $db->exec("CREATE TABLE IF NOT EXISTS cart (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        quantity   INT NOT NULL DEFAULT 1,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    echo "Table `cart` ready.\n";

    // Wishlist table
    $db->exec("CREATE TABLE IF NOT EXISTS wishlist (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    echo "Table `wishlist` ready.\n";

    // Product reviews
    $db->exec("CREATE TABLE IF NOT EXISTS reviews (
        id             INT AUTO_INCREMENT PRIMARY KEY,
        product_id     INT NOT NULL,
        reviewer_name  VARCHAR(100) NOT NULL,
        rating         TINYINT NOT NULL,
        comment        TEXT NOT NULL,
        created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        CHECK (rating BETWEEN 1 AND 5)
    ) ENGINE=InnoDB");
    echo "Table `reviews` ready.\n";

    // -------------------------------------------------------
    // Product catalogue
    // To update an image: change image_url here and re-run.
    // Cart & wishlist data is NOT affected.
    // -------------------------------------------------------
    $products = [
        [
            'name'        => 'Accounting System Pro',
            'description' => 'Advanced financial management with automated reporting, tax compliance, and multi-currency support for global enterprises.',
            'price'       => 499.99,
            'rating'      => 4.8,
            'image_url'   => 'assets/images/accountingPro.svg',
            'features'    => 'Multi-currency, Automated Tax, Advanced Reporting, API Access'
        ],
        [
            'name'        => 'Accounting System Standard',
            'description' => 'Essential accounting tools for small to medium businesses. Manage invoices, track expenses, and view cash flow.',
            'price'       => 199.99,
            'rating'      => 4.5,
            'image_url'   => 'assets/images/accountingstandard.svg',
            'features'    => 'Invoicing, Expense Tracking, Bank Reconciliation'
        ],
        [
            'name'        => 'Asset Management',
            'description' => 'Track, manage, and optimize your company assets. Features barcode scanning and lifecycle management.',
            'price'       => 299.99,
            'rating'      => 4.6,
            'image_url'   => 'assets/images/asset.svg',
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
            'image_url'   => 'assets/images/crm.svg',
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
            'image_url'   => 'assets/images/businessIntelligence.svg',
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

    // Upsert: inserts new products, updates existing ones by name.
    // Cart and wishlist rows are untouched.
    $upsert = $db->prepare("
        INSERT INTO products (name, description, price, rating, image_url, features)
        VALUES (:name, :description, :price, :rating, :image_url, :features)
        ON DUPLICATE KEY UPDATE
            image_url   = VALUES(image_url),
            description = VALUES(description),
            price       = VALUES(price),
            rating      = VALUES(rating),
            features    = VALUES(features)
    ");

    foreach ($products as $p) {
        $upsert->execute($p);
        echo "  + Synced: {$p['name']}\n";
    }

    echo "\nDone. " . count($products) . " products synced. Cart & wishlist preserved.\n";

    // Seed sample reviews (only when table is empty)
    $reviewCount = (int) $db->query('SELECT COUNT(*) FROM reviews')->fetchColumn();
    if ($reviewCount === 0) {
        $sampleReviews = [
            ['Accounting System Pro', 'Sarah Mensah', 5, 'Implementation was smooth and reporting dashboards are exactly what our finance team needed.'],
            ['Accounting System Pro', 'James Osei', 5, 'Tax compliance features saved us hours every month. Highly recommended for growing businesses.'],
            ['Accounting System Pro', 'Ama Boateng', 4, 'Powerful module with a short learning curve. Support team was responsive during onboarding.'],
            ['HRIS Platform', 'Daniel Kofi', 5, 'Payroll automation works flawlessly. Employee self-service portal is a huge time saver.'],
            ['HRIS Platform', 'Grace Adom', 5, 'Best HRIS we have used. Performance review workflows are intuitive and well structured.'],
            ['CRM Suite', 'Michael Asante', 5, 'Lead tracking and email integration improved our sales pipeline visibility immediately.'],
            ['CRM Suite', 'Efua Nyarko', 4, 'Solid CRM for mid-size teams. Ticketing module could use more customization options.'],
            ['Business Intelligence', 'Kwame Appiah', 5, 'Dashboards are clean and KPI tracking is exactly what leadership asked for.'],
        ];

        $rows = $db->query('SELECT id, name FROM products')->fetchAll();
        $nameToId = [];
        foreach ($rows as $row) {
            $nameToId[$row['name']] = (int) $row['id'];
        }

        $insertReview = $db->prepare(
            'INSERT INTO reviews (product_id, reviewer_name, rating, comment)
             VALUES (:product_id, :reviewer_name, :rating, :comment)'
        );

        foreach ($sampleReviews as [$productName, $reviewer, $rating, $comment]) {
            if (!isset($nameToId[$productName])) {
                continue;
            }
            $insertReview->execute([
                'product_id'    => $nameToId[$productName],
                'reviewer_name' => $reviewer,
                'rating'        => $rating,
                'comment'       => $comment,
            ]);
        }

        $db->exec(
            'UPDATE products p
             SET rating = (
                 SELECT ROUND(AVG(r.rating), 2)
                 FROM reviews r
                 WHERE r.product_id = p.id
             )
             WHERE EXISTS (SELECT 1 FROM reviews r WHERE r.product_id = p.id)'
        );

        echo "Sample reviews seeded.\n";
    }

} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage() . "\n");
}
?>
