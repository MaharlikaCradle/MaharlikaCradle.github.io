<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'maharlikascradledb';
$username = 'root';
$password = ''; // By default, XAMPP's MySQL has no password for root user

// Attempt database connection using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>