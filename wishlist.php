<?php
$pageTitle = 'My Wishlist';
require_once 'includes/db.php';
require_once 'includes/categories.php';

$sort = $_GET['sort'] ?? 'newest';

$wishlistProducts = $db->query('
    SELECT p.*, w.id AS wishlist_id
    FROM wishlist w
    JOIN products p ON w.product_id = p.id
')->fetchAll();

$wishlistProducts = sortStoreProducts($wishlistProducts, $sort);
$itemCount = count($wishlistProducts);

$subtitle = $itemCount === 1
    ? '1 carefully selected item awaiting your attention'
    : $itemCount . ' carefully selected items awaiting your attention';

require_once 'includes/header.php';
?>

<section class="wishlist-hero">
    <div class="wishlist-hero-inner">
        <div class="wishlist-hero-icon" aria-hidden="true">
            <i class="fas fa-heart"></i>
        </div>
        <h1>My Wishlist</h1>
        <p><?= $itemCount > 0 ? htmlspecialchars($subtitle) : 'Save ERP modules you want to review later' ?></p>
    </div>
</section>

<section class="wishlist-page-section">
    <div class="wishlist-page-wrap">
        <?php if (empty($wishlistProducts)): ?>
            <div class="wishlist-empty">
                <i class="far fa-heart"></i>
                <h2>Your wishlist is empty</h2>
                <p>Save your favorite enterprise software modules to review later.</p>
                <?= renderButton(['label' => 'Browse Products', 'href' => 'products.php', 'block' => true]) ?>
            </div>
        <?php else: ?>
            <div class="wishlist-toolbar">
                <div class="wishlist-toolbar-summary">
                    <div class="wishlist-toolbar-icon" aria-hidden="true">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div>
                        <strong><?= $itemCount ?> Item<?= $itemCount === 1 ? '' : 's' ?></strong>
                        <span>Ready to purchase</span>
                    </div>
                </div>

                <form action="wishlist.php" method="GET" class="wishlist-sort-form">
                    <label class="products-sort-label" for="wishlist-sort">Sort by</label>
                    <div class="products-sort-wrap">
                        <i class="fas fa-filter products-sort-icon" aria-hidden="true"></i>
                        <select name="sort" id="wishlist-sort" class="products-sort-select" onchange="this.form.submit()">
                            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
                            <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price (Low to High)</option>
                            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price (High to Low)</option>
                        </select>
                        <i class="fas fa-chevron-down products-sort-chevron" aria-hidden="true"></i>
                    </div>
                </form>
            </div>

            <div class="wishlist-grid product-grid">
                <?php foreach ($wishlistProducts as $product): ?>
                    <?php include 'includes/wishlist-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>


