<?php
require_once __DIR__ . '/database/erp_store.sqlite';
$dbPath = __DIR__ . '/database/erp_store.sqlite';

try {
    $db = new PDO("sqlite:" . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $products = [
        [
            'name' => 'Procurement Management',
            'description' => 'Streamline your purchasing processes, manage supplier relationships, and automate purchase orders.',
            'price' => 249.99,
            'rating' => 4.4,
            'image_url' => 'assets/images/procurement.jpg',
            'features' => 'Purchase Orders, Supplier Management, Spend Analysis'
        ],
        [
            'name' => 'Supply Chain Analytics',
            'description' => 'Advanced analytics module for tracking supply chain performance and forecasting demand.',
            'price' => 599.99,
            'rating' => 4.9,
            'image_url' => 'assets/images/supply_chain.jpg',
            'features' => 'Demand Forecasting, Logistics Tracking, Real-time Alerts'
        ],
        [
            'name' => 'Advanced Payroll System',
            'description' => 'Automated payroll processing with built-in compliance for tax regulations and benefits management.',
            'price' => 299.99,
            'rating' => 4.7,
            'image_url' => 'assets/images/payroll.jpg',
            'features' => 'Tax Compliance, Benefits Admin, Direct Deposit'
        ],
        [
            'name' => 'Marketing Automation Suite',
            'description' => 'Create, manage, and track marketing campaigns across multiple channels from a single dashboard.',
            'price' => 349.99,
            'rating' => 4.5,
            'image_url' => 'assets/images/marketing.jpg',
            'features' => 'Email Marketing, Campaign Tracking, Lead Scoring'
        ],
        [
            'name' => 'Enterprise Help Desk',
            'description' => 'Provide exceptional customer support with an integrated ticketing system and knowledge base.',
            'price' => 199.99,
            'rating' => 4.6,
            'image_url' => 'assets/images/helpdesk.jpg',
            'features' => 'Ticketing System, Knowledge Base, SLA Management'
        ],
        [
            'name' => 'Project Management Hub',
            'description' => 'Plan, execute, and track enterprise projects with Gantt charts, resource allocation, and time tracking.',
            'price' => 279.99,
            'rating' => 4.8,
            'image_url' => 'assets/images/project_management.jpg',
            'features' => 'Gantt Charts, Resource Allocation, Time Tracking'
        ]
    ];

    $insertStmt = $db->prepare("INSERT INTO products (name, description, price, rating, image_url, features) VALUES (:name, :description, :price, :rating, :image_url, :features)");
    
    foreach ($products as $product) {
        $insertStmt->execute($product);
    }
    echo "More products seeded successfully.\n";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>
