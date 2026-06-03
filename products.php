<?php
$pageTitle = 'All ERP Products';
require_once 'includes/db.php';

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'name_asc';

$query = "SELECT * FROM products";
$params = [];

if ($search !== '') {
    $query .= " WHERE name LIKE :search OR description LIKE :search";
    $params['search'] = "%$search%";
}

if ($sort === 'price_asc') {
    $query .= " ORDER BY price ASC";
} elseif ($sort === 'price_desc') {
    $query .= " ORDER BY price DESC";
} else {
    $query .= " ORDER BY name ASC";
}

$stmt = $db->prepare($query);
$stmt->execute($params);
$allProducts = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<!-- ============================
     PAGE HERO
============================== -->
<section class="page-hero">
    <div class="container">
        <p class="section-eyebrow">Our Software Suite</p>
        <h1>All ERP Products</h1>
        <p>Explore our complete range of enterprise software modules — built for precision, integration, and scale.</p>
    </div>
</section>

<!-- ============================
     PRODUCTS GRID
============================== -->
<section class="products-page-section">
    <div class="container animate-up">
        
        <div class="products-filter-bar">
            <p class="products-count"><?= count($allProducts) ?> Products Available</p>
            
            <form action="products.php" method="GET" class="filter-form">
                <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>" class="search-input">
                <select name="sort" onchange="this.form.submit()" class="sort-select">
                    <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                    <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price (Low to High)</option>
                    <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price (High to Low)</option>
                </select>
                <button type="submit" class="btn btn-primary btn-search"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="product-grid">
            <?php if ($allProducts): ?>
                <?php foreach ($allProducts as $product): ?>
                    <?php include 'includes/product-card.php'; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>No products found at this time. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Inline CTA -->
<section class="cta-banner" id="products-cta">
    <div class="container cta-banner-inner">
        <div>
            <h2>Can't Find What You Need?</h2>
            <p>Our team can build custom ERP modules tailored to your specific business workflows.</p>
        </div>
        <a href="contact.php" class="btn btn-white" id="products-page-cta-btn">Contact Our Team</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
