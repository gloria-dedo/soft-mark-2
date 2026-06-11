<?php
require_once 'includes/db.php';
require_once 'includes/categories.php';
require_once 'includes/reviews.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$id) {
    header('Location: products.php');
    exit;
}

$stmt = $db->prepare('SELECT * FROM products WHERE id = :id');
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}

$features = array_filter(array_map('trim', explode(',', $product['features'])));
$reviews = fetchProductReviews($db, $id);
$reviewStats = buildReviewStats($reviews);
$hasReviews = $reviewStats['count'] > 0;

$category = detectProductCategory($product);
$categoryLabel = $category['label'] ?? 'Enterprise Software';

$related = $db->prepare('SELECT * FROM products WHERE id != :id LIMIT 3');
$related->execute(['id' => $id]);
$relatedProducts = $related->fetchAll();

$pageTitle = htmlspecialchars($product['name']);
require_once 'includes/header.php';

$isInCart = in_array($product['id'], $cartItems ?? []);
$isInWishlist = in_array($product['id'], $wishlistItems ?? []);
$mainImage = imgUrl($product['image_url']);
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

<section class="pd-section">
    <div class="container pd-layout">
        <div class="pd-gallery">
            <div class="pd-gallery-main">
                <img
                    src="<?= htmlspecialchars($mainImage) ?>"
                    alt="<?= htmlspecialchars($product['name']) ?>"
                    class="pd-gallery-image"
                    id="pd-main-image"
                    onerror="this.onerror=null; this.src='assets/images/placeholder.jpg';"
                >
            </div>
        </div>

        <div class="pd-info">
            <span class="pd-category-badge"><?= htmlspecialchars($categoryLabel) ?></span>

            <h1 class="pd-title"><?= htmlspecialchars($product['name']) ?></h1>

            <p class="pd-price">$<?= number_format($product['price'], 2) ?></p>

            <div class="pd-rating-block">
                <?php if ($hasReviews): ?>
                    <div class="pd-rating-summary">
                        <?= renderReviewStars($reviewStats['average'], 'pd-stars') ?>
                        <span class="pd-rating-score"><?= number_format($reviewStats['average'], 1) ?> / 5</span>
                        <span class="pd-rating-count">(<?= $reviewStats['count'] ?> <?= $reviewStats['count'] === 1 ? 'Review' : 'Reviews' ?>)</span>
                    </div>
                <?php endif; ?>
                <button type="button" class="pd-review-link" id="open-review-modal">
                    Write a review
                </button>
            </div>

            <div class="pd-delivery-note">
                <i class="fas fa-bolt"></i>
                <span>Instant digital delivery &bull; Expert onboarding support included</span>
            </div>

            <div class="pd-deployment">
                <p class="pd-deployment-label">Deployment Options</p>
                <div class="pd-deployment-options" role="group" aria-label="Deployment options">
                    <button type="button" class="pd-deployment-chip is-active" data-deployment="cloud">Cloud</button>
                    <button type="button" class="pd-deployment-chip" data-deployment="on-premise">On-Premise</button>
                    <button type="button" class="pd-deployment-chip" data-deployment="hybrid">Hybrid</button>
                </div>
            </div>

            <div class="pd-actions">
                <?= renderButton([
                    'label' => 'Add to Cart',
                    'type' => 'button',
                    'variant' => 'primary',
                    'size' => 'lg',
                    'icon' => 'fas fa-shopping-cart',
                    'class' => 'add-to-cart product-detail-cart-btn',
                    'id' => 'detail-cart-btn',
                    'disabled' => $isInCart,
                    'attrs' => ['data-id' => $product['id']],
                ]) ?>
                <button
                    type="button"
                    class="pd-wishlist-btn add-to-wishlist-overlay<?= $isInWishlist ? ' heart-active' : '' ?>"
                    data-id="<?= $product['id'] ?>"
                    aria-label="<?= $isInWishlist ? 'Remove from wishlist' : 'Add to wishlist' ?>"
                    aria-pressed="<?= $isInWishlist ? 'true' : 'false' ?>"
                >
                    <i class="<?= $isInWishlist ? 'fas' : 'far' ?> fa-heart"></i>
                </button>
            </div>

            <details class="pd-accordion" open>
                <summary>
                    <span>Product Overview</span>
                    <i class="fas fa-chevron-down"></i>
                </summary>
                <div class="pd-accordion-body">
                    <p><?= htmlspecialchars($product['description']) ?></p>
                </div>
            </details>

            <details class="pd-accordion">
                <summary>
                    <span>Key Features</span>
                    <i class="fas fa-chevron-down"></i>
                </summary>
                <div class="pd-accordion-body">
                    <ul class="pd-feature-list">
                        <?php foreach ($features as $feature): ?>
                            <li><?= htmlspecialchars($feature) ?></li>
                        <?php endforeach; ?>
                        <li>Role-based access control</li>
                        <li>Secure cloud &amp; on-premise deployment</li>
                    </ul>
                </div>
            </details>

            <details class="pd-accordion">
                <summary>
                    <span>Support &amp; Licensing</span>
                    <i class="fas fa-chevron-down"></i>
                </summary>
                <div class="pd-accordion-body">
                    <div class="pd-support-grid">
                        <div class="pd-support-item">
                            <i class="fas fa-headset"></i>
                            <div>
                                <strong>Expert Support</strong>
                                <span>Dedicated onboarding assistance</span>
                            </div>
                        </div>
                        <div class="pd-support-item">
                            <i class="fas fa-shield-halved"></i>
                            <div>
                                <strong>Secure Licensing</strong>
                                <span>Enterprise-grade access controls</span>
                            </div>
                        </div>
                        <div class="pd-support-item">
                            <i class="fas fa-rotate-left"></i>
                            <div>
                                <strong>30-Day Guarantee</strong>
                                <span>Money-back if not satisfied</span>
                            </div>
                        </div>
                        <div class="pd-support-item">
                            <i class="fas fa-cloud-arrow-up"></i>
                            <div>
                                <strong>Free Updates</strong>
                                <span>Includes maintenance releases</span>
                            </div>
                        </div>
                    </div>
                </div>
            </details>
        </div>
    </div>
</section>

<?php if ($hasReviews): ?>
<section class="pd-reviews-section" id="pd-reviews">
    <div class="container">
        <h2 class="pd-reviews-heading">Rating &amp; Reviews</h2>

        <div class="pd-reviews-layout">
            <aside class="pd-reviews-summary">
                <p class="pd-reviews-average">
                    <?= number_format($reviewStats['average'], 1) ?><span>/5</span>
                </p>
                <p class="pd-reviews-total">(<?= $reviewStats['count'] ?> Ratings)</p>

                <div class="pd-reviews-bars">
                    <?php for ($star = 5; $star >= 1; $star--): ?>
                        <div class="pd-review-bar-row">
                            <span class="pd-review-bar-label"><?= $star ?></span>
                            <div class="pd-review-bar-track">
                                <div
                                    class="pd-review-bar-fill"
                                    style="width: <?= $reviewStats['distribution_pct'][$star] ?>%;"
                                ></div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </aside>

            <div class="pd-reviews-carousel-wrap">
                <div class="pd-reviews-carousel" id="pd-reviews-carousel">
                    <?php foreach ($reviews as $index => $review): ?>
                        <article
                            class="pd-review-card<?= $index === 0 ? ' is-active' : '' ?>"
                            data-index="<?= $index ?>"
                        >
                            <header class="pd-review-card-header">
                                <strong><?= htmlspecialchars($review['reviewer_name']) ?></strong>
                                <time datetime="<?= htmlspecialchars($review['created_at']) ?>">
                                    <?= formatReviewDate($review['created_at']) ?>
                                </time>
                            </header>
                            <div class="pd-review-card-stars">
                                <?= renderReviewStars((float) $review['rating'], 'pd-stars') ?>
                            </div>
                            <p class="pd-review-card-text"><?= htmlspecialchars($review['comment']) ?></p>
                            <div class="pd-review-avatar" aria-hidden="true">
                                <?= htmlspecialchars(reviewerInitials($review['reviewer_name'])) ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <?php if ($reviewStats['count'] > 1): ?>
                    <div class="pd-reviews-controls">
                        <div class="pd-reviews-dots" id="pd-reviews-dots">
                            <?php foreach ($reviews as $index => $review): ?>
                                <button
                                    type="button"
                                    class="pd-reviews-dot<?= $index === 0 ? ' is-active' : '' ?>"
                                    data-index="<?= $index ?>"
                                    aria-label="Go to review <?= $index + 1 ?>"
                                ></button>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="pd-reviews-next" id="pd-reviews-next" aria-label="Next review">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<div class="pd-modal" id="review-modal" hidden aria-hidden="true">
    <div class="pd-modal-backdrop" id="review-modal-backdrop"></div>
    <div class="pd-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="review-modal-title">
        <button type="button" class="pd-modal-close" id="close-review-modal" aria-label="Close review form">
            <i class="fas fa-times"></i>
        </button>

        <h2 class="pd-modal-title" id="review-modal-title">Write a Review</h2>
        <p class="pd-modal-subtitle">Share your experience with <?= htmlspecialchars($product['name']) ?></p>

        <form class="pd-review-form" id="review-form" novalidate>
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

            <div class="pd-form-group">
                <label>Your Rating</label>
                <div class="pd-star-input" id="review-star-input" role="radiogroup" aria-label="Rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button" class="pd-star-input-btn" data-value="<?= $i ?>" aria-label="<?= $i ?> stars">
                            <i class="far fa-star"></i>
                        </button>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="review-rating-value" value="">
            </div>

            <div class="pd-form-group">
                <label for="reviewer-name">Your Name</label>
                <input type="text" id="reviewer-name" name="reviewer_name" required maxlength="100" placeholder="Enter your name">
            </div>

            <div class="pd-form-group">
                <label for="review-comment">Your Review</label>
                <textarea id="review-comment" name="comment" required rows="4" maxlength="1000" placeholder="Tell others about your experience with this system"></textarea>
            </div>

            <p class="pd-form-error" id="review-form-error" hidden></p>

            <button type="submit" class="pd-modal-submit" id="review-form-submit">
                Submit Review
            </button>
        </form>
    </div>
</div>

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

<script src="assets/js/product-detail.js"></script>
<?php require_once 'includes/footer.php'; ?>
