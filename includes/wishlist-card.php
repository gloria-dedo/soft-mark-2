<?php
// Wishlist page product card — expects $product, $cartItems, $wishlistItems

$isInWishlist = true;
$cardImageUrl = htmlspecialchars(imgUrl($product['image_url']));
$isInCart = in_array($product['id'], $cartItems ?? []);

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
<article class="product-card wishlist-card" id="wishlist-card-<?= (int) $product['id'] ?>">
    <div class="product-card-media">
        <span class="wishlist-saved-badge">
            <i class="fas fa-heart" aria-hidden="true"></i>
            Saved
        </span>

        <a
            href="product-detail.php?id=<?= (int) $product['id'] ?>"
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
            class="product-card-wishlist add-to-wishlist-overlay heart-active"
            data-id="<?= (int) $product['id'] ?>"
            aria-label="Remove from wishlist"
            aria-pressed="true"
            id="wish-btn-<?= (int) $product['id'] ?>"
        >
            <i class="fas fa-heart"></i>
        </button>
    </div>

    <div class="product-card-body">
        <a href="product-detail.php?id=<?= (int) $product['id'] ?>" class="product-card-title-link">
            <h3 class="product-card-title"><?= htmlspecialchars($product['name']) ?></h3>
        </a>

        <div class="product-card-rating" title="<?= $product['rating'] ?> out of 5 stars">
            <span class="product-card-stars"><?= renderStars((float) $product['rating']) ?></span>
            <span class="product-card-rating-num"><?= number_format((float) $product['rating'], 1) ?></span>
        </div>

        <p class="product-card-desc"><?= htmlspecialchars($product['description']) ?></p>

        <div class="product-card-footer">
            <span class="product-card-price">$<?= number_format($product['price'], 2) ?></span>
            <?= renderButton([
                'label' => 'Add To Cart',
                'type' => 'button',
                'variant' => 'primary',
                'size' => 'sm',
                'icon' => 'fas fa-shopping-cart',
                'class' => 'add-to-cart',
                'id' => 'cart-btn-' . $product['id'],
                'disabled' => $isInCart,
                'attrs' => ['data-id' => $product['id']],
            ]) ?>
        </div>
    </div>
</article>
