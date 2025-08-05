<?php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($product_id && $status) {
        $stmt = $pdo->prepare("UPDATE perfume_products SET status = ? WHERE id = ?");
        $stmt->execute([$status, $product_id]);
    }
}

header('Location: dashboard.php');
exit;
