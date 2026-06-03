<?php
$pageTitle = 'Home';
require_once 'includes/db.php';

$featuredProducts = $db->query("SELECT * FROM products ORDER BY name ASC")->fetchAll();

require_once 'includes/header.php';
?>

<section class="hero product-finder-hero" id="hero">
    <div class="container hero-finder-inner animate-up">
        <div class="hero-copy">
            <h1>ERP systems for growing businesses</h1>
            <p>Browse practical web-based modules for finance, people, stock, sales, purchasing, projects, and daily operations.</p>

            <label class="hero-search" for="home-product-search">
                <i class="fas fa-search"></i>
                <input type="search" id="home-product-search" placeholder="Search systems..." autocomplete="off">
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
                All
            </button>
            <button class="home-filter-chip" type="button" data-filter="accounting">Accounting</button>
            <button class="home-filter-chip" type="button" data-filter="hr">HR &amp; Payroll</button>
            <button class="home-filter-chip" type="button" data-filter="inventory">Inventory</button>
            <button class="home-filter-chip" type="button" data-filter="crm">CRM</button>
            <button class="home-filter-chip" type="button" data-filter="procurement">Procurement</button>
            <button class="home-filter-chip" type="button" data-filter="project">Projects</button>
            <button class="home-filter-chip" type="button" data-filter="support">Support</button>
            <label class="home-filter-select-wrap" for="home-system-filter">
                <span>View</span>
                <select id="home-system-filter" class="home-filter-select" aria-label="View products by system type">
                    <option value="all">All</option>
                    <option value="accounting">Accounting</option>
                    <option value="hr">HR &amp; Payroll</option>
                    <option value="inventory">Inventory</option>
                    <option value="crm">CRM</option>
                    <option value="procurement">Procurement</option>
                    <option value="project">Projects</option>
                    <option value="support">Support</option>
                </select>
            </label>
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
