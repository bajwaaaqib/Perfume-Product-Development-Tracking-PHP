<?php
require_once 'includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($product_id && $status) {
        $stmt = $pdo->prepare("UPDATE perfume_products SET status = ? WHERE id = ?");
        if ($stmt->execute([$status, $product_id])) {
            echo json_encode(['success' => true]);
            exit;
        }
        echo json_encode(['success' => false, 'error' => 'Database update failed']);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
exit;
