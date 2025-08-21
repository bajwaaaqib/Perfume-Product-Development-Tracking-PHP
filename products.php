<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/auth.php';
require 'includes/db.php'; // $pdo
require_once 'includes/fpdf.php'; // ensure only loaded once

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT * FROM products WHERE 1=1 ";
$params = [];

if ($search !== '') {
    $sql .= " AND (
        name LIKE ? OR
        brand LIKE ? OR
        category LIKE ? OR
        type LIKE ?
    ) ";
    for ($i = 0; $i < 4; $i++) {
        $params[] = "%$search%";
    }
}

$sql .= " ORDER BY brand, name";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['download_pdf'])) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Product List', 0, 1, 'C');

    if ($search !== '') {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Search: ' . $search, 0, 1);
    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(10, 10, '#', 1);
    $pdf->Cell(50, 10, 'Name', 1);
    $pdf->Cell(40, 10, 'Brand', 1);
    $pdf->Cell(40, 10, 'Category', 1);
    $pdf->Cell(30, 10, 'Type', 1);
    $pdf->Cell(20, 10, 'Status', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $serial = 1;
    foreach ($products as $p) {
        $pdf->Cell(10, 10, $serial++, 1);
        $pdf->Cell(50, 10, $p['name'], 1);
        $pdf->Cell(40, 10, $p['brand'], 1);
        $pdf->Cell(40, 10, $p['category'], 1);
        $pdf->Cell(30, 10, $p['type'], 1);
        $pdf->Cell(20, 10, $p['status'] ? 'OK' : 'Quit', 1);
        $pdf->Ln();
    }

    $pdf->Output('D', 'products.pdf');
    exit;
}

require 'includes/header.php';
?>

<style>
:root {
    --primary-color: #6f42c1;
    --primary-light: #8a63d2;
    --secondary-color: #6610f2;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --light-gray: #f8f9fa;
    --dark-gray: #343a40;
}

body {
    background-color: var(--light-gray);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card-products {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.card-products-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 1rem 1.5rem;
    font-weight: 600;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.card-products-body {
    padding: 1.5rem;
}

.form-control, .form-select {
    border-radius: 8px;
    padding: 0.6rem 1rem;
    border: 1px solid #e0e0e0;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(111,66,193,0.15);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #5a6268, #6c757d);
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.6rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
}

.status-ok { background-color: rgba(40,167,69,0.15); color: var(--success-color); }
.status-quit { background-color: rgba(220,53,69,0.15); color: var(--danger-color); }

/* Search/buttons row */
form.d-flex {
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
}

form.d-flex input {
    min-width: 200px;
    flex-grow: 1;
}

@media (max-width: 576px) {
    form.d-flex {
        flex-direction: column;
        align-items: stretch;
    }
    form.d-flex .btn,
    form.d-flex a {
        width: 100%;
    }
}

/* Table responsive without wrapping */
.table-responsive {
    overflow-x: auto;
}

.table-responsive table {
    white-space: nowrap;
}

.table-responsive th,
.table-responsive td {
    white-space: nowrap;
}
</style>

<div class="container my-5">
    <div class="card card-products">
        <div class="card-products-header">
            <i class="fas fa-box-open"></i>
            <?= $search !== '' ? 'Search results for: ' . htmlspecialchars($search) : 'All Products' ?>
        </div>
        <div class="card-products-body">
            <!-- Search + Buttons -->
            <form method="GET" class="d-flex gap-2 mb-3">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search by any keyword">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Search</button>
                <?php if ($search !== ''): ?>
                    <a href="?" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
                <?php endif; ?>
                <a href="add_products_brand.php" class="btn btn-secondary"><i class="bi bi-plus-lg"></i> Add New</a>
                <button type="submit" name="download_pdf" value="1" class="btn btn-danger"><i class="bi bi-file-earmark-pdf"></i> PDF</button>
            </form>

            <?php if (count($products) === 0): ?>
                <p class="text-center text-muted">No products found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $serial = 1; foreach($products as $product): 
                                $statusClass = $product['status'] ? 'status-ok' : 'status-quit';
                                $statusText = $product['status'] ? 'OK' : 'Quit';
                            ?>
                                <tr>
                                    <td><?= $serial++ ?></td>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= htmlspecialchars($product['brand']) ?></td>
                                    <td><?= htmlspecialchars($product['category']) ?></td>
                                    <td><?= htmlspecialchars($product['type']) ?></td>
                                    <td><span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                                    <td>
                                        <a href="edit_products_brand.php?id=<?= $product['id'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
