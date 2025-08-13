<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/auth.php';
require 'includes/db.php'; // $pdo
require_once 'includes/header.php';

// Determine filter if clicked
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// 1. Get Perfume Products counts
$stmt_completed = $pdo->prepare("SELECT COUNT(*) as total FROM perfume_products WHERE status = :status");
$stmt_completed->execute(['status' => 'Completion']);
$res_completed = $stmt_completed->fetch(PDO::FETCH_ASSOC)['total'];

$inprogress_statuses = ['Tasks To Do', 'Pending', 'In Progress', 'Approved Internally', 'Printing Approval'];
$placeholders = implode(',', array_fill(0, count($inprogress_statuses), '?'));
$stmt_inprogress = $pdo->prepare("SELECT COUNT(*) as total FROM perfume_products WHERE status IN ($placeholders)");
$stmt_inprogress->execute($inprogress_statuses);
$res_inprogress = $stmt_inprogress->fetch(PDO::FETCH_ASSOC)['total'];

// 2. Products continued/discontinued counts
$stmt_continued = $pdo->query("SELECT COUNT(*) as total FROM products WHERE status = 1");
$res_continued = $stmt_continued->fetch(PDO::FETCH_ASSOC)['total'];

$stmt_discontinued = $pdo->query("SELECT COUNT(*) as total FROM products WHERE status = 0");
$res_discontinued = $stmt_discontinued->fetch(PDO::FETCH_ASSOC)['total'];

// Fetch products if a filter is applied
$products_list = [];
if ($filter !== '') {
    switch ($filter) {
        case 'perfume_completed':
            $stmt = $pdo->prepare("SELECT * FROM perfume_products WHERE status = ?");
            $stmt->execute(['Completion']);
            $products_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'perfume_inprogress':
            $stmt = $pdo->prepare("SELECT * FROM perfume_products WHERE status IN ($placeholders)");
            $stmt->execute($inprogress_statuses);
            $products_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'products_continued':
            $stmt = $pdo->prepare("SELECT id, name, brand, category, type, status FROM products WHERE status = ?");
            $stmt->execute([1]);
            $products_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'products_discontinued':
            $stmt = $pdo->prepare("SELECT id, name, brand, category, type, status FROM products WHERE status = ?");
            $stmt->execute([0]);
            $products_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
    }
}
?>
<style>
body {
    background: linear-gradient(135deg, #6f42c1, #6610f2);
}
</style>
<body>
<div class="container my-5">
    <div class="row g-3 mb-4">
        <!-- Perfume Products -->
        <div class="col-md-3">
            <a href="?filter=perfume_completed" class="text-decoration-none">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h5>Completed Products</h5>
                        <h2><?= $res_completed ?></h2>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="?filter=perfume_inprogress" class="text-decoration-none">
                <div class="card text-center bg-warning text-dark">
                    <div class="card-body">
                        <h5>In Progress Products</h5>
                        <h2><?= $res_inprogress ?></h2>
                    </div>
                </div>
            </a>
        </div>

        <!-- Products -->
        <div class="col-md-3">
            <a href="?filter=products_continued" class="text-decoration-none">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h5>Products Continued</h5>
                        <h2><?= $res_continued ?></h2>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="?filter=products_discontinued" class="text-decoration-none">
                <div class="card text-center bg-danger text-white">
                    <div class="card-body">
                        <h5>Products Discontinued</h5>
                        <h2><?= $res_discontinued ?></h2>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Graph -->
    <div class="card mt-4 mb-4">
        <div class="card-header">
            <h5 class="mb-0">Product Status Graph</h5>
        </div>
        <div class="card-body">
            <canvas id="statusChart" height="100"></canvas>
        </div>
    </div>

    <!-- Product List Table -->
    <?php if (!empty($products_list)): ?>
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Products Details</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <?php if (strpos($filter, 'perfume') !== false): ?>
                            <th>Product Name</th>
                            <th>Brand</th>
                            <th>Status</th>
                            <th>Batch No</th>
                        <?php else: ?>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Status</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products_list as $i => $p): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <?php if (strpos($filter, 'perfume') !== false): ?>
                                <td><?= htmlspecialchars($p['product_name']) ?></td>
                                <td><?= htmlspecialchars($p['brand_name']) ?></td>
                                <td><?= htmlspecialchars($p['status']) ?></td>
                                <td><?= htmlspecialchars($p['batch_no']) ?></td>
                            <?php else: ?>
                                <td><?= htmlspecialchars($p['name']) ?></td>
                                <td><?= htmlspecialchars($p['brand']) ?></td>
                                <td><?= htmlspecialchars($p['category']) ?></td>
                                <td><?= htmlspecialchars($p['type']) ?></td>
                                <td><?= $p['status'] ? '✔' : '✗' ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Perfume Completed', 'Perfume In Progress', 'Products Continued', 'Products Discontinued'],
        datasets: [{
            label: 'Number of Products',
            data: [<?= $res_completed ?>, <?= $res_inprogress ?>, <?= $res_continued ?>, <?= $res_discontinued ?>],
            backgroundColor: ['#28a745', '#ffc107', '#007bff', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>

<?php require 'includes/footer.php'; ?>
</body>
