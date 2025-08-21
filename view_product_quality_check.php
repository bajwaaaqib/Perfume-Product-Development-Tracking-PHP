<?php
// Developed by Aaqib Bajwa | www.aaqibbajwa.com | Email: info@aaqibbajwa.com
require 'includes/auth.php';
require 'includes/db.php';

// Fetch newest entries first
$stmt = $pdo->query("SELECT * FROM Product_quality_check ORDER BY entry_date DESC");
$entries = $stmt->fetchAll();

require 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Quality Check Entries</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #6f42c1;
            --secondary-color: #6610f2;
            --light-bg: #f8f9fa;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-header {
            border-bottom: 2px solid rgba(111, 66, 193, 0.15);
            padding-bottom: 0.75rem;
            margin-bottom: 2rem;
        }

        .page-title {
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .entries-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: visible !important;
        }

        .table-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
        }

        .table th, .table td {
            white-space: nowrap;
            padding: 0.9rem 1rem;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(111, 66, 193, 0.05);
        }

        .empty-state {
            padding: 3rem;
            text-align: center;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }

        .btn-action {
            border-radius: 8px;
            padding: 0.35rem 0.75rem;
        }
    </style>
</head>
<body>
<div class="container my-4">
     <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-center">
        <h1 class="page-title">
            <i class="fas fa-clipboard-check me-2"></i>Product Quality Check Entries
        </h1>
        <a href="add_product_quality_check.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Entery
        </a>
    </div>

    <div class="card entries-card">
        <div class="table-responsive">
            <?php if (count($entries) === 0): ?>
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h4>No entries found</h4>
                    <p class="text-muted">Start by adding your first quality check entry</p>
                    <a href="add_product_quality_check.php" class="btn btn-primary mt-2">
                        <i class="fas fa-plus me-2"></i>Add Entry
                    </a>
                </div>
            <?php else: ?>
                <table class="table table-hover mb-0">
                    <thead class="table-header">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Batch No.</th>
                            <th>Brand</th>
                            <th>Printing Co.</th>
                            <th>Presented By</th>
                            <th>Checked By</th>
                            <th>Status</th>
                            <th>Entry Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $serial = 1; ?>
                        <?php foreach ($entries as $entry): ?>
                            <tr>
                                <td><?= $serial++ ?></td>
                                <td><?= htmlspecialchars($entry['product_name']) ?></td>
                                <td><?= htmlspecialchars($entry['batch_number']) ?></td>
                                <td><?= htmlspecialchars($entry['brand_name']) ?></td>
                                <td><?= htmlspecialchars($entry['printing_company']) ?></td>
                                <td><?= htmlspecialchars($entry['presented_by']) ?></td>
                                <td><?= htmlspecialchars($entry['checked_by']) ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($entry['status']) ?></span>
                                </td>
                                <td><?= htmlspecialchars(date('d M Y', strtotime($entry['entry_date']))) ?></td>
                                <td>
                                    <a href="edit_product_quality_check.php?id=<?= $entry['id'] ?>" 
                                       class="btn btn-sm btn-secondary btn-action">
                                       <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
</body>
</html>
