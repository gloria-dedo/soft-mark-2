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
    <link rel="stylesheet" href="/sotfMark/assets/css/style.css">
</head>
<body>

    <!-- Store Header / Navbar -->
    <header class="store-header" id="store-header">
        <div class="store-header-inner container">

            <!-- Logo -->
            <a href="index.php" class="store-logo">
                <img src="/sotfMark/assets/images/logo.png" alt="SoftMark Logo">
            </a>

            <!-- Categories button — desktop only -->
            <button class="store-category-btn">
                <i class="fas fa-th-large"></i>
                <span>Categories</span>
                <i class="fas fa-chevron-down"></i>
            </button>

            <!-- Search bar -->
            <form class="store-search" id="store-search-form" action="products.php" method="GET">
                <input type="text" name="search" placeholder="Search products..." id="store-search-input">
                <button type="submit" aria-label="Search">
                    <i class="fas fa-search"></i>
                </button>
                <!-- Close search — mobile only -->
                <button type="button" class="store-search-close" id="store-search-close" aria-label="Close search">
                    <i class="fas fa-times"></i>
                </button>
            </form>

            <!-- Nav links — desktop only -->
            <ul class="store-nav">
                <li><a href="index.php" class="<?= $currentPage === 'index' ? 'active' : '' ?>">Home</a></li>
                <li><a href="products.php" class="<?= $currentPage === 'products' ? 'active' : '' ?>">Products</a></li>
                <li><a href="contact.php" class="<?= $currentPage === 'contact' ? 'active' : '' ?>">Contact Us</a></li>
            </ul>

            <!-- Action icons + mobile controls -->
            <div class="store-actions">
                <!-- Search icon — mobile only, opens search bar -->
                <button class="store-icon store-search-toggle" id="store-search-toggle" aria-label="Search" title="Search">
                    <i class="fas fa-search"></i>
                </button>

                <a href="wishlist.php" class="store-icon" title="Wishlist">
                    <i class="far fa-heart"></i>
                    <?php if ($wishlistCount > 0): ?>
                        <span class="store-badge"><?= $wishlistCount ?></span>
                    <?php endif; ?>
                </a>

                <a href="cart.php" class="store-icon" title="Cart">
                    <i class="fas fa-shopping-bag"></i>
                    <?php if ($cartCount > 0): ?>
                        <span class="store-badge"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>

                <!-- Hamburger — mobile only -->
                <button class="store-hamburger" id="store-hamburger" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

        </div>

        <!-- Mobile nav drawer -->
        <nav class="store-mobile-nav" id="store-mobile-nav" aria-label="Mobile navigation">
            <ul>
                <li><a href="index.php" class="<?= $currentPage === 'index' ? 'active' : '' ?>">Home</a></li>
                <li><a href="products.php" class="<?= $currentPage === 'products' ? 'active' : '' ?>">Products</a></li>
                <li><a href="contact.php" class="<?= $currentPage === 'contact' ? 'active' : '' ?>">Contact Us</a></li>
            </ul>
        </nav>

    </header>
