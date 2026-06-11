<?php
$pageTitle = 'Shopping Cart';
require_once 'includes/db.php';

if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $stmt = $db->prepare('DELETE FROM cart WHERE id = :id');
    $stmt->execute(['id' => (int)$_GET['remove']]);
    header('Location: cart.php');
    exit;
}

if (isset($_GET['qty'], $_GET['id']) && is_numeric($_GET['id'])) {
    $cartId = (int)$_GET['id'];
    if ($_GET['qty'] === 'up') {
        $db->prepare('UPDATE cart SET quantity = quantity + 1 WHERE id = ?')->execute([$cartId]);
    } elseif ($_GET['qty'] === 'down') {
        $stmt = $db->prepare('SELECT quantity FROM cart WHERE id = ?');
        $stmt->execute([$cartId]);
        $qty = (int)$stmt->fetchColumn();
        if ($qty > 1) {
            $db->prepare('UPDATE cart SET quantity = quantity - 1 WHERE id = ?')->execute([$cartId]);
        } else {
            $db->prepare('DELETE FROM cart WHERE id = ?')->execute([$cartId]);
        }
    }
    header('Location: cart.php');
    exit;
}

$cartProducts = $db->query('
    SELECT c.id AS cart_id, c.quantity, p.*
    FROM cart c
    JOIN products p ON c.product_id = p.id
')->fetchAll();

$itemCount = 0;
$totalPrice = 0;
foreach ($cartProducts as $item) {
    $itemCount += (int)$item['quantity'];
    $totalPrice += $item['price'] * $item['quantity'];
}

require_once 'includes/header.php';
?>

<section class="cart-page">
    <div class="cart-page-wrap">
        <header class="cart-page-header">
            <div class="cart-page-header-left">
                <a href="products.php" class="cart-back-btn" aria-label="Back to products">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <i class="fas fa-shopping-cart cart-page-header-icon"></i>
                <h1>Shopping Cart</h1>
            </div>
            <?php if ($itemCount > 0): ?>
                <span class="cart-item-badge"><?= $itemCount ?> Item<?= $itemCount === 1 ? '' : 's' ?></span>
            <?php endif; ?>
        </header>

        <?php if (empty($cartProducts)): ?>
            <div class="cart-empty">
                <i class="fas fa-shopping-cart"></i>
                <h2>Your cart is empty</h2>
                <p>Browse our ERP modules and add them to your cart.</p>
                <?= renderButton(['label' => 'Browse Products', 'href' => 'products.php', 'block' => true]) ?>
            </div>
        <?php else: ?>
            <div class="cart-page-layout">
                <div class="cart-items-box">
                    <?php foreach ($cartProducts as $index => $item):
                        $lineTotal = $item['price'] * $item['quantity'];
                        $isLast = $index === count($cartProducts) - 1;
                    ?>
                        <article class="cart-item-row<?= $isLast ? ' cart-item-row-last' : '' ?>">
                            <img
                                src="<?= htmlspecialchars($item['image_url']) ?>"
                                alt="<?= htmlspecialchars($item['name']) ?>"
                                class="cart-item-image"
                                onerror="this.src='assets/images/placeholder.jpg';"
                            >

                            <div class="cart-item-info">
                                <h3 class="cart-item-name"><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="cart-item-price">$<?= number_format($item['price'], 2) ?></p>
                            </div>

                            <div class="cart-qty-control">
                                <a href="cart.php?id=<?= (int)$item['cart_id'] ?>&qty=down" class="cart-qty-btn" aria-label="Decrease quantity">−</a>
                                <span class="cart-qty-value"><?= (int)$item['quantity'] ?></span>
                                <a href="cart.php?id=<?= (int)$item['cart_id'] ?>&qty=up" class="cart-qty-btn" aria-label="Increase quantity">+</a>
                            </div>

                            <span class="cart-line-total">$<?= number_format($lineTotal, 2) ?></span>

                            <a href="cart.php?remove=<?= (int)$item['cart_id'] ?>" class="cart-remove-btn" title="Remove item" aria-label="Remove item">
                                <i class="fas fa-trash"></i>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>

                <aside class="cart-summary-card">
                    <header class="cart-summary-header">
                        <i class="fas fa-shopping-cart"></i>
                        <h2>Order Summary</h2>
                    </header>

                    <div class="cart-summary-row">
                        <span>Subtotal (<?= $itemCount ?> item<?= $itemCount === 1 ? '' : 's' ?>):</span>
                        <strong id="cart-subtotal">$<?= number_format($totalPrice, 2) ?></strong>
                    </div>

                    <div class="cart-summary-total">
                        <span>Total:</span>
                        <strong id="cart-total">$<?= number_format($totalPrice, 2) ?></strong>
                    </div>

                    <p class="cart-summary-note">* Licensing &amp; implementation details confirmed at checkout</p>

                    <?= renderButton(['label' => 'Proceed to Checkout', 'href' => 'checkout.php', 'block' => true]) ?>
                    <?= renderButton(['label' => 'Continue Shopping', 'href' => 'products.php', 'variant' => 'secondary', 'block' => true]) ?>
                </aside>
            </div>
        <?php endif; ?>
    </div>
</section>


