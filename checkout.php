<?php
$pageTitle = 'Checkout';
require_once 'includes/db.php';

// Fetch cart items with product details
$checkoutItems = $db->query("
    SELECT c.id as cart_id, c.quantity, p.* 
    FROM cart c 
    JOIN products p ON c.product_id = p.id
")->fetchAll();

$subtotal = 0;
foreach ($checkoutItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName  = trim($_POST['lastName'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $company   = trim($_POST['company'] ?? '');
    $payMethod = $_POST['paymentMethod'] ?? 'email';

    if (!$firstName) $errors[] = 'First name is required.';
    if (!$lastName)  $errors[] = 'Last name is required.';
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (!$company) $errors[] = 'Company name is required.';

    if (empty($errors) && count($checkoutItems) > 0) {
        // In a real app: save the order, process payment, send email
        // Clear cart after success
        $db->exec("DELETE FROM cart");
        $success = true;
    }
}

require_once 'includes/header.php';
?>

<section class="page-hero">
    <div class="container animate-up">
        <p class="section-eyebrow">Secure Checkout</p>
        <h1>Complete Your Order</h1>
    </div>
</section>

<section class="checkout-section" style="padding: 60px 0 80px;">
    <div class="container animate-up" style="animation-delay: 0.1s;">

        <?php if ($success): ?>
            <!-- SUCCESS STATE -->
            <div style="text-align: center; padding: 80px 20px; background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); max-width: 600px; margin: 0 auto;">
                <i class="fas fa-check-circle" style="font-size: 4rem; color: #22c55e; margin-bottom: 20px;"></i>
                <h2 style="margin-bottom: 12px; font-size: 2rem;">Order Placed Successfully!</h2>
                <p style="color: var(--text-mid); margin-bottom: 8px;">Thank you for choosing SoftMark. A confirmation email has been sent.</p>
                <p style="color: var(--text-light); font-size: .85rem; margin-bottom: 32px;">Our team will contact you shortly to begin onboarding.</p>
                <a href="products.php" class="btn btn-primary">Continue Shopping</a>
            </div>

        <?php elseif (empty($checkoutItems)): ?>
            <!-- EMPTY CART -->
            <div style="text-align: center; padding: 60px 20px; background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);">
                <i class="fas fa-shopping-cart" style="font-size: 3.5rem; color: var(--border); margin-bottom: 20px;"></i>
                <h2 style="margin-bottom: 12px;">Your cart is empty.</h2>
                <p style="color: var(--text-mid); margin-bottom: 24px;">Add some products before checking out.</p>
                <a href="products.php" class="btn btn-red">Browse Products</a>
            </div>

        <?php else: ?>
            <!-- CHECKOUT FORM -->
            <?php if (!empty($errors)): ?>
                <div class="form-error" style="margin-bottom: 24px; border-radius: var(--radius);">
                    <i class="fas fa-circle-exclamation"></i>
                    <?= implode(' ', $errors) ?>
                </div>
            <?php endif; ?>

            <div class="checkout-layout" style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 48px; align-items: flex-start;">
                
                <!-- LEFT: Form -->
                <div>
                    <form method="POST" action="checkout.php" id="checkout-form">
                        <!-- Personal Information -->
                        <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 32px; margin-bottom: 24px;">
                            <h2 style="font-size: 1.2rem; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                                <span style="width: 32px; height: 32px; background: var(--accent-red); color: var(--white); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: .85rem; font-weight: 700;">1</span>
                                Personal Information
                            </h2>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">First Name <span class="req">*</span></label>
                                    <input type="text" id="firstName" name="firstName" placeholder="John" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastName">Last Name <span class="req">*</span></label>
                                    <input type="text" id="lastName" name="lastName" placeholder="Doe" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email <span class="req">*</span></label>
                                    <input type="email" id="email" name="email" placeholder="you@company.com" required>
                                </div>
                                <div class="form-group">
                                    <label for="company">Company Name <span class="req">*</span></label>
                                    <input type="text" id="company" name="company" placeholder="Acme Corp" required>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 32px; margin-bottom: 24px;">
                            <h2 style="font-size: 1.2rem; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                                <span style="width: 32px; height: 32px; background: var(--primary-blue); color: var(--white); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: .85rem; font-weight: 700;">2</span>
                                Payment Method
                            </h2>
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                <label style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; border: 1px solid var(--border); border-radius: var(--radius); cursor: pointer; transition: var(--transition);" class="payment-option">
                                    <input type="radio" name="paymentMethod" value="email" checked style="accent-color: var(--accent-blue);">
                                    <i class="fas fa-envelope" style="color: var(--accent-blue); width: 20px;"></i>
                                    <span style="font-weight: 600; font-size: .9rem;">Invoice via Email</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; border: 1px solid var(--border); border-radius: var(--radius); cursor: pointer; opacity: .5;" class="payment-option">
                                    <input type="radio" name="paymentMethod" value="credit" disabled>
                                    <i class="fas fa-credit-card" style="color: var(--accent-red); width: 20px;"></i>
                                    <span style="font-weight: 600; font-size: .9rem;">Credit Card <span style="font-weight: 400; color: var(--text-light);">— Coming Soon</span></span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; border: 1px solid var(--border); border-radius: var(--radius); cursor: pointer; opacity: .5;" class="payment-option">
                                    <input type="radio" name="paymentMethod" value="paypal" disabled>
                                    <i class="fab fa-paypal" style="color: #003087; width: 20px;"></i>
                                    <span style="font-weight: 600; font-size: .9rem;">PayPal <span style="font-weight: 400; color: var(--text-light);">— Coming Soon</span></span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-red" style="width: 100%; justify-content: center; padding: 16px; font-size: 1rem;">
                            <i class="fas fa-lock"></i> &nbsp;Place Order — $<?= number_format($subtotal, 2) ?>
                        </button>
                        <p style="text-align: center; font-size: .8rem; color: var(--text-light); margin-top: 12px;">
                            <i class="fas fa-shield-halved"></i> &nbsp;Your data is protected with 256-bit SSL encryption.
                        </p>
                    </form>
                </div>

                <!-- RIGHT: Order Summary -->
                <div style="position: sticky; top: 100px;">
                    <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 32px;">
                        <h2 style="font-size: 1.2rem; margin-bottom: 24px;">Order Summary</h2>

                        <!-- Product List -->
                        <div style="max-height: 340px; overflow-y: auto; margin-bottom: 24px;">
                            <?php foreach ($checkoutItems as $item): ?>
                                <div style="display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid var(--border);">
                                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"
                                         style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; flex-shrink: 0;"
                                         onerror="this.src='assets/images/placeholder.jpg';">
                                    <div style="flex: 1; min-width: 0;">
                                        <strong style="display: block; font-size: .9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($item['name']) ?></strong>
                                        <span style="font-size: .8rem; color: var(--text-light);">Qty: <?= $item['quantity'] ?></span>
                                    </div>
                                    <span style="font-weight: 700; font-size: .95rem; white-space: nowrap;">$<?= number_format($item['price'], 2) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Totals -->
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: .9rem; color: var(--text-mid);">
                            <span>Subtotal (<?= count($checkoutItems) ?> items)</span>
                            <span>$<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: .9rem; color: var(--text-mid);">
                            <span>Implementation</span>
                            <span style="color: #22c55e; font-weight: 600;">Included</span>
                        </div>
                        <div style="height: 1px; background: var(--border); margin: 16px 0;"></div>
                        <div style="display: flex; justify-content: space-between; font-weight: 900; font-size: 1.3rem;">
                            <span>Total</span>
                            <span style="color: var(--accent-red);">$<?= number_format($subtotal, 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
