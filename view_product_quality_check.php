<?php
require 'includes/auth.php';
require 'includes/db.php';

// Fetch newest entries first
$stmt = $pdo->query("SELECT * FROM Product_quality_check ORDER BY entry_date DESC");
$entries = $stmt->fetchAll();

require 'includes/header.php';
?>

<div class="container my-5">
    <h2>Product Quality Check Entries</h2>
    <a href="add_product_quality_check.php" class="btn btn-success mb-3">Add New Entry</a>

    <?php if (count($entries) === 0): ?>
        <p>No entries found.</p>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th> <!-- Serial Number -->
                    <th>Product Name</th>
                    <th>Brand Name</th>
                    <th>Printing Company</th>
                    <th>Presented By</th>
                    <th>Checked By</th>
                    <th>Status</th>
                    <th>Entry Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $serial = 1; ?>
                <?php foreach ($entries as $entry): ?>
                <tr>
                    <td><?= $serial++ ?></td>
                    <td><?= htmlspecialchars($entry['product_name']) ?></td>
                    <td><?= htmlspecialchars($entry['brand_name']) ?></td>
                    <td><?= htmlspecialchars($entry['printing_company']) ?></td>
                    <td><?= htmlspecialchars($entry['presented_by']) ?></td>
                    <td><?= htmlspecialchars($entry['checked_by']) ?></td>
                    <td><?= htmlspecialchars($entry['status']) ?></td>
                    <td><?= htmlspecialchars(date('d-m-Y', strtotime($entry['entry_date']))) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
