<?php
require_once 'includes/db.php';

header('Content-Type: application/json');

$query = trim($_GET['q'] ?? '');

if (mb_strlen($query) < 2) {
    echo json_encode([
        'success' => true,
        'query'   => $query,
        'total'   => 0,
        'results' => [],
    ]);
    exit;
}

$like = '%' . $query . '%';

$countStmt = $db->prepare(
    'SELECT COUNT(*) FROM products
     WHERE name LIKE :q OR description LIKE :q OR features LIKE :q'
);
$countStmt->execute(['q' => $like]);
$total = (int) $countStmt->fetchColumn();

$stmt = $db->prepare(
    'SELECT id, name, price, image_url FROM products
     WHERE name LIKE :q OR description LIKE :q OR features LIKE :q
     ORDER BY name ASC
     LIMIT 12'
);
$stmt->execute(['q' => $like]);
$rows = $stmt->fetchAll();

$results = array_map(static function (array $row): array {
    return [
        'id'        => (int) $row['id'],
        'name'      => $row['name'],
        'price'     => (float) $row['price'],
        'image_url' => imgUrl($row['image_url']),
    ];
}, $rows);

echo json_encode([
    'success' => true,
    'query'   => $query,
    'total'   => $total,
    'results' => $results,
]);
