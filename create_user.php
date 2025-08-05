<?php
require 'includes/auth.php';
require 'includes/db.php';
require 'includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif (!in_array($role, ['admin', 'user'])) {
        $error = "Invalid role selected.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already exists.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (email, password, role, invite_token) VALUES (?, ?, ?, NULL)");
            if ($insert->execute([$email, $hashed_password, $role])) {
                $success = "User created successfully.";
                $_POST = [];
            } else {
                $error = "Database error: Could not create user.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Create User - ARD PERFUMES</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    body {
            background: linear-gradient(135deg, #6f42c1, #6610f2);
        }
  .center-container {
      min-height: calc(100vh - 60px); /* Adjust 60px to your header height */
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem;
  }
  .form-card {
      max-width: 420px;
      width: 100%;
      padding: 2.5rem;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      text-align: center;
  }
  .form-card h1 {
      font-weight: 800;
      color: #6610f2;
      margin-bottom: 0.25rem;
  }
  .form-card h4 {
      font-weight: 500;
      color: #444;
      margin-bottom: 1.5rem;
  }
  .form-control:focus {
      border-color: #6610f2;
      box-shadow: 0 0 0 0.25rem rgba(102, 16, 242, 0.25);
  }
  .btn-submit {
      background-color: #6610f2;
      border: none;
      font-weight: 600;
      transition: background-color 0.3s ease;
  }
  .btn-submit:hover {
      background-color: #5a0ecc;
  }
  .error-msg {
      color: #dc3545;
      font-weight: 600;
      margin-bottom: 1rem;
      text-align: center;
  }
  .success-msg {
      color: #198754;
      font-weight: 600;
      margin-bottom: 1rem;
      text-align: center;
  }
</style>
</head>
<body>

<div class="center-container">
  <div class="form-card shadow">
      <h1>ARD PERFUMES</h1>
      <h4>Create New User</h4>

      <?php if ($error): ?>
          <div class="error-msg"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
          <div class="success-msg"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="POST" novalidate>
          <div class="mb-3 text-start">
              <label for="email" class="form-label fw-semibold">Email address</label>
              <input
                  type="email"
                  class="form-control"
                  id="email"
                  name="email"
                  placeholder="you@example.com"
                  required
                  value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
              >
          </div>

          <div class="mb-3 text-start">
              <label for="password" class="form-label fw-semibold">Password</label>
              <input
                  type="password"
                  class="form-control"
                  id="password"
                  name="password"
                  placeholder="Enter password (min 6 chars)"
                  required
              >
          </div>

          <div class="mb-4 text-start">
              <label for="role" class="form-label fw-semibold">Role</label>
              <select class="form-select" id="role" name="role" required>
                  <option value="user" <?= (($_POST['role'] ?? '') === 'user') ? 'selected' : '' ?>>User</option>
                  <option value="admin" <?= (($_POST['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
              </select>
          </div>

          <div class="d-flex gap-2">
              <button type="submit" class="btn btn-submit flex-fill py-2">Create User</button>
              <a href="dashboard.php" class="btn btn-secondary flex-fill py-2">Back</a>
          </div>
      </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
