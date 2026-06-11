<?php
$pageTitle = 'Checkout';
require_once 'includes/db.php';

$checkoutItems = $db->query('
    SELECT c.id AS cart_id, c.quantity, p.*
    FROM cart c
    JOIN products p ON c.product_id = p.id
')->fetchAll();

$itemCount = 0;
$subtotal = 0;
foreach ($checkoutItems as $item) {
    $itemCount += (int)$item['quantity'];
    $subtotal += $item['price'] * $item['quantity'];
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contactName = trim($_POST['contactName'] ?? '');
    $company     = trim($_POST['company'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');
    $orderNote   = trim($_POST['orderNote'] ?? '');
    $payMethod   = $_POST['paymentMethod'] ?? 'invoice';

    if (!$contactName) $errors[] = 'Contact name is required.';
    if (!$company)     $errors[] = 'Company name is required.';
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid work email is required.';
    if (!$phone)       $errors[] = 'Phone number is required.';

    if (empty($errors) && count($checkoutItems) > 0) {
        $db->exec('DELETE FROM cart');
        $success = true;
    }
}

require_once 'includes/header.php';
?>

<section class="checkout-page">
    <div class="checkout-page-wrap">
        <?php if ($success): ?>
            <div class="checkout-success">
                <i class="fas fa-circle-check"></i>
                <h2>Order Placed Successfully</h2>
                <p>Thank you for choosing SoftMark. Our team will contact you shortly to begin onboarding.</p>
                <?= renderButton(['label' => 'Continue Shopping', 'href' => 'products.php', 'block' => true]) ?>
            </div>

        <?php elseif (empty($checkoutItems)): ?>
            <div class="cart-empty">
                <i class="fas fa-shopping-cart"></i>
                <h2>Your cart is empty</h2>
                <p>Add products before checking out.</p>
                <?= renderButton(['label' => 'Browse Products', 'href' => 'products.php', 'block' => true]) ?>
            </div>

        <?php else: ?>
            <header class="checkout-page-header">
                <div class="checkout-page-title">
                    <span class="checkout-title-bar"></span>
                    <div>
                        <h1>Checkout</h1>
                        <p><?= $itemCount ?> item<?= $itemCount === 1 ? '' : 's' ?> in cart</p>
                    </div>
                </div>
            </header>

            <?php if (!empty($errors)): ?>
                <div class="checkout-form-error">
                    <i class="fas fa-circle-exclamation"></i>
                    <?= htmlspecialchars(implode(' ', $errors)) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="checkout.php" class="checkout-page-layout">
                <div class="checkout-billing-card">
                    <h2 class="checkout-card-title">Billing Information</h2>

                    <input type="text" name="company" placeholder="Company Name *" value="<?= htmlspecialchars($_POST['company'] ?? '') ?>" required>
                    <input type="text" name="contactName" placeholder="Contact Name *" value="<?= htmlspecialchars($_POST['contactName'] ?? '') ?>" required>
                    <input type="email" name="email" placeholder="Work Email *" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    <input type="tel" name="phone" placeholder="Phone Number *" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
                    <textarea name="orderNote" rows="4" placeholder="Order Note (Optional)"><?= htmlspecialchars($_POST['orderNote'] ?? '') ?></textarea>
                </div>

                <aside class="checkout-summary-card">
                    <h2 class="checkout-card-title">Order Summary</h2>

                    <div class="checkout-items-list">
                        <?php foreach ($checkoutItems as $item):
                            $lineTotal = $item['price'] * $item['quantity'];
                        ?>
                            <div class="checkout-item">
                                <img
                                    src="<?= htmlspecialchars($item['image_url']) ?>"
                                    alt="<?= htmlspecialchars($item['name']) ?>"
                                    onerror="this.src='assets/images/placeholder.jpg';"
                                >
                                <div class="checkout-item-info">
                                    <strong><?= htmlspecialchars($item['name']) ?></strong>
                                    <span class="checkout-item-unit">Unit: $<?= number_format($item['price'], 2) ?></span>
                                    <span class="checkout-qty-badge">Qty <?= (int)$item['quantity'] ?></span>
                                </div>
                                <span class="checkout-item-total">$<?= number_format($lineTotal, 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="checkout-summary-row">
                        <span>Subtotal</span>
                        <strong>$<?= number_format($subtotal, 2) ?></strong>
                    </div>
                    <div class="checkout-summary-row checkout-summary-muted">
                        <span>Implementation</span>
                        <span>Included</span>
                    </div>

                    <div class="checkout-summary-total">
                        <span>Total Amount</span>
                        <strong>$<?= number_format($subtotal, 2) ?></strong>
                    </div>

                    <div class="checkout-payment">
                        <h3>Payment Method</h3>
                        <label class="checkout-payment-option">
                            <input type="radio" name="paymentMethod" value="invoice" checked>
                            <span>Invoice via Email</span>
                        </label>
                    </div>

                    <?= renderButton([
                        'label' => 'Place Order',
                        'type' => 'submit',
                        'icon' => 'fas fa-shopping-cart',
                        'block' => true,
                    ]) ?>
                </aside>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
