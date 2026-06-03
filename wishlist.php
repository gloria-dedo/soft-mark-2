<?php
$pageTitle = 'Your Wishlist';
require_once 'includes/db.php';

// Fetch wishlist items
$wishlistProducts = $db->query("
    SELECT p.*, w.id as wishlist_id
    FROM wishlist w 
    JOIN products p ON w.product_id = p.id
")->fetchAll();

require_once 'includes/header.php';
?>

<section class="page-hero">
    <div class="container animate-up">
        <p class="section-eyebrow">Saved For Later</p>
        <h1>Your Wishlist</h1>
    </div>
</section>

<section class="wishlist-section" style="padding: 80px 0;">
    <div class="container animate-up" style="animation-delay: 0.1s;">
        <?php if (empty($wishlistProducts)): ?>
            <div class="empty-state" style="text-align: center; padding: 60px 20px; background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);">
                <i class="fas fa-heart" style="font-size: 3.5rem; color: var(--border); margin-bottom: 20px;"></i>
                <h2 style="margin-bottom: 12px;">Your wishlist is empty.</h2>
                <p style="color: var(--text-mid); margin-bottom: 24px;">Save your favorite enterprise software modules to review later.</p>
                <a href="products.php" class="btn btn-red">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($wishlistProducts as $product): ?>
                    <?php include 'includes/product-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
