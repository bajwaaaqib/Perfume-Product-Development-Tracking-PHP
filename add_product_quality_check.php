<?php
require 'includes/auth.php';
require 'includes/db.php';

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
        $stmt = $pdo->prepare("INSERT INTO Product_quality_check (
            product_name, batch_number, brand_name, printing_company, presented_by, checked_by, status, entry_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $result = $stmt->execute([
            $product_name, $batch_number, $brand_name, $printing_company, $presented_by, $checked_by, $status, $entry_date
        ]);

        if ($result) {
            $success = 'Entry added successfully!';
            $_POST = [];
        } else {
            $error = 'Failed to add entry.';
        }
    }
}

require 'includes/header.php';
?>

<head>
<style>
body {
    background: linear-gradient(135deg, #6f42c1, #6610f2);
}
</style>
</head>

<body>
<div class="container my-5">
  <div class="card shadow-sm mx-auto" style="max-width: 700px;">
    <div class="card-header bg-primary text-white">
      <h2 class="mb-0">Add Product Quality Check</h2>
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
              <input type="text" name="product_name" id="product_name" required class="form-control" value="<?= htmlspecialchars($_POST['product_name'] ?? '') ?>">
          </div>

          <div class="col-md-6">
              <label for="batch_number" class="form-label">Batch Number</label>
              <input type="text" name="batch_number" id="batch_number" class="form-control" value="<?= htmlspecialchars($_POST['batch_number'] ?? '') ?>">
          </div>

          <div class="col-md-6">
              <label for="brand_name" class="form-label">Brand Name *</label>
              <select name="brand_name" id="brand_name" required class="form-select">
                  <option value="">-- Select Brand --</option>
                  <?php foreach ($brandOptions as $option): 
                      $sel = (isset($_POST['brand_name']) && $_POST['brand_name'] === $option) ? 'selected' : '';
                  ?>
                      <option value="<?= htmlspecialchars($option) ?>" <?= $sel ?>><?= htmlspecialchars($option) ?></option>
                  <?php endforeach; ?>
              </select>
          </div>

          <div class="col-md-6">
              <label for="printing_company" class="form-label">Printing Company</label>
              <input type="text" name="printing_company" id="printing_company" class="form-control" value="<?= htmlspecialchars($_POST['printing_company'] ?? '') ?>">
          </div>

          <div class="col-md-6">
              <label for="presented_by" class="form-label">Presented By</label>
              <input type="text" name="presented_by" id="presented_by" class="form-control" value="<?= htmlspecialchars($_POST['presented_by'] ?? '') ?>">
          </div>

          <div class="col-md-6">
              <label for="checked_by" class="form-label">Checked By</label>
              <select name="checked_by" id="checked_by" class="form-select">
                  <option value="">-- Select Checker --</option>
                  <?php foreach ($checkedByOptions as $option):
                      $sel = (isset($_POST['checked_by']) && $_POST['checked_by'] === $option) ? 'selected' : '';
                  ?>
                      <option value="<?= htmlspecialchars($option) ?>" <?= $sel ?>><?= htmlspecialchars($option) ?></option>
                  <?php endforeach; ?>
              </select>
          </div>

          <div class="col-md-6">
              <label for="status" class="form-label">Status</label>
              <select name="status" id="status" class="form-select">
                  <option value="">-- Select Status --</option>
                  <?php foreach ($statusOptions as $option):
                      $sel = (isset($_POST['status']) && $_POST['status'] === $option) ? 'selected' : '';
                  ?>
                      <option value="<?= htmlspecialchars($option) ?>" <?= $sel ?>><?= htmlspecialchars($option) ?></option>
                  <?php endforeach; ?>
              </select>
          </div>

          <div class="col-md-6">
              <label for="entry_date" class="form-label">Entry Date</label>
              <input type="date" name="entry_date" id="entry_date" class="form-control" value="<?= htmlspecialchars($_POST['entry_date'] ?? date('Y-m-d')) ?>">
          </div>

          <div class="col-12 mt-3 d-flex justify-content-between">
              <button type="submit" class="btn btn-primary">Add Entry</button>
              <a href="view_product_quality_check.php" class="btn btn-secondary">View Entries</a>
          </div>
      </form>
    </div>
  </div>
</div>
</body>
