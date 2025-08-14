<?php
require 'includes/auth.php';
require 'includes/db.php';
require 'includes/header.php';

$error = '';
$success = '';

// Handle Add User
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif (!in_array($role, ['admin', 'user'])) {
        $error = "Invalid role.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)")
                ->execute([$email, $hashed, $role]);
            $success = "User added successfully.";
        }
    }
}

// Handle Delete User (with confirmation)
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];
    $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
    $success = "User deleted successfully.";
}

// Fetch all users
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
body {
    background: linear-gradient(135deg, #6f42c1, #6610f2);
    color: #495057;
}
.user-container {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    margin-top: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}
.page-title {
    font-weight: 700;
    color: #6f42c1;
    margin-bottom: 2rem;
    text-align: center;
    position: relative;
    padding-bottom: 0.5rem;
}
.page-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, #6f42c1, #6610f2);
    border-radius: 3px;
}
.user-form {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}
.user-table {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
}
.user-table thead {
    background: linear-gradient(135deg, #6f42c1, #6610f2);
    color: white;
}
.user-table th {
    font-weight: 600;
    padding: 1rem;
}
.user-table td {
    padding: 1rem;
    vertical-align: middle;
}
.role-badge {
    padding: 0.35rem 0.65rem;
    border-radius: 50px;
    font-weight: 500;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.role-admin {
    background-color: rgba(111, 66, 193, 0.1);
    color: #6f42c1;
}
.role-user {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}
.btn-add {
    background: linear-gradient(135deg, #6f42c1, #6610f2);
    border: none;
    font-weight: 500;
}
.btn-add:hover {
    background: linear-gradient(135deg, #5e35b1, #5600e8);
}
.btn-delete {
    transition: all 0.3s;
}
.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.25);
}
.alert {
    border-radius: 8px;
    padding: 1rem 1.5rem;
}
.form-control, .form-select {
    padding: 0.75rem 1rem;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s;
}
.form-control:focus, .form-select:focus {
    border-color: #6f42c1;
    box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
}
</style>

<div class="container user-container">
    <h2 class="page-title">User Management</h2>

    <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $success ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Add User Form -->
    <form method="POST" class="user-form">
        <input type="hidden" name="action" value="add">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="email" class="form-label fw-medium">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="user@example.com" required>
            </div>
            <div class="col-md-3">
                <label for="password" class="form-label fw-medium">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••" required>
            </div>
            <div class="col-md-3">
                <label for="role" class="form-label fw-medium">Role</label>
                <select id="role" name="role" class="form-select">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-add text-white w-100 py-2">
                    <i class="fas fa-user-plus me-2"></i> Add User
                </button>
            </div>
        </div>
    </form>

    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <span class="role-badge role-<?= $u['role'] ?>">
                            <?= $u['role'] ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <button class="btn btn-sm btn-danger btn-delete" type="submit">
                                <i class="fas fa-trash-alt me-1"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Add confirmation for delete action
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('form[action="delete"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this user?')) {
                e.preventDefault();
            }
        });
    });
});
</script>

<?php require 'includes/footer.php'; ?>