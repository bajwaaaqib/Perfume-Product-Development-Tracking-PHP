<?php
// Developed by Aaqib Bajwa | www.aaqibbajwa.com | Email: info@aaqibbajwa.com
require 'includes/auth.php';
require 'includes/db.php';

// Fetch newest entries first
$stmt = $pdo->query("SELECT * FROM Product_quality_check ORDER BY entry_date DESC");
$entries = $stmt->fetchAll();

require 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-3 text-center text-md-start">Product Quality Check Entries</h2>

    <div class="d-flex justify-content-center justify-content-md-start">
        <a href="add_product_quality_check.php" class="btn btn-secondary mb-3">Add New Entry</a>
    </div>

    <?php if (count($entries) === 0): ?>
        <p class="text-center">No entries found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-nowrap">
                <thead class="table-light">
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
                            <td><?= htmlspecialchars($entry['status']) ?></td>
                            <td><?= htmlspecialchars(date('d-m-Y', strtotime($entry['entry_date']))) ?></td>
                            <td>
                                <a href="edit_product_quality_check.php?id=<?= $entry['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
