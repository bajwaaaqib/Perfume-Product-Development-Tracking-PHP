<?php
require 'includes/auth.php';
require_once 'includes/db.php';

header('Content-Type: application/json');

if (isset($_GET['product_id'])) {
    $productId = (int)$_GET['product_id'];
    $stmt = $pdo->prepare("SELECT comments FROM perfume_products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'comments' => $product['comments'] ?? ''
    ]);
    exit;
}

echo json_encode(['comments' => '']);
?>