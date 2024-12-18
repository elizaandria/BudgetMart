<?php
// Database credentials
$host = 'localhost';
$dbname = 'user_accounts'; // Replace with your actual database name
$user = 'root'; // Default username for PHPMyAdmin
$pass = ''; // Default password for PHPMyAdmin (empty by default)

// Establishing the database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
