<?php
$host = 'localhost';
$db   = 'paychangudemo';
$user = 'root'; // Default for WAMP
$pass = '';     // Default password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ DB Connection Failed: " . $e->getMessage());
}
?>
