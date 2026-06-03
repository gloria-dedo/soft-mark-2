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

$features = array_filter(array_map('trim', explode(',', $product['features'])));

$related = $db->prepare("SELECT * FROM products WHERE id != :id LIMIT 3");
$related->execute(['id' => $id]);
$relatedProducts = $related->fetchAll();

$pageTitle = htmlspecialchars($product['name']);
require_once 'includes/header.php';

$isInCart = in_array($product['id'], $cartItems ?? []);

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

<div class="breadcrumb-bar">
    <div class="container">
        <a href="index.php">Home</a>
        <i class="fas fa-chevron-right"></i>
        <a href="products.php">Products</a>
        <i class="fas fa-chevron-right"></i>
        <span><?= htmlspecialchars($product['name']) ?></span>
    </div>
</div>

<section class="product-detail-section product-showcase-section">
    <div class="container product-showcase-grid">
        <div class="product-showcase-info">
            <p class="product-detail-kicker">Enterprise Software Module</p>
            <h1><?= htmlspecialchars($product['name']) ?></h1>

            <div class="detail-price-inline">
                <strong>$<?= number_format($product['price'], 2) ?></strong>
            </div>

            <div class="detail-rating">
                <?= renderStars2($product['rating']) ?>
                <span class="detail-rating-num"><?= number_format($product['rating'], 1) ?> / 5.0</span>
            </div>

            <button
                class="btn btn-primary product-detail-cart-btn add-to-cart <?= $isInCart ? 'btn-incart' : '' ?>"
                data-id="<?= $product['id'] ?>"
                <?= $isInCart ? 'disabled' : '' ?>
                id="detail-cart-btn"
            >
                <?= $isInCart ? '<i class="fas fa-check"></i> In Cart' : '<i class="fas fa-cart-plus"></i> Add to Cart' ?>
            </button>

            <div class="product-detail-notes">
                <p><i class="fas fa-truck-fast"></i> Fast setup support after purchase.</p>
                <p><i class="fas fa-headset"></i> Dedicated assistance available for onboarding.</p>
            </div>

            <details class="product-detail-panel" open>
                <summary>Product Description</summary>
                <p><?= htmlspecialchars($product['description']) ?></p>
            </details>

            <details class="product-detail-panel">
                <summary>Product Details</summary>
                <ul>
                    <?php foreach ($features as $feature): ?>
                        <li><?= htmlspecialchars($feature) ?></li>
                    <?php endforeach; ?>
                    <li>Role-based access control</li>
                    <li>Cloud &amp; on-premise deployment</li>
                    <li>Free onboarding &amp; training</li>
                    <li>30-day money back guarantee</li>
                </ul>
            </details>

            <details class="product-detail-panel">
                <summary>Our Commitment</summary>
                <p>Secure purchase, clear onboarding, and responsive support for every SoftMark product.</p>
            </details>
        </div>

        <div class="product-showcase-visual">
            <div class="product-visual-frame">
                <img
                    src="<?= htmlspecialchars($product['image_url']) ?>"
                    alt="<?= htmlspecialchars($product['name']) ?> product image"
                    class="detail-img"
                    onerror="this.onerror=null; this.src='assets/images/placeholder.jpg';"
                >
            </div>
        </div>
    </div>
</section>

<?php if (!empty($relatedProducts)): ?>
<section class="related-section">
    <div class="container">
        <div class="section-header">
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
