<?php
require 'includes/auth.php';
require 'includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: view_products.php');
    exit;
}

$id = (int)$_GET['id'];

// Delete product by ID
$stmt = $pdo->prepare("DELETE FROM perfume_products WHERE id = ?");
$stmt->execute([$id]);

header('Location: view_products.php');
exit;
