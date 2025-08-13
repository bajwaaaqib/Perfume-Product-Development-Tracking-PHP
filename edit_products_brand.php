<?php
// Show errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/auth.php';
require 'includes/db.php'; // This defines $pdo
require 'includes/header.php';

// Initialize message variable
$message = "";

// Check if id is provided for editing
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="alert alert-danger">❌ Invalid Product ID.</div>';
    require 'includes/footer.php';
    exit;
}

$id = (int)$_GET['id'];

// Fetch existing product data
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo '<div class="alert alert-danger">❌ Product not found.</div>';
    require 'includes/footer.php';
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $category = trim($_POST['category']);
    $type = trim($_POST['type']);
    $status = ($_POST['status'] === "true") ? 1 : 0;

    if (!empty($name) && !empty($brand)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE products 
                SET name = ?, brand = ?, category = ?, type = ?, status = ? 
                WHERE id = ?
            ");
            $stmt->execute([$name, $brand, $category, $type, $status, $id]);
            $message = '<div class="alert alert-success">✅ Product updated successfully!</div>';

            // Refresh product data after update
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

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
           <h3 class="mb-0">Edit Perfume Product</h3>
        </div>
        <?php echo $message; ?>
    <div class="card-body">
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Brand</label>
                <select name="brand" class="form-select" required>
                    <option value="">-- Select Brand --</option>
                    <?php
                    $brands = ["ARD PERFUMES", "MARCO LUCIO", "SHANGANI", "AL FATEH"];
                    foreach ($brands as $b) {
                        $selected = ($product['brand'] === $b) ? 'selected' : '';
                        echo "<option value=\"$b\" $selected>$b</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">-- Select Category --</option>
                    <?php
                    $categories = ["EDP 20ML","EDP 50ML","EDP 100ML","OIL 6ML","OIL 7ML","OIL 24ML","OIL 50ML","OIL 100ML","OIL 250ML","OIL 500ML","250ML AF","300ML AF","380ML AF","400ML AF","Attar","Lotion"];
                    foreach ($categories as $c) {
                        $selected = ($product['category'] === $c) ? 'selected' : '';
                        echo "<option value=\"$c\" $selected>$c</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">-- Select Type --</option>
                    <?php
                    $types = ["Box/Bottle","Label","Bottle/CAN"];
                    foreach ($types as $t) {
                        $selected = ($product['type'] === $t) ? 'selected' : '';
                        echo "<option value=\"$t\" $selected>$t</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="true" <?= $product['status'] ? 'selected' : '' ?>>✔ Continue</option>
                    <option value="false" <?= !$product['status'] ? 'selected' : '' ?>>✗ Discontinue</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="products.php" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
  </div>
</div>
</body>
