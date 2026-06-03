<?php
require_once 'includes/db.php';

// Remove duplicate products (IDs 16-21 from seed_more.php that overlap with 7-15)
// Keep: 1-15 (original + second seed batch), remove 16-21 (third seed with duplicates)
$db->exec("DELETE FROM products WHERE id IN (16, 17, 18, 21)");

// Verify remaining
$rows = $db->query('SELECT id, name, image_url FROM products ORDER BY id')->fetchAll();
echo "Remaining products:\n";
foreach ($rows as $r) {
    echo $r['id'] . ' | ' . $r['name'] . ' | ' . $r['image_url'] . PHP_EOL;
}
echo "\nTotal: " . count($rows) . " products\n";
?>
