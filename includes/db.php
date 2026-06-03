<?php
// includes/db.php

$dbPath = __DIR__ . '/../database/erp_store.sqlite';

try {
    $db = new PDO("sqlite:" . $dbPath);
    // Set errormode to exceptions
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Use default fetch mode associative array
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
