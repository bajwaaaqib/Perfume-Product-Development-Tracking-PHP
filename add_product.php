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
    $product_type = $_POST['product_type'] ?? '';
    $created_by = $_SESSION['user']['id'];

    if (!$product_name || !$brand_name) {
        $error = "Product Name and Brand Name are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO perfume_products (
            product_name, brand_name, batch_no, budget, fragrance_type,
            target_audience, design_style, box_packaging_type,
            bottle_coating, box_finishing, color, size, product_type, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $result = $stmt->execute([
            $product_name, $brand_name, $batch_no, $budget, $fragrance_type,
            $target_audience, $design_style, $box_packaging_type,
            $bottle_coating, $box_finishing, $color, $size, $product_type, $created_by
        ]);

        if ($result) {
            $success = "Product added successfully!";
            $_POST = [];
        } else {
            $error = "Failed to add product.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Perfume Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #495057;
    min-height: 100vh;
}
        
        .form-container {
            max-width: 1000px;
            margin: 2rem auto;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .form-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            text-align: center;
            position: relative;
        }
        
        .form-header h3 {
            font-weight: 700;
            margin: 0;
            position: relative;
            display: inline-block;
        }
        
        .form-header h3:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: white;
            border-radius: 3px;
        }
        
        .form-body {
            padding: 2rem;
            background-color: white;
        }
        
        .alert {
            border-radius: 8px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.15);
            color: var(--success-color);
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.15);
            color: var(--danger-color);
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark-gray);
        }
        
        .required-field:after {
            content: " *";
            color: var(--danger-color);
        }
        
        .form-control, .form-select {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
            box-shadow: none;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #218838);
            color: white;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #218838, var(--success-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268, #6c757d);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .form-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
        }
        
        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .form-section-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .form-section-title i {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .form-container {
                margin: 1rem;
            }
            
            .form-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<?php include 'includes/header.php'; ?>
<body>
<div class="container form-container">
    <div class="card form-card">
        <div class="form-header">
            <h3>Add New Perfume Product</h3>
        </div>
        
        <div class="form-body">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="row g-3">
                <!-- Basic Information Section -->
                <div class="form-section col-12">
                    <h5 class="form-section-title">
                        <i class="fas fa-info-circle"></i> Basic Information
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="product_name" class="form-label required-field">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required
                                   value="<?= htmlspecialchars($_POST['product_name'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="brand_name" class="form-label required-field">Brand Name</label>
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
                    </div>
                </div>

                <!-- Product Details Section -->
                <div class="form-section col-12">
                    <h5 class="form-section-title">
                        <i class="fas fa-tag"></i> Product Details
                    </h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="product_type" class="form-label">Product Type</label>
                            <select class="form-select" id="product_type" name="product_type">
                                <?php
                                $typeOptions = ['Box', 'Bottle', 'Label', 'Marketing Asset'];
                                $selectedType = $_POST['product_type'] ?? '';
                                echo '<option value="">-- Select Type --</option>';
                                foreach ($typeOptions as $option) {
                                    $sel = ($option === $selectedType) ? 'selected' : '';
                                    echo "<option value=\"" . htmlspecialchars($option) . "\" $sel>" . htmlspecialchars($option) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="batch_no" class="form-label">Batch No.</label>
                            <input type="text" class="form-control" id="batch_no" name="batch_no"
                                   value="<?= htmlspecialchars($_POST['batch_no'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="budget" class="form-label">Budget</label>
                            <input type="text" class="form-control" id="budget" name="budget"
                                   value="<?= htmlspecialchars($_POST['budget'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- Fragrance & Design Section -->
                <div class="form-section col-12">
                    <h5 class="form-section-title">
                        <i class="fas fa-spray-can"></i> Fragrance & Design
                    </h5>
                    <div class="row">
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

                        <div class="col-md-4">
                            <label for="design_style" class="form-label">Design Style</label>
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
                    </div>
                </div>

                <!-- Packaging Section -->
                <div class="form-section col-12">
                    <h5 class="form-section-title">
                        <i class="fas fa-box-open"></i> Packaging Details
                    </h5>
                    <div class="row">
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
                    </div>
                </div>

                <!-- Visual Details Section -->
                <div class="form-section col-12">
                    <h5 class="form-section-title">
                        <i class="fas fa-palette"></i> Visual Details
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="color" class="form-label">Color</label>
                            <input type="text" class="form-control" id="color" name="color"
                                   value="<?= htmlspecialchars($_POST['color'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="size" class="form-label">Size</label>
                            <input type="text" class="form-control" id="size" name="size"
                                   value="<?= htmlspecialchars($_POST['size'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="col-12 mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Add Task
                    </button>
                    <a href="view_products.php" class="btn btn-secondary">
                        <i class="fas fa-tasks"></i> View Tasks
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>