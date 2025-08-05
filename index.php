<?php
session_start();
require 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - ARD PERFUMES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #6f42c1, #6610f2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            max-width: 420px;
            width: 100%;
            padding: 2.5rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }
        .login-card h1 {
            font-weight: 800;
            color: #6610f2;
            margin-bottom: 0.25rem;
        }
        .login-card h4 {
            font-weight: 500;
            color: #444;
            margin-bottom: 1.5rem;
        }
        .form-control:focus {
            border-color: #6610f2;
            box-shadow: 0 0 0 0.25rem rgba(102, 16, 242, 0.25);
        }
        .btn-login {
            background-color: #6610f2;
            border: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .btn-login:hover {
            background-color: #5a0ecc;
        }
        .error-msg {
            color: #dc3545;
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-card shadow">
    <h1>ARD PERFUMES</h1>
    <h4>Product Development Tracking</h4>

    <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3 text-start">
            <label for="email" class="form-label fw-semibold">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="mb-4 text-start">
            <label for="password" class="form-label fw-semibold">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-login w-100 py-2">Login</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
