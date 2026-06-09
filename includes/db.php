<?php
// includes/db.php

$host = '127.0.0.1';
$dbname = 'erp_store';
$username = 'root';
$password = ''; // Default XAMPP MySQL password is empty

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set errormode to exceptions
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Use default fetch mode associative array
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

/**
 * Returns an image URL with a cache-busting ?v= timestamp.
 * Uses the file's last-modified time so the query string only changes
 * when the file actually changes on disk.
 *
 * @param string $url  Relative path as stored in DB e.g. "assets/images/foo.jpg"
 * @return string      URL safe to use in src=""
 */
function imgUrl(string $url): string {
    // Build the absolute server path from the project root
    $root = dirname(__DIR__); // sotfMark/
    $filePath = $root . DIRECTORY_SEPARATOR . ltrim($url, '/\\');

    $version = file_exists($filePath) ? filemtime($filePath) : 1;
    return htmlspecialchars($url) . '?v=' . $version;
}

