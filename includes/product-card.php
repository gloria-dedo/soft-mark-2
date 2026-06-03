<?php
// Reusable product card component
// Expects: $product array, $cartItems array, $wishlistItems array

$isInCart = in_array($product['id'], $cartItems ?? []);
$isInWishlist = in_array($product['id'], $wishlistItems ?? []);
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

// Render stars
if (!function_exists('renderStars')) {
    function renderStars(float $rating): string {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($rating)) {
                $stars .= '<i class="fas fa-star" style="color: #f59e0b;"></i>';
            } elseif ($i - $rating < 1) {
                $stars .= '<i class="fas fa-star-half-stroke" style="color: #f59e0b;"></i>';
            } else {
                $stars .= '<i class="far fa-star" style="color: #d1d5db;"></i>';
            }
        }
        return $stars;
    }
}
?>
<div class="product-card" id="product-card-<?= $product['id'] ?>" data-system-type="<?= htmlspecialchars($productType) ?>" data-search="<?= htmlspecialchars($productSearchText) ?>">
    <div class="product-img-wrapper">
        <a href="product-detail.php?id=<?= $product['id'] ?>" class="product-img-link" style="display: block; position: absolute; inset: 0;">
            <img 
                src="<?= htmlspecialchars($product['image_url']) ?>" 
                alt="<?= htmlspecialchars($product['name']) ?>"
                class="product-img"
                onerror="this.onerror=null; this.src='assets/images/placeholder.jpg';"
            >
            <?php if ($isInCart): ?>
                <span class="in-cart-badge"><i class="fas fa-check"></i> Added</span>
            <?php endif; ?>
        </a>
        <button 
            class="add-to-wishlist-overlay <?= $isInWishlist ? 'heart-active' : '' ?>"
            data-id="<?= $product['id'] ?>"
            title="<?= $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' ?>"
            id="wish-btn-<?= $product['id'] ?>"
        >
            <i class="fas fa-heart"></i>
        </button>
    </div>
    
    <div class="product-info" style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
            <a href="product-detail.php?id=<?= $product['id'] ?>" style="text-decoration: none; color: inherit; flex-grow: 1;">
                <h3 class="product-title" style="margin: 0; font-size: 1.1rem; font-weight: 700; line-height: 1.3; color: var(--text-dark);"><?= htmlspecialchars($product['name']) ?></h3>
            </a>
        </div>
        
        <div class="product-meta" style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-size: .85rem;">
            <span class="product-rating" title="<?= $product['rating'] ?> out of 5 stars">
                <?= renderStars($product['rating']) ?>
                <span class="rating-num" style="color: var(--text-light); margin-left: 4px; font-weight: 600;"><?= number_format($product['rating'], 1) ?></span>
            </span>
        </div>
        
        <p class="product-desc" style="font-size: .9rem; color: var(--text-mid); margin-bottom: 16px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= htmlspecialchars($product['description']) ?></p>
        
        <a href="product-detail.php?id=<?= $product['id'] ?>" style="font-size: .85rem; font-weight: 700; color: var(--accent-blue); text-transform: uppercase; letter-spacing: .05em; display: inline-flex; align-items: center; gap: 6px; margin-bottom: auto; transition: gap .2s;">
            Read More <i class="fas fa-arrow-right"></i>
        </a>
        
        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 20px; border-top: 1px solid var(--border); padding-top: 16px;">
            <span class="product-price" style="font-weight: 800; font-size: 1.15rem; color: var(--accent-blue);">$<?= number_format($product['price'], 2) ?></span>
            
            <button 
                class="btn btn-primary add-to-cart <?= $isInCart ? 'btn-incart' : '' ?>"
                data-id="<?= $product['id'] ?>"
                <?= $isInCart ? 'disabled' : '' ?>
                id="cart-btn-<?= $product['id'] ?>"
                style="padding: 8px 16px; font-size: .85rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 6px;"
            >
                <?= $isInCart ? '<i class="fas fa-check"></i> In Cart' : '<i class="fas fa-plus"></i> Add' ?>
            </button>
        </div>
    </div>
</div>
