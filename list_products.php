<?php
require_once 'includes/db.php';
$rows = $db->query('SELECT id, name, image_url FROM products ORDER BY id')->fetchAll();
foreach ($rows as $r) {
    echo $r['id'] . ' | ' . $r['name'] . ' | ' . $r['image_url'] . PHP_EOL;
}
?>
