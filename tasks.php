<?php
require 'includes/auth.php';
require_once 'includes/db.php';

$statuses = ['Tasks To Do', 'Pending', 'In Progress', 'Approved Internally', 'Printing Approval', 'Completion'];

// Handle status update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE perfume_products SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['product_id']]);
    header("Location: tasks.php"); // Redirect to avoid form resubmission
    exit;
}

$status_groups = [];
foreach ($statuses as $status) {
    $stmt = $pdo->prepare("SELECT * FROM perfume_products WHERE status = ?");
    $stmt->execute([$status]);
    $status_groups[$status] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

require_once 'includes/header.php';
?>

<div class="container my-4">
    <h2 class="mb-4">Task Tracking</h2>

    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-6 g-4">
        <?php foreach ($status_groups as $status => $tasks): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-header bg-primary text-white text-center fw-bold">
                        <?= htmlspecialchars($status) ?>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php if (count($tasks) > 0): ?>
                            <?php foreach ($tasks as $task): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?= htmlspecialchars($task['product_name']) ?></span>
                                    <form method="POST" class="mb-0 d-flex align-items-center">
                                        <input type="hidden" name="product_id" value="<?= $task['id'] ?>">
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <?php foreach ($statuses as $s): ?>
                                                <option value="<?= $s ?>" <?= ($task['status'] === $s) ? 'selected' : '' ?>>
                                                    <?= $s ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted text-center">No tasks</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
