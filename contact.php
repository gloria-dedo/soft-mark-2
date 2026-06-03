<?php
$pageTitle = 'Contact Us';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // In production, send email here. For now, just flag success.
        $success = true;
    }
}

require_once 'includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container animate-up">
        <p class="section-eyebrow">Get In Touch</p>
        <h1>Contact Us</h1>
        <p>Have a question or want a free demo? Our enterprise consultants are ready to help.</p>
    </div>
</section>

<!-- ============================
     CONTACT SECTION
============================== -->
<section class="contact-section">
    <div class="container contact-grid animate-up" style="animation-delay: 0.2s;">

        <!-- Contact Info -->
        <div class="contact-info-col">
            <h2>Let's Start A Conversation</h2>
            <p>Whether you're exploring ERP for the first time or looking to migrate, our team will walk you through the best solution for your organisation.</p>

            <div class="contact-details">
                <div class="contact-detail-item">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <strong>Head Office</strong>
                        <span>12 Innovation Drive, Tech Hub, Accra, Ghana</span>
                    </div>
                </div>
                <div class="contact-detail-item">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <strong>Phone</strong>
                        <span>+1 (800) 789-0000</span>
                    </div>
                </div>
                <div class="contact-detail-item">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <strong>Email</strong>
                        <span>hello@softmark-erp.com</span>
                    </div>
                </div>
                <div class="contact-detail-item">
                    <div class="contact-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <strong>Business Hours</strong>
                        <span>Mon – Fri: 8:00 AM – 6:00 PM GMT</span>
                    </div>
                </div>
            </div>

            <div class="contact-socials">
                <a href="#" class="social-btn" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="social-btn" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a>
                <a href="#" class="social-btn" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-col">
            <?php if ($success): ?>
                <div class="form-success" id="form-success-msg">
                    <i class="fas fa-circle-check"></i>
                    <h3>Message Sent!</h3>
                    <p>Thank you for reaching out. One of our consultants will be in touch within 24 hours.</p>
                    <a href="contact.php" class="btn btn-primary">Send Another Message</a>
                </div>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="form-error" id="form-error-msg">
                        <i class="fas fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="contact.php" class="contact-form" id="contact-form" novalidate>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name <span class="req">*</span></label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                placeholder="e.g. John Mensah"
                                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="email">Work Email <span class="req">*</span></label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="you@company.com"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                required
                            >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="company">Company Name</label>
                            <input 
                                type="text" 
                                id="company" 
                                name="company"
                                placeholder="e.g. Meridian Holdings"
                                value="<?= htmlspecialchars($_POST['company'] ?? '') ?>"
                            >
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <select id="subject" name="subject">
                                <option value="demo" <?= ($_POST['subject'] ?? '') === 'demo' ? 'selected' : '' ?>>Request a Free Demo</option>
                                <option value="pricing" <?= ($_POST['subject'] ?? '') === 'pricing' ? 'selected' : '' ?>>Pricing Enquiry</option>
                                <option value="support" <?= ($_POST['subject'] ?? '') === 'support' ? 'selected' : '' ?>>Technical Support</option>
                                <option value="other" <?= ($_POST['subject'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message">Message <span class="req">*</span></label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="6" 
                            placeholder="Tell us about your business needs and how we can help..."
                            required
                        ><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-submit" id="contact-submit-btn">
                        <i class="fas fa-paper-plane"></i> &nbsp;Send Message
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
