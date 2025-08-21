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
    $product_name       = $_POST['product_name'] ?? '';
    $brand_name         = $_POST['brand_name'] ?? '';
    $batch_no           = $_POST['batch_no'] ?? '';
    $budget             = $_POST['budget'] ?? '';
    $fragrance_type     = $_POST['fragrance_type'] ?? '';
    $target_audience    = $_POST['target_audience'] ?? '';
    $design_style       = $_POST['design_style'] ?? '';
    $box_packaging_type = $_POST['box_packaging_type'] ?? '';
    $bottle_coating     = $_POST['bottle_coating'] ?? '';
    $box_finishing      = $_POST['box_finishing'] ?? '';
    $color              = $_POST['color'] ?? '';
    $size               = $_POST['size'] ?? '';
    $product_type       = $_POST['product_type'] ?? '';

    if (!$product_name || !$brand_name) {
        $error = 'Product Name and Brand Name are required.';
    } else {
        $stmt = $pdo->prepare("UPDATE perfume_products SET
            product_name = ?, brand_name = ?, batch_no = ?, budget = ?, fragrance_type = ?,
            target_audience = ?, design_style = ?, box_packaging_type = ?, bottle_coating = ?, box_finishing = ?,
            color = ?, size = ?, product_type = ?
            WHERE id = ?");

        $updated = $stmt->execute([
            $product_name, $brand_name, $batch_no, $budget, $fragrance_type,
            $target_audience, $design_style, $box_packaging_type, $bottle_coating, $box_finishing,
            $color, $size, $product_type,
            $id
        ]);

        if ($updated) {
            $success = 'Product details updated successfully.';
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    :root {
        --primary-color: #6f42c1;
        --primary-light: #8a63d2;
        --secondary-color: #6610f2;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --dark-gray: #343a40;
    }
    .form-container { max-width: 1000px; margin: 2rem auto; }
    .form-card { border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden; }
    .form-header { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color:white; text-align:center; padding:1.5rem; position:relative; }
    .form-header h3 { margin:0; font-weight:700; position:relative; display:inline-block; }
    .form-header h3:after { content:''; position:absolute; bottom:-10px; left:50%; transform:translateX(-50%); width:60px; height:3px; background:white; border-radius:3px; }
    .form-body { padding:2rem; background:white; }
    .alert { border-radius:8px; padding:1rem 1.5rem; margin-bottom:1.5rem; border:none; }
    .alert-success { background-color: rgba(40, 167, 69, 0.15); color: var(--success-color); }
    .alert-danger { background-color: rgba(220, 53, 69, 0.15); color: var(--danger-color); }
    .form-label { font-weight:600; margin-bottom:0.5rem; color: var(--dark-gray); }
    .required-field:after { content: " *"; color: var(--danger-color); }
    .form-control, .form-select { padding:0.75rem 1rem; border-radius:8px; border:1px solid #e0e0e0; transition: all 0.3s; }
    .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.25rem rgba(111, 66, 193,0.25); }
    .btn { padding:0.75rem 1.5rem; border-radius:8px; font-weight:600; transition: all 0.3s; border:none; display:inline-flex; align-items:center; justify-content:center; }
    .btn-success { background: linear-gradient(135deg, var(--success-color), #218838); color:white; }
    .btn-success:hover { background: linear-gradient(135deg, #218838, var(--success-color)); transform: translateY(-2px); box-shadow:0 4px 8px rgba(0,0,0,0.1); }
    .btn-secondary { background: linear-gradient(135deg, #6c757d, #5a6268); color:white; }
    .btn-secondary:hover { background: linear-gradient(135deg, #5a6268, #6c757d); transform: translateY(-2px); box-shadow:0 4px 8px rgba(0,0,0,0.1); }
    .btn i { margin-right:8px; }
    .form-section { margin-bottom:1.5rem; padding-bottom:1.5rem; border-bottom:1px solid #eee; }
    .form-section:last-child { border-bottom:none; margin-bottom:0; padding-bottom:0; }
    .form-section-title { font-weight:600; color: var(--primary-color); margin-bottom:1rem; display:flex; align-items:center; }
    .form-section-title i { margin-right:10px; font-size:1.1rem; }
    @media (max-width:768px) { .form-container { margin:1rem; } .form-body { padding:1.5rem; } }
</style>

<div class="container form-container">
    <div class="card form-card">
        <div class="form-header">
            <h3>Edit Perfume Product</h3>
        </div>
        <div class="form-body">
            <?php if ($success): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" class="row g-3">
                <!-- Basic Info -->
                <div class="form-section col-12">
                    <h5 class="form-section-title"><i class="fas fa-info-circle"></i> Basic Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label required-field">Product Name</label>
                            <input type="text" class="form-control" name="product_name" required value="<?= htmlspecialchars($product['product_name']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required-field">Brand Name</label>
                            <select class="form-select" name="brand_name" required>
                                <?php
                                $brands = ['ARD PERFUMES', 'MARCO LUCIO', 'SHANGANI', 'AL FATEH'];
                                foreach ($brands as $b) {
                                    $sel = ($product['brand_name'] === $b) ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($b)."\" $sel>".htmlspecialchars($b)."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="form-section col-12">
                    <h5 class="form-section-title"><i class="fas fa-tag"></i> Product Details</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Product Type</label>
                            <select name="product_type" class="form-select">
                                <?php
                                $types = ['Box', 'Bottle', 'Label', 'Marketing Asset'];
                                foreach ($types as $t) {
                                    $sel = ($product['product_type'] === $t) ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($t)."\" $sel>".htmlspecialchars($t)."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Batch No.</label>
                            <input type="text" class="form-control" name="batch_no" value="<?= htmlspecialchars($product['batch_no']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Budget</label>
                            <input type="text" class="form-control" name="budget" value="<?= htmlspecialchars($product['budget']) ?>">
                        </div>
                    </div>
                </div>

                <!-- Fragrance & Design -->
                <div class="form-section col-12">
                    <h5 class="form-section-title"><i class="fas fa-spray-can"></i> Fragrance & Design</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Fragrance Type</label>
                            <select class="form-select" name="fragrance_type">
                                <?php
                                $fragrances = ['Floral', 'Woody', 'Citrus', 'Oriental', 'Fresh'];
                                foreach ($fragrances as $f) {
                                    $sel = ($product['fragrance_type'] === $f) ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($f)."\" $sel>".htmlspecialchars($f)."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Target Audience</label>
                            <select class="form-select" name="target_audience">
                                <?php
                                $audiences = ['Men', 'Women', 'Unisex', 'Kids'];
                                foreach ($audiences as $a) {
                                    $sel = ($product['target_audience'] === $a) ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($a)."\" $sel>".htmlspecialchars($a)."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Design Style</label>
                            <select class="form-select" name="design_style">
                                <?php
                                $designs = ['Classic', 'Modern', 'Vintage', 'Minimalist', 'Oriental'];
                                foreach ($designs as $d) {
                                    $sel = ($product['design_style'] === $d) ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($d)."\" $sel>".htmlspecialchars($d)."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Packaging -->
                <div class="form-section col-12">
                    <h5 class="form-section-title"><i class="fas fa-box-open"></i> Packaging Details</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Box Packaging Type</label>
                            <select class="form-select" name="box_packaging_type">
                                <?php
                                $boxes = ['Foodboard', 'Metallic Board', 'Rigid Box'];
                                foreach ($boxes as $b) {
                                    $sel = ($product['box_packaging_type'] === $b) ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($b)."\" $sel>".htmlspecialchars($b)."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bottle Coating</label>
                            <select class="form-select" name="bottle_coating">
                                <?php
                                $coatings = ['Full Matte', 'Full Glossy', 'Frosted', 'Transparent', '50% Coating', 'Half Coating'];
                                foreach ($coatings as $c) {
                                    $sel = ($product['bottle_coating'] === $c) ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($c)."\" $sel>".htmlspecialchars($c)."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Box Finishing</label>
                            <select class="form-select" name="box_finishing">
                                <?php
                                $finishings = ['Matte', 'Glossy'];
                                foreach ($finishings as $f) {
                                    $sel = ($product['box_finishing'] === $f) ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($f)."\" $sel>".htmlspecialchars($f)."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Visual Details -->
                <div class="form-section col-12">
                    <h5 class="form-section-title"><i class="fas fa-palette"></i> Visual Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Color</label>
                            <input type="text" class="form-control" name="color" value="<?= htmlspecialchars($product['color']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Size</label>
                            <input type="text" class="form-control" name="size" value="<?= htmlspecialchars($product['size']) ?>">
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update Product</button>
                    <a href="view_products.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
