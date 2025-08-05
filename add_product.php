<?php
require 'includes/auth.php';
require 'includes/db.php';
$success = '';
$error = '';

// Check user session before inserting
if (!isset($_SESSION['user']['id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    $created_by = $_SESSION['user']['id'];

    if (!$product_name || !$brand_name) {
        $error = "Product Name and Brand Name are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO perfume_products (
            product_name, brand_name, batch_no, budget, fragrance_type,
            target_audience, design_style, box_packaging_type,
            bottle_coating, box_finishing, color, size, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $result = $stmt->execute([
            $product_name, $brand_name, $batch_no, $budget, $fragrance_type,
            $target_audience, $design_style, $box_packaging_type,
            $bottle_coating, $box_finishing, $color, $size, $created_by
        ]);

        if ($result) {
            $success = "Product added successfully!";
            // Clear POST data after success to clear form fields
            $_POST = [];
        } else {
            $error = "Failed to add product.";
        }
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

<?php include 'includes/header.php'; ?>
<body>
<div class="container my-5">
  <div class="card shadow-sm mx-auto" style="max-width: 900px;">
    <div class="card-header bg-primary text-white">
      <h3 class="mb-0">Add New Perfume Product</h3>
    </div>
    <div class="card-body">
      <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
      <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" class="row g-3">
        <!-- Product Name (Text) -->
        <div class="col-md-6">
          <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="product_name" name="product_name" required
                 value="<?= htmlspecialchars($_POST['product_name'] ?? '') ?>">
        </div>

        <!-- Brand Name (Dropdown) -->
        <div class="col-md-6">
          <label for="brand_name" class="form-label">Brand Name <span class="text-danger">*</span></label>
          <select class="form-select" id="brand_name" name="brand_name" required>
            <?php
            $brandOptions = ['ARD PERFUMES', 'MARCO LUCIO', 'SHANGANI', 'AL FATEH'];
            $selectedBrand = $_POST['brand_name'] ?? '';
            echo '<option value="">-- Select Brand --</option>';
            foreach ($brandOptions as $option) {
                $sel = ($option === $selectedBrand) ? 'selected' : '';
                echo "<option value=\"" . htmlspecialchars($option) . "\" $sel>" . htmlspecialchars($option) . "</option>";
            }
            ?>
          </select>
        </div>

        <!-- Batch No. (Text) -->
        <div class="col-md-4">
          <label for="batch_no" class="form-label">Batch No.</label>
          <input type="text" class="form-control" id="batch_no" name="batch_no"
                 value="<?= htmlspecialchars($_POST['batch_no'] ?? '') ?>">
        </div>

        <!-- Budget (Text) -->
        <div class="col-md-4">
          <label for="budget" class="form-label">Budget</label>
          <input type="text" class="form-control" id="budget" name="budget"
                 value="<?= htmlspecialchars($_POST['budget'] ?? '') ?>">
        </div>

        <!-- Fragrance Type (Dropdown) -->
        <div class="col-md-4">
          <label for="fragrance_type" class="form-label">Fragrance Type</label>
          <select class="form-select" id="fragrance_type" name="fragrance_type">
            <?php
            $fragranceOptions = ['Floral', 'Woody', 'Citrus', 'Oriental', 'Fresh'];
            $selectedFragrance = $_POST['fragrance_type'] ?? '';
            echo '<option value="">-- Select Fragrance --</option>';
            foreach ($fragranceOptions as $option) {
                $sel = ($option === $selectedFragrance) ? 'selected' : '';
                echo "<option value=\"" . htmlspecialchars($option) . "\" $sel>" . htmlspecialchars($option) . "</option>";
            }
            ?>
          </select>
        </div>

        <!-- Target Audience (Dropdown) -->
        <div class="col-md-4">
          <label for="target_audience" class="form-label">Target Audience</label>
          <select class="form-select" id="target_audience" name="target_audience">
            <?php
            $audienceOptions = ['Men', 'Women', 'Unisex', 'Kids'];
            $selectedAudience = $_POST['target_audience'] ?? '';
            echo '<option value="">-- Select Audience --</option>';
            foreach ($audienceOptions as $option) {
                $sel = ($option === $selectedAudience) ? 'selected' : '';
                echo "<option value=\"" . htmlspecialchars($option) . "\" $sel>" . htmlspecialchars($option) . "</option>";
            }
            ?>
          </select>
        </div>

        <!-- Design Style (Dropdown) -->
        <div class="col-md-4">
          <label for="design_style" class="form-label">Design Style Preference</label>
          <select class="form-select" id="design_style" name="design_style">
            <?php
            $designOptions = ['Classic', 'Modern', 'Vintage', 'Minimalist', 'Oriental'];
            $selectedDesign = $_POST['design_style'] ?? '';
            echo '<option value="">-- Select Design Style --</option>';
            foreach ($designOptions as $option) {
                $sel = ($option === $selectedDesign) ? 'selected' : '';
                echo "<option value=\"" . htmlspecialchars($option) . "\" $sel>" . htmlspecialchars($option) . "</option>";
            }
            ?>
          </select>
        </div>

        <!-- Box Packaging Type (Dropdown) -->
        <div class="col-md-4">
          <label for="box_packaging_type" class="form-label">Box Packaging Type</label>
          <select class="form-select" id="box_packaging_type" name="box_packaging_type">
            <?php
            $boxPackagingOptions = ['Foodboard', 'Metallic Board', 'Rigid Box'];
            $selectedBox = $_POST['box_packaging_type'] ?? '';
            echo '<option value="">-- Select Packaging Type --</option>';
            foreach ($boxPackagingOptions as $option) {
                $sel = ($option === $selectedBox) ? 'selected' : '';
                echo "<option value=\"" . htmlspecialchars($option) . "\" $sel>" . htmlspecialchars($option) . "</option>";
            }
            ?>
          </select>
        </div>

        <!-- Bottle Coating (Dropdown) -->
        <div class="col-md-4">
          <label for="bottle_coating" class="form-label">Bottle Coating</label>
          <select class="form-select" id="bottle_coating" name="bottle_coating">
            <?php
            $bottleCoatingOptions = ['Full Matte', 'Full Glossy', 'Frosted', 'Transparent', '50% Coating', 'Half Coating'];
            $selectedCoating = $_POST['bottle_coating'] ?? '';
            echo '<option value="">-- Select Bottle Coating --</option>';
            foreach ($bottleCoatingOptions as $option) {
                $sel = ($option === $selectedCoating) ? 'selected' : '';
                echo "<option value=\"" . htmlspecialchars($option) . "\" $sel>" . htmlspecialchars($option) . "</option>";
            }
            ?>
          </select>
        </div>

        <!-- Box Finishing (Dropdown) -->
        <div class="col-md-4">
          <label for="box_finishing" class="form-label">Box Finishing</label>
          <select class="form-select" id="box_finishing" name="box_finishing">
            <?php
            $boxFinishingOptions = ['Matte', 'Glossy'];
            $selectedFinishing = $_POST['box_finishing'] ?? '';
            echo '<option value="">-- Select Box Finishing --</option>';
            foreach ($boxFinishingOptions as $option) {
                $sel = ($option === $selectedFinishing) ? 'selected' : '';
                echo "<option value=\"" . htmlspecialchars($option) . "\" $sel>" . htmlspecialchars($option) . "</option>";
            }
            ?>
          </select>
        </div>

        <!-- Color (Text Input) -->
        <div class="col-md-6">
          <label for="color" class="form-label">Color</label>
          <input type="text" class="form-control" id="color" name="color"
                 value="<?= htmlspecialchars($_POST['color'] ?? '') ?>">
        </div>

        <!-- Size (Text Input) -->
        <div class="col-md-6">
          <label for="size" class="form-label">Size</label>
          <input type="text" class="form-control" id="size" name="size"
                 value="<?= htmlspecialchars($_POST['size'] ?? '') ?>">
        </div>

        <div class="col-12 mt-3 d-flex justify-content-between">
          <button type="submit" class="btn btn-success px-4">Add Product</button>
          <a href="view_products.php" class="btn btn-secondary px-4">View Tasks</a>
        </div>
      </form>

    </div>
  </div>
</div>
</body>
