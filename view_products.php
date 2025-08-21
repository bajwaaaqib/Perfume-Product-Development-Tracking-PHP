<?php
require 'includes/auth.php';
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE perfume_products SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['product_id']]);
    header("Location: view_products.php");
    exit;
}

$statuses = ['Tasks To Do', 'Pending', 'In Progress', 'Approved Internally', 'Printing Approval', 'Completion', 'Postpond', 'Done'];

$stmt = $pdo->query("
    SELECT p.*, u.email AS created_by_email
    FROM perfume_products p
    LEFT JOIN users u ON p.created_by = u.id
    ORDER BY p.created_at DESC
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfume Products Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6f42c1;
            --secondary-color: #6610f2;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #495057;
        }

        .page-header {
            border-bottom: 2px solid rgba(111, 66, 193, 0.2);
            padding-bottom: 0.75rem;
            margin-bottom: 2rem;
        }

        .page-title {
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .products-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: visible !important; /* allow dropdowns */
        }

        .table-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

.table {
    width: 100%;
    table-layout: auto;
    border-collapse: separate;   /* allow rounded corners */
    border-spacing: 0;           /* no extra gaps */
    border-radius: 12px;         /* adjust radius as you like */
    overflow: hidden;            /* keeps content inside rounded edges */
}

        .table th,
        .table td {
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
            vertical-align: middle;
            white-space: nowrap; /* no wrapping */
            overflow: visible !important; /* no hiding */
            text-overflow: unset; /* no ellipsis */
        }

        .table-hover tbody tr:hover {
            background-color: rgba(111, 66, 193, 0.05);
        }

        /* Fix: dropdowns expand fully */
        .table-responsive {
            overflow-x: auto;
            overflow-y: visible !important;
        }

        .status-select, .action-select {
            min-width: 140px;
            border-radius: 8px;
            padding: 0.4rem 0.75rem;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
            z-index: 20; /* ensure above table */
            background-color: #fff;
            white-space: nowrap;
        }

        .status-select:focus, .action-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.15);
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

        @media (max-width: 992px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>
<body>
<div class="container my-4 fade-in">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="page-title">
            <i class="fas fa-wine-bottle me-2"></i>Perfume Products
        </h1>
        <a href="add_product.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Product
        </a>
    </div>

    <div class="card products-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-header">
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
                        <?php foreach ($products as $product): 
                            $statusClass = strtolower(str_replace(' ', '-', $product['status']));
                        ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= htmlspecialchars($product['product_name']) ?></td>
                                <td><?= htmlspecialchars($product['brand_name']) ?></td>
                                <td><?= htmlspecialchars($product['batch_no']) ?></td>
                                <td><?= htmlspecialchars($product['created_by_email']) ?></td>
                                <td><?= date('d M Y', strtotime($product['created_at'])) ?></td>
                                <td>
                                    <form method="POST" class="m-0">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <select name="status" onchange="this.form.submit()" 
                                                class="status-select <?= 'status-' . $statusClass ?>">
                                            <?php foreach ($statuses as $status): ?>
                                                <option value="<?= $status ?>" 
                                                    <?= ($product['status'] === $status) ? 'selected' : '' ?>>
                                                    <?= $status ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <select class="action-select" onchange="handleAction(this, <?= $product['id'] ?>)">
                                        <option value="" selected disabled>Actions</option>
                                        <option value="edit">Edit</option>
                                        <option value="delete">Delete</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                    <h4>No products found</h4>
                                    <p class="text-muted">Add your first perfume product to get started</p>
                                    <a href="add_product.php" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus me-2"></i>Add Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function handleAction(select, productId) {
    const action = select.value;
    if (action === "edit") {
        window.location.href = `edit_product.php?id=${productId}`;
    } else if (action === "delete") {
        if (confirm('Are you sure you want to delete this product?')) {
            window.location.href = `delete_product.php?id=${productId}`;
        } else {
            select.value = "";
        }
    }
}
</script>

<?php require 'includes/footer.php'; ?>
</body>
</html>
