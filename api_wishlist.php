<?php
require_once 'includes/db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

if ($action === 'toggle' && $id > 0) {
    try {
        $stmt = $db->prepare("SELECT id FROM wishlist WHERE product_id = :id");
        $stmt->execute(['id' => $id]);
        if ($stmt->fetch()) {
            // Remove
            $del = $db->prepare("DELETE FROM wishlist WHERE product_id = :id");
            $del->execute(['id' => $id]);
            echo json_encode(['success' => true, 'status' => 'removed']);
        } else {
            // Add
            $ins = $db->prepare("INSERT INTO wishlist (product_id) VALUES (:id)");
            $ins->execute(['id' => $id]);
            echo json_encode(['success' => true, 'status' => 'added']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
}
?>
