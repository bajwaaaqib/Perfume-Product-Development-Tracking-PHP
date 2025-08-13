<?php
// Show errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
require 'includes/auth.php';
require 'includes/db.php'; // This defines $pdo
require 'includes/header.php';

// Initialize message variable
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $category = trim($_POST['category']);
    $type = trim($_POST['type']);
    $status = ($_POST['status'] === "true") ? 1 : 0; // Convert to 1/0

    if (!empty($name) && !empty($brand)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO products (name, brand, category, type, status)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $brand, $category, $type, $status]);
            $message = '<div class="alert alert-success">✅ Product added successfully!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        $message = '<div class="alert alert-warning">⚠ Name and Brand are required.</div>';
    }
}
?>

<head>
<style>
body {
    background: linear-gradient(135deg, #6f42c1, #6610f2);
}
</style>
</head>
<body>
<div class="container my-5">
  <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-header bg-primary text-white">
           <h3 class="mb-0">Add New Perfume Product</h3>
        </div>
        <?php echo $message; ?>
    <div class="card-body">
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Brand</label>
                <select name="brand" class="form-select" required>
                    <option value="">-- Select Brand --</option>
                    <option value="ARD PERFUMES">ARD PERFUMES</option>
                    <option value="MARCO LUCIO">MARCO LUCIO</option>
                    <option value="SHANGANI">SHANGANI</option>
                    <option value="AL FATEH">AL FATEH</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">-- Select Category --</option>
                    <option value="EDP 20ML">EDP 20ML</option>
                    <option value="EDP 50ML">EDP 50ML</option>
                    <option value="EDP 100ML">EDP 100ML</option>
                    <option value="OIL 6ML">OIL 6ML</option>
                    <option value="OIL 7ML">OIL 7ML</option>
                    <option value="OIL 24ML">OIL 24ML</option>
                    <option value="OIL 50ML">OIL 50ML</option>
                    <option value="OIL 100ML">OIL 100ML</option>
                    <option value="OIL 250ML">OIL 250ML</option>
                    <option value="OIL 500ML">OIL 500ML</option>
                    <option value="250ML AF">250ML AF</option>
                    <option value="300ML AF">300ML AF</option>
                    <option value="380ML AF">380ML AF</option>
                    <option value="400ML AF">400ML AF</option>
                    <option value="Attar">Attar</option>
                    <option value="Lotion">Lotion</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">-- Select Type --</option>
                    <option value="Box/Bottle">Box/Bottle</option>
                    <option value="Label">Label</option>
                    <option value="Bottle/CAN">Bottle/CAN</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="true">✔ Continue</option>
                    <option value="false">✗ Discontinue</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="products.php" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Add Product</button>
            </div>
        </form>
    </div>
  </div>
</div>
</body>
