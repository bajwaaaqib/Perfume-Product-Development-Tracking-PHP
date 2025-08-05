<?php
require 'includes/auth.php';
require 'includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: view_products.php');
    exit;
}

$id = (int)$_GET['id'];

// Fetch product data
$stmt = $pdo->prepare("SELECT * FROM perfume_products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: view_products.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and get POST data with null coalescing operator
    $product_name = $_POST['product_name'] ?? '';
    $brand_name = $_POST['brand_name'] ?? '';
    $batch_no = $_POST['batch_no'] ?? '';
    $budget = $_POST['budget'] ?? '';
    $fragrance_type = $_POST['fragrance_type'] ?? '';
    $target_audience = $_POST['target_audience'] ?? '';
    $design_style = $_POST['design_style'] ?? '';
    $box_packaging_type = $_POST['box_packaging_type'] ?? '';
    $bottle_coating = $_POST['bottle_coating'] ?? '';
    $box_finishing = $_POST['box_finishing'] ?? '';
    $color = $_POST['color'] ?? '';
    $size = $_POST['size'] ?? '';

    if (!$product_name || !$brand_name) {
        $error = 'Product Name and Brand Name are required.';
    } else {
        $stmt = $pdo->prepare("UPDATE perfume_products SET
            product_name = ?, brand_name = ?, batch_no = ?, budget = ?, fragrance_type = ?,
            target_audience = ?, design_style = ?, box_packaging_type = ?, bottle_coating = ?, box_finishing = ?,
            color = ?, size = ?
            WHERE id = ?");

        $updated = $stmt->execute([
            $product_name, $brand_name, $batch_no, $budget, $fragrance_type,
            $target_audience, $design_style, $box_packaging_type, $bottle_coating, $box_finishing,
            $color, $size,
            $id
        ]);

        if ($updated) {
            $success = 'Product details updated successfully.';
            // Reload product data after update
            $stmt = $pdo->prepare("SELECT * FROM perfume_products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
        } else {
            $error = 'Failed to update product details.';
        }
    }
}

require 'includes/header.php';
?>

<div class="container">
    <h2>Perfume Product</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label for="product_name" class="form-label">Product Name *</label>
            <input type="text" name="product_name" id="product_name" class="form-control" required
                   value="<?= htmlspecialchars($product['product_name']) ?>">
        </div>

        <div class="col-md-6">
            <label for="brand_name" class="form-label">Brand Name *</label>
            <input type="text" name="brand_name" id="brand_name" class="form-control" required
                   value="<?= htmlspecialchars($product['brand_name']) ?>">
        </div>

        <div class="col-md-4">
            <label for="batch_no" class="form-label">Batch No.</label>
            <input type="text" name="batch_no" id="batch_no" class="form-control"
                   value="<?= htmlspecialchars($product['batch_no']) ?>">
        </div>

        <div class="col-md-4">
            <label for="budget" class="form-label">Budget</label>
            <input type="text" name="budget" id="budget" class="form-control"
                   value="<?= htmlspecialchars($product['budget']) ?>">
        </div>

        <div class="col-md-4">
            <label for="fragrance_type" class="form-label">Fragrance Type</label>
            <input type="text" name="fragrance_type" id="fragrance_type" class="form-control"
                   value="<?= htmlspecialchars($product['fragrance_type']) ?>">
        </div>

        <div class="col-md-4">
            <label for="target_audience" class="form-label">Target Audience</label>
            <input type="text" name="target_audience" id="target_audience" class="form-control"
                   value="<?= htmlspecialchars($product['target_audience']) ?>">
        </div>

        <div class="col-md-4">
            <label for="design_style" class="form-label">Design Style Preference</label>
            <input type="text" name="design_style" id="design_style" class="form-control"
                   value="<?= htmlspecialchars($product['design_style']) ?>">
        </div>

        <div class="col-md-4">
            <label for="box_packaging_type" class="form-label">Box Packaging Type</label>
            <input type="text" name="box_packaging_type" id="box_packaging_type" class="form-control"
                   value="<?= htmlspecialchars($product['box_packaging_type']) ?>">
        </div>

        <div class="col-md-4">
            <label for="bottle_coating" class="form-label">Bottle Coating</label>
            <input type="text" name="bottle_coating" id="bottle_coating" class="form-control"
                   value="<?= htmlspecialchars($product['bottle_coating']) ?>">
        </div>

        <div class="col-md-4">
            <label for="box_finishing" class="form-label">Box Finishing</label>
            <input type="text" name="box_finishing" id="box_finishing" class="form-control"
                   value="<?= htmlspecialchars($product['box_finishing']) ?>">
        </div>

        <!-- New fields: color and size -->
        <div class="col-md-6">
            <label for="color" class="form-label">Color</label>
            <input type="text" name="color" id="color" class="form-control"
                   value="<?= htmlspecialchars($product['color']) ?>">
        </div>

        <div class="col-md-6">
            <label for="size" class="form-label">Size</label>
            <input type="text" name="size" id="size" class="form-control"
                   value="<?= htmlspecialchars($product['size']) ?>">
        </div>

        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="view_products.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require 'includes/footer.php'; ?>
