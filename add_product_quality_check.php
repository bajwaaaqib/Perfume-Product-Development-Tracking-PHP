<?php
require 'includes/auth.php';
require 'includes/db.php';

$success = '';
$error = '';

$brandOptions = ['ARD PERFUMES', 'MARCO LUCIO', 'SHANGANI', 'AL FATEH'];
$checkedByOptions = ['Aaqib', 'CEO', 'Aziz'];
$statusOptions = ['Pending', 'Approved', 'Rejected', 'In Progress'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'] ?? '';
    $batch_number = $_POST['batch_number'] ?? '';
    $brand_name = $_POST['brand_name'] ?? '';
    $printing_company = $_POST['printing_company'] ?? '';
    $presented_by = $_POST['presented_by'] ?? '';
    $checked_by = $_POST['checked_by'] ?? '';
    $status = $_POST['status'] ?? '';
    $entry_date = $_POST['entry_date'] ?? date('Y-m-d');

    if (!$product_name || !$brand_name) {
        $error = 'Product Name and Brand Name are required.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO Product_quality_check (
            product_name, batch_number, brand_name, printing_company, presented_by, checked_by, status, entry_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $result = $stmt->execute([
            $product_name, $batch_number, $brand_name, $printing_company, $presented_by, $checked_by, $status, $entry_date
        ]);

        if ($result) {
            $success = 'Entry added successfully!';
            $_POST = [];
        } else {
            $error = 'Failed to add entry.';
        }
    }
}

require 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Quality Check</title>
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
        
        .quality-form-container {
            max-width: 800px;
            margin: 2rem auto;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .quality-form-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        
        .quality-form-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            text-align: center;
            position: relative;
        }
        
        .quality-form-header h2 {
            font-weight: 700;
            margin: 0;
            position: relative;
            display: inline-block;
        }
        
        .quality-form-header h2:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: white;
            border-radius: 3px;
        }
        
        .quality-form-body {
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
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
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
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .status-approved {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }
        
        .status-rejected {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }
        
        .status-in-progress {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .quality-form-container {
                margin: 1rem;
            }
            
            .quality-form-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
<div class="container quality-form-container">
    <div class="card quality-form-card">
        <div class="quality-form-header">
            <h2><i class="fas fa-clipboard-check me-2"></i>Product Quality Check</h2>
        </div>
        
        <div class="quality-form-body">
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
                <!-- Product Information -->
                <div class="col-md-6 form-group">
                    <label for="product_name" class="form-label required-field">Product Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-cube"></i></span>
                        <input type="text" name="product_name" id="product_name" required 
                               class="form-control" value="<?= htmlspecialchars($_POST['product_name'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-6 form-group">
                    <label for="batch_number" class="form-label">Batch Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                        <input type="text" name="batch_number" id="batch_number" 
                               class="form-control" value="<?= htmlspecialchars($_POST['batch_number'] ?? '') ?>">
                    </div>
                </div>

                <!-- Brand & Printing -->
                <div class="col-md-6 form-group">
                    <label for="brand_name" class="form-label required-field">Brand Name</label>
                    <select name="brand_name" id="brand_name" required class="form-select">
                        <option value="">-- Select Brand --</option>
                        <?php foreach ($brandOptions as $option): 
                            $sel = (isset($_POST['brand_name']) && $_POST['brand_name'] === $option) ? 'selected' : '';
                        ?>
                            <option value="<?= htmlspecialchars($option) ?>" <?= $sel ?>><?= htmlspecialchars($option) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 form-group">
                    <label for="printing_company" class="form-label">Printing Company</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-print"></i></span>
                        <input type="text" name="printing_company" id="printing_company" 
                               class="form-control" value="<?= htmlspecialchars($_POST['printing_company'] ?? '') ?>">
                    </div>
                </div>

                <!-- Personnel -->
                <div class="col-md-6 form-group">
                    <label for="presented_by" class="form-label">Presented By</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        <input type="text" name="presented_by" id="presented_by" 
                               class="form-control" value="<?= htmlspecialchars($_POST['presented_by'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-6 form-group">
                    <label for="checked_by" class="form-label">Checked By</label>
                    <select name="checked_by" id="checked_by" class="form-select">
                        <option value="">-- Select Checker --</option>
                        <?php foreach ($checkedByOptions as $option):
                            $sel = (isset($_POST['checked_by']) && $_POST['checked_by'] === $option) ? 'selected' : '';
                        ?>
                            <option value="<?= htmlspecialchars($option) ?>" <?= $sel ?>><?= htmlspecialchars($option) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Status & Date -->
                <div class="col-md-6 form-group">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Select Status --</option>
                        <?php foreach ($statusOptions as $option):
                            $sel = (isset($_POST['status']) && $_POST['status'] === $option) ? 'selected' : '';
                            $badgeClass = strtolower(str_replace(' ', '-', $option));
                        ?>
                            <option value="<?= htmlspecialchars($option) ?>" <?= $sel ?>>
                                <span class="status-badge status-<?= $badgeClass ?>"><?= htmlspecialchars($option) ?></span>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 form-group">
                    <label for="entry_date" class="form-label">Entry Date</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" name="entry_date" id="entry_date" 
                               class="form-control" value="<?= htmlspecialchars($_POST['entry_date'] ?? date('Y-m-d')) ?>">
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="col-12 mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add Entry
                    </button>
                    <a href="view_product_quality_check.php" class="btn btn-secondary">
                        <i class="fas fa-list"></i> View Entries
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>