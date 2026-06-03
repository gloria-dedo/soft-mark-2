<?php
require 'includes/db.php';
$cart = $db->query('SELECT * FROM cart')->fetchAll(PDO::FETCH_ASSOC);
print_r($cart);
?>
