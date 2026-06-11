<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/categories.php';

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
    <link rel="stylesheet" href="assets/css/carousel.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body>

    <!-- Store Header / Navbar -->
    <header class="store-header" id="store-header">
        <div class="store-header-inner container">

            <!-- Logo -->
            <a href="index.php" class="store-logo">
                <img src="assets/images/logoSoftMark.jpeg" alt="SoftMark Logo">
            </a>

            <!-- Desktop middle: categories, search, nav -->
            <div class="store-header-middle">
            <!-- Categories button — desktop only -->
            <div class="store-category-wrap" id="store-category-wrap">
                <button
                    type="button"
                    class="store-category-btn"
                    id="store-category-btn"
                    aria-expanded="false"
                    aria-haspopup="true"
                    aria-controls="store-category-dropdown"
                >
                    <i class="ti ti-category"></i>
                    <span>Categories</span>
                    <i class="ti ti-chevron-down store-category-chevron"></i>
                </button>
                <div class="store-category-dropdown" id="store-category-dropdown" hidden>
                    <ul class="store-category-list">
                        <?php foreach (storeCategories() as $cat): ?>
                            <li>
                                <a href="<?= categoryProductsUrl($cat['slug']) ?>">
                                    <i class="ti <?= htmlspecialchars($cat['icon']) ?>"></i>
                                    <?= htmlspecialchars($cat['label']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Search bar + live results -->
            <div class="store-search-wrap" id="store-search-wrap">
                <form class="store-search" id="store-search-form" action="products.php" method="GET" role="search">
                    <input
                        type="search"
                        name="search"
                        placeholder="Search products..."
                        id="store-search-input"
                        autocomplete="off"
                        aria-autocomplete="list"
                        aria-controls="store-search-dropdown"
                        aria-expanded="false"
                    >
                    <button type="submit" aria-label="Search">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="store-search-close" id="store-search-close" aria-label="Close search">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
                <div class="store-search-dropdown" id="store-search-dropdown" hidden role="region" aria-label="Search results"></div>
            </div>

            <!-- Nav links — desktop only -->
            <ul class="store-nav">
                <li><a href="index.php" class="<?= $currentPage === 'index' ? 'active' : '' ?>">Home</a></li>
                <li><a href="contact.php" class="<?= $currentPage === 'contact' ? 'active' : '' ?>">Contact Us</a></li>
            </ul>
            </div>

            <!-- Action icons + mobile controls -->
            <div class="store-actions">
                <!-- Search icon — mobile only, opens search bar -->
                <button class="store-icon store-search-toggle mobile-only"
                    id="store-search-toggle"
                    aria-label="Search"
                    title="Search">
                    <i class="fas fa-search"></i>
                </button>

                <a href="wishlist.php" class="store-icon mobile-nav-icon" id="store-wishlist" title="Wishlist">
                    <i class="far fa-heart" style="color: red;"></i>
                    <?php if ($wishlistCount > 0): ?>
                        <span class="store-badge"><?= $wishlistCount ?></span>
                    <?php endif; ?>
                </a>

                <a href="cart.php" class="store-icon mobile-nav-icon" id="store-cart" title="Cart">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if ($cartCount > 0): ?>
                        <span class="store-badge"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>

                <!-- Hamburger — mobile only -->
                <button class="store-hamburger mobile-only" id="store-hamburger" aria-label="Open menu" aria-expanded="false" aria-controls="store-mobile-nav">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

        </div>

        <!-- Mobile drawer -->
        <nav class="store-mobile-drawer" id="store-mobile-nav" aria-label="Mobile navigation" aria-hidden="true">
            <div class="store-mobile-drawer-header">
                <a href="index.php" class="store-mobile-drawer-logo">
                    <img src="assets/images/logoSoftMark.jpeg" alt="SoftMark Logo">
                </a>
                <button type="button" class="store-mobile-drawer-close" id="store-mobile-nav-close" aria-label="Close menu">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="store-mobile-tabs" role="tablist" aria-label="Mobile menu sections">
                <button
                    type="button"
                    class="store-mobile-tab is-active"
                    id="store-mobile-tab-categories"
                    role="tab"
                    aria-selected="true"
                    aria-controls="store-mobile-panel-categories"
                    data-mobile-tab="categories"
                >
                    Categories
                </button>
                <button
                    type="button"
                    class="store-mobile-tab"
                    id="store-mobile-tab-menu"
                    role="tab"
                    aria-selected="false"
                    aria-controls="store-mobile-panel-menu"
                    data-mobile-tab="menu"
                >
                    Menu
                </button>
            </div>

            <div
                class="store-mobile-panel is-active"
                id="store-mobile-panel-categories"
                role="tabpanel"
                aria-labelledby="store-mobile-tab-categories"
                data-mobile-panel="categories"
            >
                <p class="store-mobile-panel-label">Shop by category</p>
                <ul class="store-mobile-category-list">
                    <?php foreach (storeCategories() as $cat): ?>
                        <li>
                            <a href="<?= categoryProductsUrl($cat['slug']) ?>" class="store-mobile-category-link">
                                <span class="store-mobile-cat-icon" aria-hidden="true">
                                    <i class="ti ti-tag"></i>
                                </span>
                                <span class="store-mobile-cat-name"><?= htmlspecialchars($cat['label']) ?></span>
                                <i class="ti ti-chevron-right store-mobile-cat-arrow" aria-hidden="true"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div
                class="store-mobile-panel"
                id="store-mobile-panel-menu"
                role="tabpanel"
                aria-labelledby="store-mobile-tab-menu"
                data-mobile-panel="menu"
                hidden
            >
                <p class="store-mobile-panel-label">Quick links</p>
                <ul class="store-mobile-menu-list">
                    <li>
                        <a href="index.php" class="store-mobile-menu-link <?= $currentPage === 'index' ? 'active' : '' ?>">
                            <span class="store-mobile-cat-icon" aria-hidden="true">
                                <i class="ti ti-home"></i>
                            </span>
                            <span class="store-mobile-cat-name">Home</span>
                            <i class="ti ti-chevron-right store-mobile-cat-arrow" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a href="contact.php" class="store-mobile-menu-link <?= $currentPage === 'contact' ? 'active' : '' ?>">
                            <span class="store-mobile-cat-icon" aria-hidden="true">
                                <i class="ti ti-mail"></i>
                            </span>
                            <span class="store-mobile-cat-name">Contact Us</span>
                            <i class="ti ti-chevron-right store-mobile-cat-arrow" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

    </header>
