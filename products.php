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
/* Mobile adjustments: search in first row, buttons in second row */
@media (max-width: 576px) {
    .search-input-row {
        flex: 1 1 100%;
    }
    .button-row {
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    .button-row .btn {
        flex: 1 1 48%; /* Two buttons per line, adjust as needed */
        min-width: 120px;
    }
}
</style>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">
                <?= $search !== '' ? 'Search results for: <em>' . htmlspecialchars($search) . '</em>' : 'All Products' ?>
            </h1>
        </div>

        <div class="card-body">
            <!-- Search and Buttons -->
            <form method="GET" class="d-flex flex-column flex-sm-row gap-2 mb-4">
                <div class="search-input-row d-flex">
                    <input 
                        type="text" 
                        name="search" 
                        value="<?= htmlspecialchars($search) ?>" 
                        class="form-control form-control-sm w-100" 
                        placeholder="Search by any keyword"
                    >
                </div>

                <div class="button-row d-flex gap-2 flex-wrap mt-2 mt-sm-0">
                    <button type="submit" class="btn btn-secondary btn-sm">
                        <i class="bi bi-search"></i> Search
                    </button>

                    <?php if ($search !== ''): ?>
                        <a href="?" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    <?php endif; ?>

                    <a href="add_products_brand.php" class="btn btn-secondary btn-sm ms-auto">
                        <i class="bi bi-plus-lg"></i> Add New
                    </a>

                    <button type="submit" name="download_pdf" value="1" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf"></i> Download PDF
                    </button>
                </div>
            </form>

            <?php if (count($products) === 0): ?>
                <p class="text-center fs-5 text-muted">No products found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $serial = 1; ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <th><?= $serial++ ?></th>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= htmlspecialchars($product['brand']) ?></td>
                                    <td><?= htmlspecialchars($product['category']) ?></td>
                                    <td><?= htmlspecialchars($product['type']) ?></td>
                                    <td class="text-center">
                                        <?= $product['status'] ? '<span class="text-success fs-4">&#10003;</span>' : '<span class="text-danger fs-4">&#10007;</span>' ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit_products_brand.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
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
