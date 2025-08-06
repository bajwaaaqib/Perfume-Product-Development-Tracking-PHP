<?php
require 'includes/auth.php';
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE perfume_products SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['product_id']]);
    header("Location: view_products.php");
    exit;
}

$statuses = ['Tasks To Do', 'Pending', 'In Progress', 'Approved Internally', 'Printing Approval', 'Completion'];

$stmt = $pdo->query("
    SELECT p.*, u.email AS created_by_email
    FROM perfume_products p
    LEFT JOIN users u ON p.created_by = u.id
    ORDER BY p.created_at DESC
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'includes/header.php';
?>
<head>
    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

<div class="container my-5">
    <h2 class="mb-4 text-center text-md-start fw-bold text-primary">All Perfume Products</h2>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Brand</th>
                        <th>Batch No</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($products) > 0): ?>
                        <?php $counter = 1; ?>
                        <?php foreach ($products as $product): ?>
                            <tr class="text-center">
                                <td><?= $counter++ ?></td>
                                <td class="text-start"><?= htmlspecialchars($product['product_name']) ?></td>
                                <td><?= htmlspecialchars($product['brand_name']) ?></td>
                                <td><?= htmlspecialchars($product['batch_no']) ?></td>
                                <td><?= htmlspecialchars($product['created_by_email']) ?></td>
                                <td><?= date('d M Y', strtotime($product['created_at'])) ?></td>
                                <td style="min-width: 150px;">
                                    <form method="POST" style="margin: 0;">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                            <?php foreach ($statuses as $status): ?>
                                                <option value="<?= $status ?>" <?= ($product['status'] === $status) ? 'selected' : '' ?>>
                                                    <?= $status ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </td>
                                <td style="min-width: 130px;">
                                    <select class="form-select form-select-sm" onchange="handleAction(this, <?= $product['id'] ?>)">
                                        <option value="" selected disabled>Choose action</option>
                                        <option value="edit">View/Edit</option>
                                        <option value="delete">Delete</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4 fs-5">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>

<script>
function handleAction(select, productId) {
    const action = select.value;
    if (action === "edit") {
        window.location.href = `edit_product.php?id=${productId}`;
    } else if (action === "delete") {
        window.location.href = `delete_product.php?id=${productId}`;
    }
}
</script>
