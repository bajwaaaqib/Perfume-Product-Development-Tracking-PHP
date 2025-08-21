<?php 
require 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/header.php';

$statuses = ['Tasks To Do', 'Pending', 'In Progress', 'Approved Internally', 'Printing Approval', 'Completion', 'Postpond'];

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'], $_POST['product_id'])) {
    $comment = trim($_POST['comment']);
    $productId = (int)$_POST['product_id'];
    
    if (!empty($comment)) {
        $stmt = $pdo->prepare("UPDATE perfume_products SET comments = CONCAT(IFNULL(comments, ''), ?) WHERE id = ?");
        $timestamp = date('Y-m-d H:i:s') . " - ";
        $stmt->execute([$timestamp . $comment . "\n\n", $productId]);
    }
}

// Map each status to a percentage (6 steps total)
$status_percentage = [
    'Tasks To Do' => 0,
    'Pending' => 0,
    'Postpond' => 0,
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

// Function to count comments
function countComments($comments) {
    if (empty($comments)) {
        return 0;
    }
    // Count the number of comment entries (each separated by double newlines)
    $commentEntries = array_filter(explode("\n\n", trim($comments)));
    return count($commentEntries);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Development Tracking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6f42c1;
            --primary-light: #8a63d2;
            --secondary-color: #6610f2;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
        }
        
        body { 
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
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
            position: relative;
        }
        
        .list-group-item:last-child { border-bottom: none; }
        
        .card-column { margin-bottom: 1.75rem; }
        
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
.status-Postpond { 
  background: linear-gradient(45deg, #6f42c1, #4b0082);
}
        
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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
            color: var(--primary-color);
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
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        .empty-value {
            color: #6c757d;
            font-style: italic;
        }
        
        /* Comment styles */
        .comment-checkbox {
            margin-right: 0.5rem;
            transform: scale(0.85);
        }
        
        .comment-section {
            margin-top: 1.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            border-left: 3px solid var(--primary-color);
        }
        
        .comment-form {
            margin-top: 1rem;
        }
        
        .comment-textarea {
            width: 100%;
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            resize: vertical;
            min-height: 60px;
            transition: all 0.3s;
            font-size: 0.85rem;
        }
        
        .comment-textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.15);
            outline: none;
        }
        
        .comment-submit {
            margin-top: 0.75rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .comment-submit:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .comment-history {
            margin-top: 0.25rem;
            font-size: 0.8rem;
            white-space: 1rem;
            background-color: white;
            padding: .5rem;
            border-radius: 0.5rem;
            height: auto;
            overflow-y: visible;
            border: 1px solid #e9ecef;
        }
        
        .comment-item {
            margin-bottom: 0.25rem;
            padding-bottom: 0.25rem;
            border-bottom: 1px solid #f1f1f1;
        }
        
        .comment-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .comment-timestamp {
            color: #6c757d;
            font-size: 0.7rem;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }
        
        .comment-text {
            line-height: 1.5;
        }
        
        .btn-comment {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        
        .btn-comment:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Comment icon and badge styles */
        .brand-comment-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            position: relative;
            min-height: 24px;
        }
        
        .brand-name {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            padding-right: 30px;
        }
        
        .btn-comment-icon {
            position: absolute;
            top: 0;
            right: 0;
            color: var(--primary-color);
            opacity: 0.7;
            transition: all 0.2s;
            font-size: 0.9rem;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            z-index: 2;
        }
        
        .btn-comment-icon:hover {
            opacity: 1;
            transform: scale(1.1);
            color: var(--primary-light);
        }
        
        .comment-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            line-height: 1;
            z-index: 3;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .list-group-item {
                padding: 0.8rem 1rem;
            }
            
            .brand-comment-container {
                align-items: center;
            }
            
            .brand-name {
                padding-right: 25px;
                font-size: 0.8rem;
            }
            
            .btn-comment-icon {
                top: 50%;
                transform: translateY(-50%);
                right: 5px;
            }
            
            .btn-comment-icon:hover {
                transform: translateY(-50%) scale(1.1);
            }
            
            .product-name {
                font-size: 0.9rem;
            }
        }
        
        /* Desktop responsiveness */
        @media (min-width: 769px) {
            .brand-comment-container {
                align-items: center;
            }
            
            .btn-comment-icon {
                top: 50%;
                transform: translateY(-50%);
            }
            
            .btn-comment-icon:hover {
                transform: translateY(-50%) scale(1.1);
            }
        }
    </style>
</head>
<body>

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
                        <?php $commentCount = countComments($task['comments']); ?>
<li class="list-group-item"
    draggable="true"
    data-id="<?= $task['id'] ?>"
    data-status="<?= $task['status'] ?>">
    
    <div class="d-flex flex-column">
        <!-- Product Name and Status -->
        <div class="d-flex align-items-center justify-content-between mb-2">
            <strong class="product-name" onclick="showProductDetails(<?= htmlspecialchars(json_encode($task)) ?>)">
                <?= htmlspecialchars($task['product_name']) ?>
            </strong>
            <span class="text-primary small ms-2">
                <?= round($status_percentage[$task['status']]) ?>%
            </span>
        </div>

        <!-- Brand Name and Product Type -->
        <div class="brand-comment-container">
            <div>
                <small class="text-muted fst-italic brand-name"><?= htmlspecialchars($task['brand_name']) ?></small><br>
                <small class="text-muted fst-italic"><?= htmlspecialchars($task['product_type'] ?: 'Type not set') ?></small>
            </div>
            
            <!-- Comment Button -->
            <button class="btn-comment-icon" 
                    onclick="event.stopPropagation(); showCommentsModal(<?= $task['id'] ?>, '<?= addslashes($task['product_name']) ?>')">
                <i class="far fa-comment"></i>
                <?php if ($commentCount > 0): ?>
                    <span class="comment-badge"><?= $commentCount ?></span>
                <?php endif; ?>
            </button>
        </div>
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

<!-- Comments Modal -->
<div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentsModalLabel">Product Comments</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="comment-history" id="commentHistory"></div>
                <form id="commentForm" class="comment-form">
                    <input type="hidden" name="product_id" id="commentProductId">
                    <textarea name="comment" class="comment-textarea" placeholder="Add your comment here..." required></textarea>
                    <button type="submit" class="comment-submit">
                        <i class="fas fa-paper-plane me-2"></i>Submit Comment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize Bootstrap modals
var productDetailsModal = new bootstrap.Modal(document.getElementById('productDetailsModal'));
var commentsModal = new bootstrap.Modal(document.getElementById('commentsModal'));
var currentProductId = null;

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
    
    // Add comments button
    html += `
        <div class="product-detail">
            <span class="product-detail-label">Comments:</span>
            <span class="product-detail-value">
                <button class="btn btn-comment" onclick="showCommentsModal(${product.id}, '${escapeHtml(product.product_name)}')">
                    <i class="fas fa-comments me-2"></i>View/Add Comments
                </button>
            </span>
        </div>
    `;
    
    modalContent.innerHTML = html;
    productDetailsModal.show();
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function showCommentsModal(productId, productName) {
    currentProductId = productId;
    document.getElementById('commentsModalLabel').textContent = `Comments: ${productName}`;
    document.getElementById('commentProductId').value = productId;
    
    // Fetch current comments
    fetch(`get_comments.php?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            const commentHistory = document.getElementById('commentHistory');
            if (data.comments && data.comments.trim() !== '') {
                // Format comments with timestamps
                const commentsArray = data.comments.trim().split('\n\n');
                let formattedComments = '';
                
                commentsArray.forEach(comment => {
                    if (comment.trim()) {
                        const [timestamp, ...commentParts] = comment.split(' - ');
                        formattedComments += `
                            <div class="comment-item">
                                <div class="comment-timestamp"><i class="far fa-clock me-2"></i>${timestamp}</div>
                                <div class="comment-text">${commentParts.join(' - ')}</div>
                            </div>
                        `;
                    }
                });
                
                commentHistory.innerHTML = formattedComments;
            } else {
                commentHistory.innerHTML = '<div class="text-muted text-center py-3">No comments yet. Be the first to add one!</div>';
            }
        });
    
    productDetailsModal.hide();
    commentsModal.show();
}

// Handle comment form submission
document.getElementById('commentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const commentTextarea = this.querySelector('textarea');
    
    fetch('update_comments.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh comments
            showCommentsModal(currentProductId, document.getElementById('commentsModalLabel').textContent.replace('Comments: ', ''));
            commentTextarea.value = '';
            
            // Refresh the page to update comment counts
            setTimeout(() => {
                location.reload();
            }, 500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

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