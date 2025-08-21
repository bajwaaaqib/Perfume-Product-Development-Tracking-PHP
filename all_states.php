<?php
require 'includes/auth.php'; 
require 'includes/db.php';

// Fetch stats
$stmt = $pdo->query("SELECT * FROM marketing_stats ORDER BY created_at DESC");
$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Marketing Stats</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <style>
    :root {
            --primary-color: #00308F;
            --light-bg: #f8f9fa;
            --card-bg: #ffffff;
        }

    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #495057;
    }

    .page-header {
      border-bottom: 2px solid rgba(13, 110, 253, 0.2);
      padding-bottom: 0.75rem;
      margin-bottom: 2rem;
    }

    .page-title {
      font-weight: 700;
      color: var(--primary-color);
      margin: 0;
    }

    .stats-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .table-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
    }

    .table {
      width: 100%;
      table-layout: auto;
      border-collapse: separate;
      border-spacing: 0;
      border-radius: 12px;
      overflow: hidden;
    }

    .table th, .table td {
      font-size: 0.9rem;
      padding: 0.75rem 1rem;
      vertical-align: middle;
      white-space: nowrap;
    }

    .table-hover tbody tr:hover {
      background-color: rgba(13, 110, 253, 0.05);
    }

    .empty-state {
      padding: 3rem;
      text-align: center;
      color: #6c757d;
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: #dee2e6;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
      animation: fadeIn 0.3s ease-out;
    }
    
            .dashboard-header {
            background: var(--primary-color);
            color: white;
            padding: 25px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
                .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
  </style>
</head>
<body>
    <div class="dashboard-header">
        <div class="dashboard-container">
            <h1><i class="fas fa-chart-line"></i> Marketing Dashboard</h1>
        </div>
    </div>
<div class="dashboard-container my-4 fade-in">
  <div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title">
      <i class="fas fa-chart-line me-2"></i>Marketing Stats
    </h1>
    <div class="d-flex gap-2">
      <a href="add_stats.php" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Stats
      </a>
      <a href="marketing_dashboard.php" class="btn btn-secondary">
        ⬅ Back
      </a>
    </div>
  </div>

  <div class="card stats-card">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-header">
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Platform</th>
            <th>Stat Date</th>
            <th>Followers</th>
            <th>Likes</th>
            <th>Comments</th>
            <th>Shares</th>
            <th>Posts</th>
            <th>Website Views</th>
            <th>Website Clicks</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($stats): ?>
            <?php $count = 1; ?>
            <?php foreach ($stats as $row): ?>
              <tr>
                <td><?= $count++ ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['platform']) ?></td>
                <td><?= htmlspecialchars($row['stat_date']) ?></td>
                <td><?= htmlspecialchars($row['followers']) ?></td>
                <td><?= htmlspecialchars($row['likes']) ?></td>
                <td><?= htmlspecialchars($row['comments']) ?></td>
                <td><?= htmlspecialchars($row['shares']) ?></td>
                <td><?= htmlspecialchars($row['posts_count']) ?></td>
                <td><?= htmlspecialchars($row['website_views']) ?></td>
                <td><?= htmlspecialchars($row['website_clicks']) ?></td>
                <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="12">
                <div class="empty-state">
                  <i class="fas fa-chart-bar"></i>
                  <h4>No stats available</h4>
                  <p class="text-muted">Add your first stats record to get started</p>
                  <a href="add_stats.php" class="btn btn-primary mt-2">
                    <i class="fas fa-plus me-2"></i>Add Stats
                  </a>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
