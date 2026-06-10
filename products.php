<?php
$pageTitle = 'All ERP Products';
require_once 'includes/db.php';
require_once 'includes/categories.php';

$search   = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$sort     = $_GET['sort'] ?? 'name_asc';
$page     = max(1, (int) ($_GET['page'] ?? 1));
$perPage  = 10;

$query  = 'SELECT * FROM products';
$params = [];

if ($search !== '') {
    $query .= ' WHERE name LIKE :search OR description LIKE :search OR features LIKE :search';
    $params['search'] = "%$search%";
}

$stmt = $db->prepare($query);
$stmt->execute($params);
$allProducts = $stmt->fetchAll();

if ($category !== '') {
    $allProducts = filterProductsByCategory($allProducts, $category);
}

$allProducts = sortStoreProducts($allProducts, $sort);

$totalProducts = count($allProducts);
$totalPages    = max(1, (int) ceil($totalProducts / $perPage));
$page          = min($page, $totalPages);
$offset        = ($page - 1) * $perPage;
$pagedProducts = array_slice($allProducts, $offset, $perPage);

$rangeStart = $totalProducts > 0 ? $offset + 1 : 0;
$rangeEnd   = min($offset + $perPage, $totalProducts);

$activeCategoryLabel = 'All Software';
foreach (storeCategories() as $cat) {
    if ($cat['slug'] === $category) {
        $activeCategoryLabel = $cat['label'];
        break;
    }
}

if ($search !== '') {
    $headerTitle = 'Search Results';
    $pageTitle   = 'Search Results';
} else {
    $headerTitle = $activeCategoryLabel;
    $pageTitle   = $activeCategoryLabel;
}

$productCountLabel = $totalProducts . ' product' . ($totalProducts === 1 ? '' : 's') . ' found';

function productsListUrl(array $overrides = []): string {
    global $search, $category, $sort, $page;

    $params = array_filter([
        'search'   => $search,
        'category' => $category,
        'sort'     => $sort,
        'page'     => $page > 1 ? $page : null,
    ], static fn($value) => $value !== '' && $value !== null);

    $params = array_merge($params, $overrides);

    foreach ($params as $key => $value) {
        if ($value === '' || $value === null || ($key === 'page' && (int) $value <= 1)) {
            unset($params[$key]);
        }
    }

    $query = http_build_query($params);
    return $query !== '' ? 'products.php?' . $query : 'products.php';
}

require_once 'includes/header.php';
?>

<section class="products-page-section">
    <div class="container">
        <header class="products-category-header">
            <span class="products-category-accent" aria-hidden="true"></span>
            <div class="products-category-text">
                <h1><?= htmlspecialchars($headerTitle) ?></h1>
                <?php if ($search !== ''): ?>
                    <p class="products-search-query">for &ldquo;<?= htmlspecialchars($search) ?>&rdquo;</p>
                <?php endif; ?>
                <p class="products-category-count"><?= htmlspecialchars($productCountLabel) ?></p>
            </div>
            <div class="products-category-rule" aria-hidden="true"></div>
        </header>

        <div class="products-toolbar">
            <p class="products-range">
                Showing
                <strong><?= $rangeStart ?>&ndash;<?= $rangeEnd ?></strong>
                of
                <strong><?= $totalProducts ?></strong>
            </p>

            <form action="products.php" method="GET" class="products-sort-form">
                <?php if ($search !== ''): ?>
                    <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                <?php endif; ?>
                <?php if ($category !== ''): ?>
                    <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
                <?php endif; ?>
                <label class="products-sort-label" for="products-sort">Sort by</label>
                <div class="products-sort-wrap">
                    <i class="ti ti-arrows-sort products-sort-icon" aria-hidden="true"></i>
                    <select name="sort" id="products-sort" class="products-sort-select" onchange="this.form.submit()">
                        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
                        <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price (Low to High)</option>
                        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price (High to Low)</option>
                    </select>
                    <i class="ti ti-chevron-down products-sort-chevron" aria-hidden="true"></i>
                </div>
            </form>
        </div>

        <div class="product-grid products-page-grid">
            <?php if ($pagedProducts): ?>
                <?php foreach ($pagedProducts as $product): ?>
                    <?php include 'includes/product-card.php'; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>No products found<?= $search !== '' ? ' for your search.' : ' in this category.' ?></p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($totalProducts > $perPage): ?>
            <nav class="products-pagination" aria-label="Products pagination">
                <?php if ($page > 1): ?>
                    <a href="<?= htmlspecialchars(productsListUrl(['page' => $page - 1])) ?>" class="products-page-btn" aria-label="Previous page">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                <?php else: ?>
                    <span class="products-page-btn is-disabled" aria-hidden="true">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                <?php endif; ?>

                <div class="products-page-numbers">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i === $page): ?>
                            <span class="products-page-number is-active" aria-current="page"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= htmlspecialchars(productsListUrl(['page' => $i])) ?>" class="products-page-number"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <?php if ($page < $totalPages): ?>
                    <a href="<?= htmlspecialchars(productsListUrl(['page' => $page + 1])) ?>" class="products-page-btn" aria-label="Next page">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                <?php else: ?>
                    <span class="products-page-btn is-disabled" aria-hidden="true">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    </div>
</section>

<section class="cta-banner" id="products-cta">
    <div class="container cta-banner-inner">
        <div>
            <h2>Can't Find What You Need?</h2>
            <p>Our team can build custom ERP modules tailored to your specific business workflows.</p>
        </div>
        <a href="contact.php" class="btn btn-white" id="products-page-cta-btn">Contact Our Team</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
