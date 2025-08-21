<?php
$host = 'localhost';
$db = 'marcoluc_npd';
$user = 'marcoluc_npd';
$pass = 'npD183#1AaQib';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection Failed: " . $e->getMessage());
}
?>
