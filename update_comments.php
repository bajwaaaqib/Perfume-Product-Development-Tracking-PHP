<?php
require 'includes/auth.php';
require_once 'includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'], $_POST['product_id'])) {
    $comment = trim($_POST['comment']);
    $productId = (int)$_POST['product_id'];
    
    if (!empty($comment)) {
        $stmt = $pdo->prepare("UPDATE perfume_products SET comments = CONCAT(IFNULL(comments, ''), ?) WHERE id = ?");
        $timestamp = date('Y-m-d H:i:s') . " - ";
        $stmt->execute([$timestamp . $comment . "\n\n", $productId]);
        
        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false]);
?>