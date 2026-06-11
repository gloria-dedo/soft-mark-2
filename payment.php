<?php
$pageTitle = 'Payment';
require_once 'includes/db.php';

$total = $_GET['total'] ?? '0.00';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mock payment success
    $success = true;
    
    // Clear cart
    $db->query("DELETE FROM cart");
}

require_once 'includes/header.php';
?>

<section class="page-hero">
    <div class="container animate-up">
        <p class="section-eyebrow">Checkout Step 2</p>
        <h1>Payment</h1>
    </div>
</section>

<section class="payment-section" style="padding: 80px 0;">
    <div class="container animate-up" style="animation-delay: 0.1s; max-width: 600px;">
        
        <?php if ($success): ?>
            <div class="form-success" style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 60px 40px;">
                <i class="fas fa-check-circle" style="font-size: 4rem; color: #22c55e; margin-bottom: 20px;"></i>
                <h2 style="font-size: 2rem; margin-bottom: 12px;">Payment Successful!</h2>
                <p style="color: var(--text-mid); margin-bottom: 24px;">Your order has been placed successfully. Thank you for choosing SoftMark.</p>
                <?= renderButton(['label' => 'Return to Home', 'href' => 'index.php', 'block' => true]) ?>
            </div>
        <?php else: ?>
            <div class="contact-form-col" style="border-radius: var(--radius);">
                <div style="text-align: center; margin-bottom: 32px;">
                    <h2 style="margin-bottom: 8px;">Total to Pay: <span style="color: var(--accent-blue);">$<?= htmlspecialchars($total) ?></span></h2>
                    <p style="color: var(--text-light); font-size: .9rem;">Please enter your payment details below (Mock Payment)</p>
                </div>
                
                <form method="POST" action="payment.php?total=<?= urlencode($total) ?>" class="contact-form">
                    <div class="form-group">
                        <label for="cardName">Name on Card</label>
                        <input type="text" id="cardName" name="cardName" placeholder="e.g. John Doe" required>
                    </div>
                    <div class="form-group">
                        <label for="cardNumber">Card Number</label>
                        <input type="text" id="cardNumber" name="cardNumber" placeholder="0000 0000 0000 0000" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry">Expiry Date</label>
                            <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" placeholder="123" required>
                        </div>
                    </div>
                    <?= renderButton([
                        'label' => 'Pay $' . htmlspecialchars($total) . ' Securely',
                        'type' => 'submit',
                        'icon' => 'fas fa-lock',
                        'block' => true,
                        'class' => 'btn-submit',
                        'attrs' => ['style' => 'margin-top: 16px'],
                    ]) ?>
                </form>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
