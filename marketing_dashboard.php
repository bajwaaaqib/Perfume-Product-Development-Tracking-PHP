<?php
require 'includes/auth.php';
require_once 'includes/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Platforms
$platforms = ['Instagram', 'Facebook', 'TikTok', 'LinkedIn', 'Website'];
$platform = isset($_GET['platform']) && in_array($_GET['platform'], $platforms) ? $_GET['platform'] : 'Instagram';
$month = date('Y-m'); // current month

$data = [];
$labels = [];

// Fetch marketing stats for the selected month and platform
if ($platform === 'Website') {
    $stmt = $pdo->prepare("
        SELECT stat_date, website_views, website_clicks
        FROM marketing_stats
        WHERE platform = :platform AND stat_date LIKE :month
        ORDER BY stat_date ASC
    ");
    $stmt->execute([
        ':platform' => $platform,
        ':month' => "$month%"
    ]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $labels[] = date('M j', strtotime($row['stat_date']));
        $data['views'][] = $row['website_views'];
        $data['clicks'][] = $row['website_clicks'];
    }
} else {
    $stmt = $pdo->prepare("
        SELECT stat_date, followers, likes, comments, shares, posts_count
        FROM marketing_stats
        WHERE platform = :platform AND stat_date LIKE :month
        ORDER BY stat_date ASC
    ");
    $stmt->execute([
        ':platform' => $platform,
        ':month' => "$month%"
    ]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $labels[] = date('M j', strtotime($row['stat_date']));
        $data['followers'][] = $row['followers'];
        $data['likes'][] = $row['likes'];
        $data['comments'][] = $row['comments'];
        $data['shares'][] = $row['shares'];
        $data['posts_count'][] = $row['posts_count'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Marketing Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #00308F;
            --light-bg: #f8f9fa;
            --card-bg: #ffffff;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 60px;
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

        .action-buttons {
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-action {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-action:hover {
            background-color: #002366;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .platform-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .platform-btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            background-color: #e9ecef;
            transition: all 0.2s;
        }

        .platform-btn.active {
            background-color: var(--primary-color);
            color: white;
        }

        .platform-btn:hover {
            background-color: #dee2e6;
        }

        .stats-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 30px;
        }

        .chart-container {
            position: relative;
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <div class="dashboard-container">
            <h1><i class="fas fa-chart-line"></i> Marketing Dashboard</h1>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="add_stats.php" class="btn btn-action"><i class="fas fa-plus-circle"></i> Add Daily Stats</a>
            <a href="all_states.php" class="btn btn-action"><i class="fas fa-image"></i> All States</a>
        </div>

        <!-- Stats Card -->
        <div class="stats-card">
                  <!-- Platform Selector -->
<div class="platform-selector">
    <form method="get" action="">
        <select name="platform" onchange="this.form.submit()" class="form-select" style="max-width:250px; display:inline-block;">
            <?php foreach($platforms as $p): ?>
                <option value="<?= $p ?>" <?= $p === $platform ? 'selected' : '' ?>>
                    <?= $p ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>
            <h4 class="mb-3"><?= htmlspecialchars($platform) ?> Stats - <?= date('F Y') ?></h4>
            <div class="chart-container">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('lineChart').getContext('2d');
    const chartData = {
        labels: <?= json_encode($labels) ?>,
        datasets: [
            <?php if($platform === 'Website'): ?>
            {
                label: 'Views',
                data: <?= json_encode($data['views'] ?? []) ?>,
                borderColor: '#00308F',
                backgroundColor: 'rgba(0,48,143,0.1)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Clicks',
                data: <?= json_encode($data['clicks'] ?? []) ?>,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40,167,69,0.1)',
                fill: true,
                tension: 0.3
            }
            <?php else: ?>
            {
                label: 'Followers',
                data: <?= json_encode($data['followers'] ?? []) ?>,
                borderColor: '#00308F',
                backgroundColor: 'rgba(0,48,143,0.1)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Likes',
                data: <?= json_encode($data['likes'] ?? []) ?>,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40,167,69,0.1)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Comments',
                data: <?= json_encode($data['comments'] ?? []) ?>,
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255,193,7,0.1)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Shares',
                data: <?= json_encode($data['shares'] ?? []) ?>,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220,53,69,0.1)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Posts Count',
                data: <?= json_encode($data['posts_count'] ?? []) ?>,
                borderColor: '#6f42c1',
                backgroundColor: 'rgba(111,66,193,0.1)',
                fill: true,
                tension: 0.3
            }
            <?php endif; ?>
        ]
    };

    new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Monthly Performance Metrics' }
            },
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { beginAtZero: true, title: { display: true, text: 'Count' } }
            }
        }
    });
    </script>
    
    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
