<?php
$pageTitle = 'Your Cart';
require_once 'includes/db.php';

// Fetch cart items
$cartProducts = $db->query("
    SELECT c.id as cart_id, c.quantity, p.* 
    FROM cart c 
    JOIN products p ON c.product_id = p.id
")->fetchAll();

// Handle remove item
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $removeId = (int)$_GET['remove'];
    $stmt = $db->prepare("DELETE FROM cart WHERE id = :id");
    $stmt->execute(['id' => $removeId]);
    header('Location: cart.php');
    exit;
}

$totalPrice = 0;
foreach ($cartProducts as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

require_once 'includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container animate-up">
        <p class="section-eyebrow">Review Your Order</p>
        <h1>Shopping Cart</h1>
    </div>
</section>

<!-- Cart Section -->
<section class="cart-section" style="padding: 80px 0;">
    <div class="container animate-up" style="animation-delay: 0.1s;">
        <?php if (empty($cartProducts)): ?>
            <div class="empty-state" style="text-align: center; padding: 60px 20px; background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);">
                <i class="fas fa-shopping-cart" style="font-size: 3.5rem; color: var(--border); margin-bottom: 20px;"></i>
                <h2 style="margin-bottom: 12px;">Your cart is empty.</h2>
                <p style="color: var(--text-mid); margin-bottom: 24px;">Explore our enterprise software modules and add them to your cart.</p>
                <a href="products.php" class="btn btn-primary">Shop Now</a>
            </div>
        <?php else: ?>
            <div class="contact-grid">
                <!-- Cart Items -->
                <div class="cart-items-col" style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background: var(--bg-light); border-bottom: 1px solid var(--border); text-align: left;">
                            <tr>
                                <th style="padding: 16px 24px; font-size: .85rem; text-transform: uppercase; letter-spacing: .05em; color: var(--text-light);">Product</th>
                                <th style="padding: 16px 24px; font-size: .85rem; text-transform: uppercase; letter-spacing: .05em; color: var(--text-light);">Price</th>
                                <th style="padding: 16px 24px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartProducts as $item): ?>
                                <tr style="border-bottom: 1px solid var(--border);">
                                    <td style="padding: 24px;">
                                        <div style="display: flex; align-items: center; gap: 16px;">
                                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 64px; height: 64px; object-fit: cover; border-radius: 4px;">
                                            <div>
                                                <strong style="display: block; font-size: 1.05rem; margin-bottom: 4px;"><?= htmlspecialchars($item['name']) ?></strong>
                                                <span style="font-size: .85rem; color: var(--text-light);"><?= htmlspecialchars(substr($item['description'], 0, 60)) ?>...</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 24px; font-weight: 700; font-size: 1.1rem; color: var(--accent-blue);">
                                        $<?= number_format($item['price'], 2) ?>
                                    </td>
                                    <td style="padding: 24px; text-align: right;">
                                        <a href="cart.php?remove=<?= $item['cart_id'] ?>" class="btn-icon" style="color: var(--accent-red); border-color: transparent; background: transparent;" title="Remove Item">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Order Summary -->
                <div class="cart-summary-col">
                    <div style="background: var(--white); padding: 32px; border: 1px solid var(--border); border-radius: var(--radius);">
                        <h2 style="margin-bottom: 24px; font-size: 1.5rem;">Order Summary</h2>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 1rem; color: var(--text-mid);">
                            <span>Subtotal (<?= count($cartProducts) ?> items)</span>
                            <strong>$<?= number_format($totalPrice, 2) ?></strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 24px; font-size: 1rem; color: var(--text-mid); padding-bottom: 24px; border-bottom: 1px solid var(--border);">
                            <span>Implementation</span>
                            <strong>$0.00</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-weight: 900; font-size: 1.4rem; margin-bottom: 32px;">
                            <span>Total</span>
                            <span style="color: var(--accent-red);">$<?= number_format($totalPrice, 2) ?></span>
                        </div>
                        <a href="checkout.php" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 16px; font-size: 1rem; background: var(--accent-red); border-color: var(--accent-red);">
                            Proceed to Checkout <i class="fas fa-arrow-right"></i>
                        </a>
                        <p style="text-align: center; font-size: .8rem; color: var(--text-light); margin-top: 16px;">
                            <i class="fas fa-lock"></i> Secure 256-bit SSL encryption.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
