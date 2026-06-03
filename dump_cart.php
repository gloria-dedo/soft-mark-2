<?php
require 'includes/db.php';
$cartItems = $db->query("
    SELECT c.id as cart_id, c.quantity, p.* 
    FROM cart c 
    JOIN products p ON c.product_id = p.id
")->fetchAll();
var_dump($cartItems);
?>
