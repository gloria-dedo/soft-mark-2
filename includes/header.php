<?php
require_once __DIR__ . '/db.php';

// Fetch cart and wishlist counts for display in navbar
$cartCount = $db->query("SELECT SUM(quantity) FROM cart")->fetchColumn() ?: 0;
$wishlistCount = $db->query("SELECT COUNT(*) FROM wishlist")->fetchColumn() ?: 0;

// Fetch all wishlist product IDs for page-wide use
$wishlistItems = $db->query("SELECT product_id FROM wishlist")->fetchAll(PDO::FETCH_COLUMN);
$cartItems = $db->query("SELECT product_id FROM cart")->fetchAll(PDO::FETCH_COLUMN);

// Determine current page for active nav link
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SoftMark ERP - Enterprise Resource Planning software solutions for accounting, HR, inventory and more.">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | SoftMark ERP' : 'SoftMark ERP — Enterprise Solutions'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Top announcement bar -->
    <div class="top-bar">
        <div class="container">
            <span><i class="fas fa-headset"></i> &nbsp;Expert Support: Mon–Fri, 8am–6pm</span>
            <span><i class="fas fa-shield-halved"></i> &nbsp;30-Day Money Back Guarantee</span>
            <span><i class="fas fa-phone"></i> &nbsp;+1 (800) 789-0000</span>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar" id="main-navbar">
        <div class="container nav-inner">
            <!-- Logo -->
            <a href="index.php" class="logo" id="nav-logo">
                <img src="assets/images/logo.png" alt="SoftMark Logo" style="height: 48px; width: auto; display: block;">
            </a>

            <!-- Nav Links -->
            <ul class="nav-links" id="nav-links">
                <li><a href="index.php" class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>">Home</a></li>
                <li><a href="products.php" class="nav-link <?= $currentPage === 'products' ? 'active' : '' ?>">Products</a></li>
                <li><a href="contact.php" class="nav-link <?= $currentPage === 'contact' ? 'active' : '' ?>">Contact Us</a></li>
            </ul>

            <!-- Nav Icons -->
            <div class="nav-icons-group">
                <a href="wishlist.php" class="nav-icon-link" id="nav-wishlist" title="Wishlist">
                    <i class="fas fa-heart"></i>
                    <?php if ($wishlistCount > 0): ?>
                        <span class="badge badge-red"><?= $wishlistCount ?></span>
                    <?php endif; ?>
                </a>
                <a href="cart.php" class="nav-icon-link" id="nav-cart" title="Cart">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if ($cartCount > 0): ?>
                        <span class="badge badge-blue"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
                <!-- Mobile hamburger -->
                <button class="hamburger" id="hamburger-btn" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
        <!-- Mobile nav drawer -->
        <div class="mobile-nav" id="mobile-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>
    </nav>
