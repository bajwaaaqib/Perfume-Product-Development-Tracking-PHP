<?php
// Developed by Aaqib Bajwa | www.aaqibbajwa.com | Email: info@aaqibbajwa.com
require 'includes/auth.php';
require 'includes/db.php';

// Get all entries ordered by latest entry first
$stmt = $pdo->query("SELECT * FROM printing_approval ORDER BY entry_date DESC");
$entries = $stmt->fetchAll();

require 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-3 text-center text-md-start">Printing Approval Entries</h2>
    
    <!-- Mobile/desktop aligned button -->
    <div class="d-flex justify-content-center justify-content-md-start">
        <a href="add_printing_approval.php" class="btn btn-secondary mb-3">Add New Entry</a>
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
                        <th>Brand</th>
                        <th>Printing Co.</th>
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
                            <td><?= htmlspecialchars($entry['checked_by']) ?></td>
                            <td><?= htmlspecialchars($entry['status']) ?></td>
                            <td><?= htmlspecialchars(date('d-m-Y', strtotime($entry['entry_date']))) ?></td>
                            <td><a href="edit_printing_approval.php?id=<?= $entry['id'] ?>" class="btn btn-sm btn-secondary">Edit</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
