<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/auth.php';
require 'includes/db.php'; // $pdo
require_once 'includes/fpdf.php'; // ensure only loaded once

// Get search keyword from URL
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build SQL query to search across multiple columns (name, brand, category, type)
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

// Generate PDF if requested
if (isset($_GET['download_pdf'])) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Product List', 0, 1, 'C');

    if ($search !== '') {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Search: ' . $search, 0, 1);
    }

    // Table header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(10, 10, '#', 1);
    $pdf->Cell(50, 10, 'Name', 1);
    $pdf->Cell(40, 10, 'Brand', 1);
    $pdf->Cell(40, 10, 'Category', 1);
    $pdf->Cell(30, 10, 'Type', 1);
    $pdf->Cell(20, 10, 'Status', 1);
    $pdf->Ln();

    // Table data
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

    $pdf->Output('D', 'products.pdf'); // download
    exit; // prevent further output
}

require 'includes/header.php';
?>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">
                <?php
                if ($search !== '') {
                    echo 'Search results for: <em>' . htmlspecialchars($search) . '</em>';
                } else {
                    echo 'All Products';
                }
                ?>
            </h1>
        </div>

        <div class="card-body">
            <form method="GET" class="d-flex align-items-center gap-2 flex-nowrap mb-4" style="overflow-x:auto;">
                <input 
                    type="text" 
                    name="search" 
                    value="<?= htmlspecialchars($search) ?>" 
                    class="form-control form-control-sm flex-grow-1 min-w-0" 
                    placeholder="Search products by any keyword..." 
                    aria-label="Search products"
                    style="min-width:150px;"
                >

                <button type="submit" class="btn btn-secondary btn-sm px-3 flex-shrink-0">
                    <i class="bi bi-search"></i> Search
                </button>

                <?php if ($search !== ''): ?>
                    <a href="?" class="btn btn-outline-secondary btn-sm px-3 flex-shrink-0">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                <?php endif; ?>

                <a href="add_products_brand.php" class="btn btn-secondary btn-sm px-3 flex-shrink-0 ms-auto">
                    <i class="bi bi-plus-lg"></i> Add New
                </a>

                <button type="submit" name="download_pdf" value="1" class="btn btn-danger btn-sm flex-shrink-0 ms-2">
                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                </button>
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
