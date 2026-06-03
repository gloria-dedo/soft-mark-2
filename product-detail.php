<?php
require_once 'includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: products.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}

$isInCart = in_array($product['id'], $cartItems ?? []);
$isInWishlist = in_array($product['id'], $wishlistItems ?? []);

// Build features list
$features = array_map('trim', explode(',', $product['features']));

// Fetch related products (all others, limit 3)
$related = $db->prepare("SELECT * FROM products WHERE id != :id LIMIT 3");
$related->execute(['id' => $id]);
$relatedProducts = $related->fetchAll();

$pageTitle = htmlspecialchars($product['name']);
require_once 'includes/header.php';

// Render stars helper (duplicated here since db.php scope)
function renderStars2(float $rating): string {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($rating)) {
            $stars .= '<i class="fas fa-star"></i>';
        } elseif ($i - $rating < 1) {
            $stars .= '<i class="fas fa-star-half-stroke"></i>';
        } else {
            $stars .= '<i class="far fa-star"></i>';
        }
    }
    return $stars;
}
?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container">
        <a href="index.php">Home</a>
        <i class="fas fa-chevron-right"></i>
        <a href="products.php">Products</a>
        <i class="fas fa-chevron-right"></i>
        <span><?= htmlspecialchars($product['name']) ?></span>
    </div>
</div>

<!-- ============================
     PRODUCT DETAIL
============================== -->
<section class="product-detail-section">
    <div class="container">
        <div class="product-detail-grid">
            <!-- Product Image -->
            <div class="detail-img-col">
                <div class="detail-img-wrapper">
                    <img 
                        src="<?= htmlspecialchars($product['image_url']) ?>" 
                        alt="<?= htmlspecialchars($product['name']) ?>"
                        class="detail-img"
                        onerror="this.onerror=null; this.src='assets/images/placeholder.jpg';"
                    >
                    <?php if ($isInCart): ?>
                        <div class="detail-in-cart-badge"><i class="fas fa-check-circle"></i> Already In Your Cart</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="detail-info-col">
                <p class="detail-eyebrow"><i class="fas fa-cube"></i> &nbsp;Enterprise Software Module</p>
                <h1><?= htmlspecialchars($product['name']) ?></h1>

                <div class="detail-meta">
                    <div class="detail-rating">
                        <?= renderStars2($product['rating']) ?>
                        <span class="detail-rating-num"><?= number_format($product['rating'], 1) ?> / 5.0</span>
                    </div>
                </div>

                <p class="detail-desc"><?= htmlspecialchars($product['description']) ?></p>

                <!-- Feature list -->
                <div class="detail-features">
                    <h3>Key Features</h3>
                    <ul>
                        <?php foreach ($features as $feature): ?>
                            <li><i class="fas fa-check"></i> <?= htmlspecialchars($feature) ?></li>
                        <?php endforeach; ?>
                        <li><i class="fas fa-check"></i> Role-based access control</li>
                        <li><i class="fas fa-check"></i> Cloud &amp; on-premise deployment</li>
                        <li><i class="fas fa-check"></i> Free onboarding &amp; training</li>
                        <li><i class="fas fa-check"></i> 30-day money back guarantee</li>
                    </ul>
                </div>

                <!-- Price & Actions -->
                <div class="detail-purchase-box">
                    <div class="detail-price">
                        <span class="detail-price-label">Annual License</span>
                        <span class="detail-price-value">$<?= number_format($product['price'], 2) ?></span>
                        <span class="detail-price-note">per year - unlimited users</span>
                    </div>
                    <div class="detail-actions">
                        <button 
                            class="btn btn-red add-to-cart <?= $isInCart ? 'btn-incart' : '' ?>"
                            data-id="<?= $product['id'] ?>"
                            <?= $isInCart ? 'disabled' : '' ?>
                            id="detail-cart-btn"
                        >
                            <?= $isInCart ? '<i class="fas fa-check"></i> In Cart' : '<i class="fas fa-cart-plus"></i> Add to Cart' ?>
                        </button>
                        <button 
                            class="btn-icon add-to-wishlist <?= $isInWishlist ? 'heart-active' : '' ?>"
                            data-id="<?= $product['id'] ?>"
                            title="<?= $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' ?>"
                            id="detail-wish-btn"
                        >
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>

                <!-- Trust badges -->
                <div class="trust-badges">
                    <div class="badge-item"><i class="fas fa-shield-halved"></i> Secure Purchase</div>
                    <div class="badge-item"><i class="fas fa-rotate-left"></i> 30-Day Returns</div>
                    <div class="badge-item"><i class="fas fa-headset"></i> Dedicated Support</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================
     RELATED PRODUCTS
============================== -->
<?php if (!empty($relatedProducts)): ?>
<section class="related-section">
    <div class="container">
        <div class="section-header">
            <p class="section-eyebrow">More From SoftMark</p>
            <h2>Related Products</h2>
        </div>
        <div class="product-grid">
            <?php foreach ($relatedProducts as $product): ?>
                <?php include 'includes/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
