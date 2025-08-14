<?php 
require 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/header.php';

$statuses = ['Tasks To Do', 'Pending', 'In Progress', 'Approved Internally', 'Printing Approval', 'Completion'];

// Map each status to a percentage (6 steps total)
$status_percentage = [
    'Tasks To Do' => 0,
    'Pending' => 0,
    'In Progress' => 100*3/6,
    'Approved Internally' => 100*4/6,
    'Printing Approval' => 100*5/6,
    'Completion' => 100
];

// Fetch products grouped by status
$status_groups = [];
foreach ($statuses as $status) {
    $stmt = $pdo->prepare("SELECT * FROM perfume_products WHERE status = ?");
    $stmt->execute([$status]);
    $status_groups[$status] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<style>
body { background: linear-gradient(135deg, #6f42c1, #6610f2); }
.card-header {
    font-size: 1.1rem;
    padding: 0.9rem 1rem;
    border-radius: 0.375rem 0.375rem 0 0;
    box-shadow: 0 2px 6px rgb(0 0 0 / 0.15);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.list-group-item {
    font-size: 0.95rem;
    padding: 1rem 1.25rem;
    border: none;
    border-bottom: 1px solid #e9ecef;
    cursor: grab;
}
.list-group-item:last-child { border-bottom: none; }
.card-column { margin-bottom: 1.75rem; }
.status-Tasks-To-Do { background: linear-gradient(45deg, #6c757d, #495057); }
.status-Pending { background: linear-gradient(45deg, #ffc107, #e0a800); }
.status-In-Progress { background: linear-gradient(45deg, #0d6efd, #0b5ed7); }
.status-Approved-Internally { background: linear-gradient(45deg, #20c997, #198754); }
.status-Printing-Approval { background: linear-gradient(45deg, #fd7e14, #d65a07); }
.status-Completion { background: linear-gradient(45deg, #198754, #146c43); }
h2.mb-4.text-center {
    font-weight: 700;
    color: #fff;
    margin-bottom: 2.5rem !important;
    letter-spacing: 0.05em;
}
.list-group { min-height: 120px; }
.list-group.drag-over {
    background-color: rgba(102, 16, 242, 0.1);
    border: 2px dashed #6610f2;
}
/* Modal styles */
.modal-content {
    border-radius: 0.5rem;
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
.modal-header {
    background: linear-gradient(135deg, #6f42c1, #6610f2);
    color: white;
    border-radius: 0.5rem 0.5rem 0 0;
    padding: 1rem 1.5rem;
}
.modal-title {
    font-weight: 600;
}
.modal-body {
    padding: 1.5rem;
}
.product-detail {
    margin-bottom: 1rem;
    display: flex;
    flex-wrap: wrap;
}
.product-detail-label {
    font-weight: 600;
    color: #6f42c1;
    width: 200px;
    flex-shrink: 0;
}
.product-detail-value {
    flex-grow: 1;
}
.product-name {
    cursor: pointer;
    transition: color 0.2s;
}
.product-name:hover {
    color: #6610f2;
    text-decoration: underline;
}
.empty-value {
    color: #6c757d;
    font-style: italic;
}
</style>

<div class="container py-4">
    <h2 class="mb-4 text-center">Product Development Tracking</h2>

    <div class="row g-3">
    <?php foreach ($status_groups as $status => $tasks): ?>
        <div class="col-12 col-sm-6 col-lg-4 card-column">
            <div class="card h-100 shadow-sm border-primary rounded-4">
                <div class="card-header text-white text-center fw-bold <?= 'status-' . str_replace(' ', '-', $status) ?>">
                    <?= htmlspecialchars($status) ?> (<span class="task-count"><?= count($tasks) ?></span>)
                </div>
                <ul class="list-group list-group-flush task-column" data-status="<?= htmlspecialchars($status) ?>">
                    <?php foreach ($tasks as $task): ?>
                        <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2"
                            draggable="true"
                            data-id="<?= $task['id'] ?>"
                            data-status="<?= $task['status'] ?>">
                            <div class="flex-grow-1 overflow-hidden">
                                <strong class="d-flex align-items-center text-nowrap overflow-hidden text-truncate" style="max-width: 100%;">
                                    <span class="product-name" onclick="showProductDetails(<?= htmlspecialchars(json_encode($task)) ?>)">
                                        <?= htmlspecialchars($task['product_name']) ?>
                                    </span>
                                    <span class="text-primary ms-2 small flex-shrink-0">
                                        <?= round($status_percentage[$task['status']]) ?>%
                                    </span>
                                </strong>
                                <small class="text-muted fst-italic"><?= htmlspecialchars($task['brand_name']) ?></small>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>

<!-- Product Details Modal -->
<div class="modal fade" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productDetailsModalLabel">Product Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productDetailsContent">
                <!-- Content will be inserted here by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize Bootstrap modal
var productDetailsModal = new bootstrap.Modal(document.getElementById('productDetailsModal'));

function formatFieldName(field) {
    return field.replace(/_/g, ' ')
               .replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
}

function showProductDetails(product) {
    const modalContent = document.getElementById('productDetailsContent');
    document.getElementById('productDetailsModalLabel').textContent = product.product_name + ' Details';
    
    // Define the fields we want to show in order
    const fieldsToShow = [
        'product_name',
        'brand_name',
        'batch_no',
        'budget',
        'fragrance_type',
        'target_audience',
        'design_style',
        'box_packaging_type',
        'bottle_coating',
        'box_finishing',
        'created_at',
        'status',
        'color',
        'size',
        'product_type'
    ];
    
    let html = '';
    
    fieldsToShow.forEach(field => {
        const value = product[field] ? product[field] : '<span class="empty-value">Not specified</span>';
        const formattedField = formatFieldName(field);
        
        html += `
            <div class="product-detail">
                <span class="product-detail-label">${formattedField}:</span>
                <span class="product-detail-value">${value}</span>
            </div>
        `;
    });
    
    modalContent.innerHTML = html;
    productDetailsModal.show();
}

document.addEventListener('DOMContentLoaded', () => {
    const draggables = document.querySelectorAll('.list-group-item');
    const columns = document.querySelectorAll('.task-column');

    let draggedItem = null;

    draggables.forEach(item => {
        item.addEventListener('dragstart', function() {
            draggedItem = this;
            setTimeout(() => this.style.display = 'none', 0);
        });

        item.addEventListener('dragend', function() {
            draggedItem.style.display = 'flex';
            draggedItem = null;
        });
    });

    columns.forEach(column => {
        column.addEventListener('dragover', e => e.preventDefault());

        column.addEventListener('drop', function() {
            if (!draggedItem) return;

            const newStatus = this.dataset.status;
            draggedItem.dataset.status = newStatus;
            this.appendChild(draggedItem);

            updateTaskInDB(draggedItem.dataset.id, newStatus);
            updatePercentage(draggedItem, newStatus);
            updateCounts();
        });
    });

    function updateTaskInDB(id, status) {
        fetch('update_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${encodeURIComponent(id)}&status=${encodeURIComponent(status)}`
        }).then(res => res.json())
          .then(data => {
              if (!data.success) {
                  alert('Failed to update');
              }
          });
    }

    function updatePercentage(item, status) {
        const percentages = {
            'Tasks To Do': 0,
            'Pending': 0,
            'In Progress': 100*3/6,
            'Approved Internally': 100*4/6,
            'Printing Approval': 100*5/6,
            'Completion': 100
        };
        item.querySelector('span.text-primary').textContent = Math.round(percentages[status]) + '%';
    }

    function updateCounts() {
        document.querySelectorAll('.task-column').forEach(col => {
            col.parentElement.querySelector('.task-count').textContent = col.querySelectorAll('.list-group-item').length;
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>