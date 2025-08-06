<?php
require 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/header.php';

$statuses = ['Tasks To Do', 'Pending', 'In Progress', 'Approved Internally', 'Printing Approval', 'Completion'];

// Fetch products grouped by status
$status_groups = [];
foreach ($statuses as $status) {
    $stmt = $pdo->prepare("SELECT * FROM perfume_products WHERE status = ?");
    $stmt->execute([$status]);
    $status_groups[$status] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<style>
body {
            background: linear-gradient(135deg, #6f42c1, #6610f2);
        }
    /* Card header styling with shadow */
    .card-header {
        font-size: 1.1rem;
        padding: 0.9rem 1rem;
        border-radius: 0.375rem 0.375rem 0 0;
        box-shadow: 0 2px 6px rgb(0 0 0 / 0.15);
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    /* List item style */
    .list-group-item {
        font-size: 0.95rem;
        padding: 1rem 1.25rem;
        border: none;
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.3s ease;
        cursor: default;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    /* Form select inside list */
    select.form-select-sm {
        font-size: 0.9rem;
        max-width: 100%;
        min-width: 140px;
        border-radius: 0.3rem;
        transition: box-shadow 0.3s ease;
    }
    select.form-select-sm:focus {
        box-shadow: 0 0 8px rgba(102, 16, 242, 0.5);
        border-color: #6610f2;
        outline: none;
    }

    /* Column margin */
    .card-column {
        margin-bottom: 1.75rem;
    }

    /* Status-based background colors with gradient */
    .status-Tasks-To-Do {
        background: linear-gradient(45deg, #6c757d, #495057);
    }
    .status-Pending {
        background: linear-gradient(45deg, #ffc107, #e0a800);
    }
    .status-In-Progress {
        background: linear-gradient(45deg, #0d6efd, #0b5ed7);
    }
    .status-Approved-Internally {
        background: linear-gradient(45deg, #20c997, #198754);
    }
    .status-Printing-Approval {
        background: linear-gradient(45deg, #fd7e14, #d65a07);
    }
    .status-Completion {
        background: linear-gradient(45deg, #198754, #146c43);
    }

    /* Container heading */
    h2.mb-4.text-center {
        font-weight: 700;
        color: ;
        margin-bottom: 2.5rem !important;
        letter-spacing: 0.05em;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-column {
            margin-bottom: 1rem;
        }
    }
</style>
<body>
<div class="container py-4 ">
    <h2 class="mb-4 text-center">Product Development Tracking</h2>

    <div class="row">
    <?php foreach ($status_groups as $status => $tasks): ?>
        <div class="col-md-4 card-column">
            <div class="card h-100 shadow-sm border-primary rounded-4">
                <div class="card-header text-white text-center fw-bold <?= 'status-' . str_replace(' ', '-', $status) ?>">
                    <?= htmlspecialchars($status) . ' (' . count($tasks) . ')' ?>
                </div>
                <ul class="list-group list-group-flush">
                    <?php
                    // Limit tasks to 6 only if status is Completion
                    $display_tasks = ($status === 'Completion') ? array_slice($tasks, 0, 6) : $tasks;
                    ?>

                    <?php if (count($display_tasks) > 0): ?>
                        <?php foreach ($display_tasks as $task): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="flex-grow-1">
                                    <strong><?= htmlspecialchars($task['product_name']) ?></strong><br>
                                    <small class="text-muted fst-italic"><?= htmlspecialchars($task['brand_name']) ?></small>
                                </div>
                                <form method="POST" action="update_status.php" class="m-0">
                                    <input type="hidden" name="product_id" value="<?= $task['id'] ?>">
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <?php foreach ($statuses as $option): ?>
                                            <option value="<?= $option ?>" <?= $option === $task['status'] ? 'selected' : '' ?>>
                                                <?= $option ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </li>
                        <?php endforeach; ?>

                        <?php if ($status === 'Completion' && count($tasks) > 6): ?>
                            <li class="list-group-item text-center fst-italic text-primary">
                                And <?= count($tasks) - 6 ?> more tasks hidden...
                            </li>
                        <?php endif; ?>

                    <?php else: ?>
                        <li class="list-group-item text-muted text-center fst-italic">No tasks available</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</div>

<?php require_once 'includes/footer.php'; ?>
</body>
