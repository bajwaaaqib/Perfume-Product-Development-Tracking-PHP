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
            $message = '<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>Product added successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>Error: ' . htmlspecialchars($e->getMessage()) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
    } else {
        $message = '<div class="alert alert-warning alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i>Name and Brand are required.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
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
            --warning-color: #ffc107;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            color: #495057;
        }
        
        .product-form-container {
            max-width: 700px;
            margin: 2rem auto;
            animation: fadeIn 0.3s ease-out;
        }
        
        .product-form-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        
        .product-form-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            text-align: center;
            position: relative;
        }
        
        .product-form-header h3 {
            font-weight: 700;
            margin: 0;
            position: relative;
            display: inline-block;
        }
        
        .product-form-header h3:after {
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
        
        .product-form-body {
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
        
        .alert-warning {
            background-color: rgba(255, 193, 7, 0.15);
            color: var(--warning-color);
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
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.15);
        }
        
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            border: none;
            color: white;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268, #6c757d);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .product-form-container {
                margin: 1rem;
            }
            
            .product-form-body {
                padding: 1.5rem;
            }
            
            .form-actions {
                flex-direction: column-reverse;
                gap: 1rem;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="container product-form-container">
    <div class="card product-form-card">
        <div class="product-form-header">
            <h3><i class="fas fa-wine-bottle me-2"></i>Add New Perfume Product</h3>
        </div>
        
        <div class="product-form-body">
            <?php echo $message; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="name" class="form-label required-field">Product Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="brand" class="form-label required-field">Brand</label>
                    <select id="brand" name="brand" class="form-select" required>
                        <option value="">-- Select Brand --</option>
                        <option value="ARD PERFUMES">ARD PERFUMES</option>
                        <option value="MARCO LUCIO">MARCO LUCIO</option>
                        <option value="SHANGANI">SHANGANI</option>
                        <option value="AL FATEH">AL FATEH</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="category" class="form-label">Category</label>
                    <select id="category" name="category" class="form-select">
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

                <div class="mb-4">
                    <label for="type" class="form-label">Type</label>
                    <select id="type" name="type" class="form-select">
                        <option value="">-- Select Type --</option>
                        <option value="Box/Bottle">Box/Bottle</option>
                        <option value="Label">Label</option>
                        <option value="Bottle/CAN">Bottle/CAN</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label required-field">Status</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="true">✔ Active (Continue)</option>
                        <option value="false">✗ Inactive (Discontinue)</option>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="products.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Products
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
</body>
</html>