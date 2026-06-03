<?php
$pageTitle = 'Home';
require_once 'includes/db.php';

$featuredProducts = $db->query("SELECT * FROM products ORDER BY name ASC")->fetchAll();

require_once 'includes/header.php';
?>

<section class="hero product-finder-hero" id="hero">
    <div class="container hero-finder-inner animate-up">
        <div class="hero-copy">
            <p class="section-eyebrow">SoftMark ERP Store</p>
            <h1>Business web systems for every workflow</h1>
            <p>Find and purchase ready-to-use ERP modules for accounting, HR, inventory, CRM, procurement, projects, support, and more.</p>

            <label class="hero-search" for="home-product-search">
                <i class="fas fa-search"></i>
                <input type="search" id="home-product-search" placeholder="Search ERP products..." autocomplete="off">
            </label>
        </div>

        <div class="hero-art" aria-hidden="true">
            <div class="hero-type-box">
                <span>ERP</span>
            </div>
            <div class="hero-window hero-window-main">
                <div class="window-dots">
                    <span></span><span></span><span></span>
                </div>
                <div class="window-lines">
                    <span></span><span></span><span></span><span></span>
                </div>
            </div>
            <div class="hero-window hero-window-side"></div>
        </div>
    </div>
</section>

<section class="home-product-browser" id="featured">
    <div class="container">
        <div class="home-filter-bar" aria-label="Filter products by web system type">
            <button class="home-filter-chip active" type="button" data-filter="all">
                <i class="far fa-star"></i> Discover
            </button>
            <button class="home-filter-chip" type="button" data-filter="accounting">Accounting</button>
            <button class="home-filter-chip" type="button" data-filter="hr">HR &amp; Payroll</button>
            <button class="home-filter-chip" type="button" data-filter="inventory">Inventory</button>
            <button class="home-filter-chip" type="button" data-filter="crm">CRM</button>
            <button class="home-filter-chip" type="button" data-filter="procurement">Procurement</button>
            <button class="home-filter-chip" type="button" data-filter="project">Projects</button>
            <button class="home-filter-chip" type="button" data-filter="support">Support</button>
            <a href="products.php" class="home-filter-link">View all <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="home-products-heading">
            <div>
                <p class="section-eyebrow">Available Systems</p>
                <h2>Choose a product and add it to cart.</h2>
            </div>
            <p id="home-product-count"><?= count($featuredProducts) ?> products available</p>
        </div>

        <div class="product-grid home-product-grid" id="home-product-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <?php include 'includes/product-card.php'; ?>
            <?php endforeach; ?>
        </div>

        <div class="home-empty-state" id="home-empty-state" hidden>
            <i class="fas fa-magnifying-glass"></i>
            <p>No products match your search. Try another system type or keyword.</p>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
