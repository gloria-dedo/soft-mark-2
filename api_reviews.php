<?php
require_once 'includes/db.php';
require_once 'includes/reviews.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $productId = (int) ($_GET['product_id'] ?? 0);

    if ($productId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid product']);
        exit;
    }

    $reviews = fetchProductReviews($db, $productId);
    $stats = buildReviewStats($reviews);

    echo json_encode([
        'success' => true,
        'stats'   => $stats,
        'reviews' => array_map(static function (array $review): array {
            return [
                'id'             => (int) $review['id'],
                'reviewer_name'  => $review['reviewer_name'],
                'rating'         => (int) $review['rating'],
                'comment'        => $review['comment'],
                'created_at'     => formatReviewDate($review['created_at']),
                'initials'       => reviewerInitials($review['reviewer_name']),
            ];
        }, $reviews),
    ]);
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    $productId = (int) ($input['product_id'] ?? 0);
    $name = trim($input['reviewer_name'] ?? $input['name'] ?? '');
    $rating = (int) ($input['rating'] ?? 0);
    $comment = trim($input['comment'] ?? '');

    if ($productId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid product']);
        exit;
    }

    if ($name === '' || mb_strlen($name) < 2) {
        echo json_encode(['success' => false, 'error' => 'Please enter your name']);
        exit;
    }

    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'error' => 'Please select a rating']);
        exit;
    }

    if ($comment === '' || mb_strlen($comment) < 10) {
        echo json_encode(['success' => false, 'error' => 'Review must be at least 10 characters']);
        exit;
    }

    $productCheck = $db->prepare('SELECT id FROM products WHERE id = :id');
    $productCheck->execute(['id' => $productId]);
    if (!$productCheck->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Product not found']);
        exit;
    }

    try {
        $insert = $db->prepare(
            'INSERT INTO reviews (product_id, reviewer_name, rating, comment)
             VALUES (:product_id, :reviewer_name, :rating, :comment)'
        );
        $insert->execute([
            'product_id'     => $productId,
            'reviewer_name'  => $name,
            'rating'         => $rating,
            'comment'        => $comment,
        ]);

        syncProductRating($db, $productId);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Could not save review']);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
