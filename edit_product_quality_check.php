<?php
require 'includes/auth.php';
require 'includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die('Invalid ID.');
}

// Fetch existing record
$stmt = $pdo->prepare("SELECT * FROM Product_quality_check WHERE id = ?");
$stmt->execute([$id]);
$entry = $stmt->fetch();

if (!$entry) {
    die('Entry not found.');
}

$success = '';
$error = '';

$brandOptions = ['ARD PERFUMES', 'MARCO LUCIO', 'SHANGANI', 'AL FATEH'];
$checkedByOptions = ['Aaqib', 'CEO', 'Aziz'];
$statusOptions = ['Pending', 'Approved', 'Rejected', 'In Progress'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'] ?? '';
    $batch_number = $_POST['batch_number'] ?? '';
    $brand_name = $_POST['brand_name'] ?? '';
    $printing_company = $_POST['printing_company'] ?? '';
    $presented_by = $_POST['presented_by'] ?? '';
    $checked_by = $_POST['checked_by'] ?? '';
    $status = $_POST['status'] ?? '';
    $entry_date = $_POST['entry_date'] ?? date('Y-m-d');

    if (!$product_name || !$brand_name) {
        $error = 'Product Name and Brand Name are required.';
    } else {
        $updateStmt = $pdo->prepare("UPDATE Product_quality_check SET 
            product_name = ?, 
            batch_number = ?, 
            brand_name = ?, 
            printing_company = ?, 
            presented_by = ?, 
            checked_by = ?, 
            status = ?, 
            entry_date = ?
            WHERE id = ?");

        $result = $updateStmt->execute([
            $product_name, $batch_number, $brand_name, $printing_company, $presented_by,
            $checked_by, $status, $entry_date, $id
        ]);

        if ($result) {
            $success = 'Entry updated successfully!';
            // Refresh data
            $stmt->execute([$id]);
            $entry = $stmt->fetch();
        } else {
            $error = 'Failed to update entry.';
        }
    }
}

require 'includes/header.php';
?>

<div class="container my-5">
    <div class="card mx-auto" style="max-width: 700px;">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Product Quality Check</h4>
        </div>
        <div class="card-body">
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label for="product_name" class="form-label">Product Name *</label>
                    <input type="text" name="product_name" id="product_name" required class="form-control" value="<?= htmlspecialchars($entry['product_name']) ?>">
                </div>

                <div class="col-md-6">
                    <label for="batch_number" class="form-label">Batch Number</label>
                    <input type="text" name="batch_number" id="batch_number" class="form-control" value="<?= htmlspecialchars($entry['batch_number']) ?>">
                </div>

                <div class="col-md-6">
                    <label for="brand_name" class="form-label">Brand Name *</label>
                    <select name="brand_name" id="brand_name" required class="form-select">
                        <?php foreach ($brandOptions as $option): ?>
                            <option value="<?= $option ?>" <?= $entry['brand_name'] === $option ? 'selected' : '' ?>><?= $option ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="printing_company" class="form-label">Printing Company</label>
                    <input type="text" name="printing_company" id="printing_company" class="form-control" value="<?= htmlspecialchars($entry['printing_company']) ?>">
                </div>

                <div class="col-md-6">
                    <label for="presented_by" class="form-label">Presented By</label>
                    <input type="text" name="presented_by" id="presented_by" class="form-control" value="<?= htmlspecialchars($entry['presented_by']) ?>">
                </div>

                <div class="col-md-6">
                    <label for="checked_by" class="form-label">Checked By</label>
                    <select name="checked_by" id="checked_by" class="form-select">
                        <option value="">-- Select --</option>
                        <?php foreach ($checkedByOptions as $option): ?>
                            <option value="<?= $option ?>" <?= $entry['checked_by'] === $option ? 'selected' : '' ?>><?= $option ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <?php foreach ($statusOptions as $option): ?>
                            <option value="<?= $option ?>" <?= $entry['status'] === $option ? 'selected' : '' ?>><?= $option ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="entry_date" class="form-label">Entry Date</label>
                    <input type="date" name="entry_date" id="entry_date" class="form-control" value="<?= htmlspecialchars($entry['entry_date']) ?>">
                </div>

                <div class="col-12 d-flex justify-content-between mt-3">
                    <button type="submit" class="btn btn-warning">Update Entry</button>
                    <a href="view_product_quality_check.php" class="btn btn-secondary">Back to List</a>
                </div>
            </form>
        </div>
    </div>
</div>
