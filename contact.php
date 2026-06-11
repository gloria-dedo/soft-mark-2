<?php
$pageTitle = 'Contact Us';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $success = true;
    }
}

$mapQuery = urlencode('VJW4+3VR Bsalim, Lebanon');
$mapEmbed = "https://www.google.com/maps?q={$mapQuery}&output=embed";

require_once 'includes/header.php';
?>

<section class="contact-hero">
    <div class="contact-hero-pattern" aria-hidden="true"></div>
    <div class="container contact-hero-inner">
        <span class="contact-hero-badge">
            <i class="fas fa-envelope"></i>
            We're here to help
        </span>
        <h1>Get In Touch</h1>
        <p>Have a question, feedback, or need support? Reach out and we'll respond as soon as possible.</p>
    </div>
</section>

<nav class="contact-breadcrumb" aria-label="Breadcrumb">
    <div class="contact-page-wrap">
        <a href="index.php">Home</a>
        <i class="fas fa-chevron-right" aria-hidden="true"></i>
        <span>Contact Us</span>
    </div>
</nav>

<section class="contact-page-section">
    <div class="contact-page-wrap">
        <div class="contact-page-layout">
            <div class="contact-page-left">
                <article class="contact-card contact-info-card">
                    <header class="contact-card-header">
                        <div class="contact-card-icon" aria-hidden="true"><i class="fas fa-phone"></i></div>
                        <h2>Contact Information</h2>
                    </header>

                    <ul class="contact-info-list">
                        <li>
                            <div class="contact-info-icon" aria-hidden="true"><i class="fas fa-phone"></i></div>
                            <div>
                                <strong>Phone</strong>
                                <a href="tel:+9613552021">+9613552021</a>
                            </div>
                        </li>
                        <li>
                            <div class="contact-info-icon" aria-hidden="true"><i class="fas fa-envelope"></i></div>
                            <div>
                                <strong>Email</strong>
                                <a href="mailto:tony@compusoft.com.ng">tony@compusoft.com.ng</a>
                            </div>
                        </li>
                        <li>
                            <div class="contact-info-icon" aria-hidden="true"><i class="fas fa-location-dot"></i></div>
                            <div>
                                <strong>Address</strong>
                                <span>33°53'42.8"N 35°36'25.7"E, VJW4+3VR Bsalim, Lebanon</span>
                            </div>
                        </li>
                        <li>
                            <div class="contact-info-icon" aria-hidden="true"><i class="fas fa-clock"></i></div>
                            <div>
                                <strong>Business Hours</strong>
                                <span>Mon – Sat: 8:00 AM – 6:00 PM | Holidays: 8:00 AM – 5:00 PM</span>
                            </div>
                        </li>
                    </ul>
                </article>

                <article class="contact-card contact-whatsapp-card">
                    <header class="contact-card-header">
                        <div class="contact-card-icon" aria-hidden="true"><i class="fab fa-whatsapp"></i></div>
                        <h2>Quick Chat</h2>
                    </header>
                    <?= renderButton([
                        'label' => 'Chat with us on WhatsApp',
                        'href' => 'https://wa.link/vj2b6r',
                        'icon' => 'fab fa-whatsapp',
                        'iconRight' => 'fas fa-arrow-right',
                        'block' => true,
                        'attrs' => ['target' => '_blank', 'rel' => 'noopener noreferrer'],
                    ]) ?>
                    <p class="contact-whatsapp-note">Typically replies within a few minutes</p>
                </article>
            </div>

            <div class="contact-page-right">
                <article class="contact-card contact-form-card">
                    <header class="contact-card-header">
                        <div class="contact-card-icon" aria-hidden="true"><i class="fas fa-paper-plane"></i></div>
                        <h2>Send us a Message</h2>
                    </header>

                    <?php if ($success): ?>
                        <div class="contact-form-success">
                            <i class="fas fa-circle-check"></i>
                            <h3>Message Sent!</h3>
                            <p>Thank you for reaching out. We'll get back to you as soon as possible.</p>
                            <?= renderButton(['label' => 'Send Another Message', 'href' => 'contact.php', 'block' => true]) ?>
                        </div>
                    <?php else: ?>
                        <?php if ($error): ?>
                            <div class="contact-form-error">
                                <i class="fas fa-circle-exclamation"></i>
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="contact.php" class="contact-page-form" novalidate>
                            <div class="contact-form-row">
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="contact-field-input"
                                    placeholder="Your Name"
                                    value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                    required
                                >
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="contact-field-input"
                                    placeholder="Your Email"
                                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                    required
                                >
                            </div>

                            <input
                                type="text"
                                id="subject"
                                name="subject"
                                class="contact-field-input"
                                placeholder="Subject"
                                value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>"
                            >

                            <textarea
                                id="message"
                                name="message"
                                class="contact-field-input contact-field-textarea"
                                rows="6"
                                placeholder="Write your message here..."
                                required
                            ><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

                            <?= renderButton(['label' => 'Send Message', 'type' => 'submit', 'block' => true]) ?>
                        </form>
                    <?php endif; ?>
                </article>
            </div>
        </div>

        <article class="contact-card contact-map-card">
            <header class="contact-card-header">
                <div class="contact-card-icon" aria-hidden="true"><i class="fas fa-location-dot"></i></div>
                <h2>Find Us Here</h2>
            </header>
            <div class="contact-map-wrap">
                <iframe
                    src="<?= htmlspecialchars($mapEmbed) ?>"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="SoftMark office location in Bsalim, Lebanon"
                ></iframe>
            </div>
        </article>
    </div>
</section>

