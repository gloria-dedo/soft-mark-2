<?php
// Reusable product card component
// Expects: $product array, $cartItems array, $wishlistItems array

$isInWishlist = in_array($product['id'], $wishlistItems ?? []);
$cardImageUrl = htmlspecialchars(imgUrl($product['image_url']));
$productSearchText = strtolower($product['name'] . ' ' . $product['description'] . ' ' . $product['features']);
$productType = 'operations';

if (strpos($productSearchText, 'account') !== false || strpos($productSearchText, 'tax') !== false || strpos($productSearchText, 'invoice') !== false) {
    $productType = 'accounting';
} elseif (strpos($productSearchText, 'hr') !== false || strpos($productSearchText, 'human') !== false || strpos($productSearchText, 'payroll') !== false || strpos($productSearchText, 'employee') !== false) {
    $productType = 'hr';
} elseif (strpos($productSearchText, 'inventory') !== false || strpos($productSearchText, 'stock') !== false || strpos($productSearchText, 'warehouse') !== false) {
    $productType = 'inventory';
} elseif (strpos($productSearchText, 'crm') !== false || strpos($productSearchText, 'customer') !== false || strpos($productSearchText, 'lead') !== false) {
    $productType = 'crm';
} elseif (strpos($productSearchText, 'procurement') !== false || strpos($productSearchText, 'purchase') !== false || strpos($productSearchText, 'supplier') !== false) {
    $productType = 'procurement';
} elseif (strpos($productSearchText, 'project') !== false || strpos($productSearchText, 'gantt') !== false) {
    $productType = 'project';
} elseif (strpos($productSearchText, 'help') !== false || strpos($productSearchText, 'support') !== false || strpos($productSearchText, 'ticket') !== false) {
    $productType = 'support';
}

if (!function_exists('renderStars')) {
    function renderStars(float $rating): string {
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
}
?>
<article
    class="product-card"
    id="product-card-<?= $product['id'] ?>"
    data-system-type="<?= htmlspecialchars($productType) ?>"
    data-search="<?= htmlspecialchars($productSearchText) ?>"
>
    <div class="product-card-media">
        <a
            href="product-detail.php?id=<?= $product['id'] ?>"
            class="product-card-image-link"
            aria-label="<?= htmlspecialchars($product['name']) ?>"
        >
            <img
                src="<?= $cardImageUrl ?>"
                alt=""
                class="product-card-image"
                loading="lazy"
                onerror="this.onerror=null; this.src='assets/images/placeholder.jpg';"
            >
        </a>
        <button
            type="button"
            class="product-card-wishlist add-to-wishlist-overlay<?= $isInWishlist ? ' heart-active' : '' ?>"
            data-id="<?= $product['id'] ?>"
            aria-label="<?= $isInWishlist ? 'Remove from wishlist' : 'Add to wishlist' ?>"
            aria-pressed="<?= $isInWishlist ? 'true' : 'false' ?>"
            id="wish-btn-<?= $product['id'] ?>"
        >
            <i class="<?= $isInWishlist ? 'fas' : 'far' ?> fa-heart"></i>
        </button>
    </div>

    <div class="product-card-body">
        <a href="product-detail.php?id=<?= $product['id'] ?>" class="product-card-title-link">
            <h3 class="product-card-title"><?= htmlspecialchars($product['name']) ?></h3>
        </a>

        <div class="product-card-rating" title="<?= $product['rating'] ?> out of 5 stars">
            <span class="product-card-stars"><?= renderStars($product['rating']) ?></span>
            <span class="product-card-rating-num"><?= number_format($product['rating'], 1) ?></span>
        </div>

        <p class="product-card-desc"><?= htmlspecialchars($product['description']) ?></p>

        <div class="product-card-footer">
            <span class="product-card-price">$<?= number_format($product['price'], 2) ?></span>
            <button
                type="button"
                class="product-card-cart add-to-cart"
                data-id="<?= $product['id'] ?>"
                id="cart-btn-<?= $product['id'] ?>"
            >
                <i class="fas fa-shopping-cart"></i>
                Add To Cart
            </button>
        </div>
    </div>
</article>
