<?php
require_once 'includes/db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

if ($action === 'add' && $id > 0) {
    try {
        // Check if already in cart
        $stmt = $db->prepare("SELECT id FROM cart WHERE product_id = :id");
        $stmt->execute(['id' => $id]);
        if (!$stmt->fetch()) {
            $insert = $db->prepare("INSERT INTO cart (product_id) VALUES (:id)");
            $insert->execute(['id' => $id]);
        }
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
}
?>
