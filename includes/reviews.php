<?php
/**
 * Product review helpers.
 */

function fetchProductReviews(PDO $db, int $productId): array {
    $stmt = $db->prepare(
        'SELECT id, reviewer_name, rating, comment, created_at
         FROM reviews
         WHERE product_id = :id
         ORDER BY created_at DESC'
    );
    $stmt->execute(['id' => $productId]);
    return $stmt->fetchAll();
}

function buildReviewStats(array $reviews): array {
    $count = count($reviews);
    $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

    if ($count === 0) {
        return [
            'count'          => 0,
            'average'        => 0,
            'distribution'   => $distribution,
            'distribution_pct' => array_fill(1, 5, 0),
        ];
    }

    $sum = 0;
    foreach ($reviews as $review) {
        $rating = (int) $review['rating'];
        $sum += $rating;
        if (isset($distribution[$rating])) {
            $distribution[$rating]++;
        }
    }

    $distributionPct = [];
    foreach ($distribution as $star => $value) {
        $distributionPct[$star] = round(($value / $count) * 100);
    }

    return [
        'count'            => $count,
        'average'          => round($sum / $count, 1),
        'distribution'     => $distribution,
        'distribution_pct' => $distributionPct,
    ];
}

function renderReviewStars(float $rating, string $extraClass = ''): string {
    $classAttr = $extraClass !== '' ? ' class="' . htmlspecialchars($extraClass) . '"' : '';
    $stars = "<span{$classAttr}>";

    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($rating)) {
            $stars .= '<i class="fas fa-star"></i>';
        } elseif ($i - $rating < 1) {
            $stars .= '<i class="fas fa-star-half-stroke"></i>';
        } else {
            $stars .= '<i class="far fa-star"></i>';
        }
    }

    $stars .= '</span>';
    return $stars;
}

function reviewerInitials(string $name): string {
    $parts = preg_split('/\s+/', trim($name));
    $initials = '';

    foreach (array_slice($parts, 0, 2) as $part) {
        $initials .= strtoupper(substr($part, 0, 1));
    }

    return $initials !== '' ? $initials : '?';
}

function formatReviewDate(string $datetime): string {
    $timestamp = strtotime($datetime);
    return $timestamp ? date('F j, Y', $timestamp) : $datetime;
}

function syncProductRating(PDO $db, int $productId): void {
    $stmt = $db->prepare(
        'SELECT ROUND(AVG(rating), 2) AS avg_rating
         FROM reviews
         WHERE product_id = :id'
    );
    $stmt->execute(['id' => $productId]);
    $avg = $stmt->fetchColumn();

    if ($avg === false || $avg === null) {
        return;
    }

    $update = $db->prepare('UPDATE products SET rating = :rating WHERE id = :id');
    $update->execute(['rating' => $avg, 'id' => $productId]);
}
