<?php
// Fetch up to 6 products to use as carousel slides
$carouselSlides = $db->query(
    "SELECT id, name, description, image_url FROM products ORDER BY rating DESC LIMIT 6"
)->fetchAll();

// Truncate description to a short teaser (~100 chars)
function carouselTeaser(string $text, int $max = 100): string {
    return mb_strlen($text) > $max
        ? mb_substr($text, 0, $max - 1) . '…'
        : $text;
}
?>

<div class="carousel">

    <?php foreach ($carouselSlides as $index => $slide): ?>
        <div class="slide <?= $index === 0 ? 'active' : '' ?>"
             style="background-image:url('<?= imgUrl($slide['image_url']) ?>')">

            <div class="overlay"></div>

            <div class="slide-container">

                <div class="carousel-top-bar">
                    <div class="top-ad">
                        🔥 Featured
                    </div>
                </div>

                <div class="content">
                    <h2><?= htmlspecialchars($slide['name']) ?></h2>
                    <p><?= htmlspecialchars(carouselTeaser($slide['description'])) ?></p>
                </div>

                <div class="bottom-row">
                    <a href="product-detail.php?id=<?= $slide['id'] ?>" class="buy-btn">
                        View Details
                    </a>

                    <div class="navigation">
                        <button class="nav prev">&#10094;</button>
                        <button class="nav next">&#10095;</button>
                    </div>
                </div>

            </div>

        </div>
    <?php endforeach; ?>

</div>

<script src="assets/js/carousel.js"></script>
