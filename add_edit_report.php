<?php
require 'includes/auth.php';
require 'includes/db.php';

$id = $_GET['id'] ?? null;
$report_date = $_POST['report_date'] ?? date('Y-m-d');
$content = $_POST['content'] ?? '';

$success = '';
$error = '';

if ($id) {
    // Fetch existing report for edit
    $stmt = $pdo->prepare("SELECT * FROM weekly_reports WHERE id = ?");
    $stmt->execute([$id]);
    $report = $stmt->fetch();
    if (!$report) {
        die('Report not found.');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $report_date = $report['report_date'];
        $content = $report['content'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$report_date || !$content) {
        $error = 'Date and report content are required.';
    } else {
        if ($id) {
            // Update existing report
            $stmt = $pdo->prepare("UPDATE weekly_reports SET report_date = ?, content = ? WHERE id = ?");
            $result = $stmt->execute([$report_date, $content, $id]);
            if ($result) {
                $success = 'Report updated successfully.';
            } else {
                $error = 'Failed to update report.';
            }
        } else {
            // Insert new report
            $stmt = $pdo->prepare("INSERT INTO weekly_reports (report_date, content) VALUES (?, ?)");
            $result = $stmt->execute([$report_date, $content]);
            if ($result) {
                $success = 'Report added successfully.';
                // Clear form
                $report_date = date('Y-m-d');
                $content = '';
            } else {
                $error = 'Failed to add report.';
            }
        }
    }
}

require 'includes/header.php';
?>

<div class="container my-5" style="max-width: 700px;">
    <h2><?= $id ? 'Edit' : 'Add' ?> Weekly Report</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="report_date" class="form-label">Date</label>
            <input type="date" id="report_date" name="report_date" required class="form-control" value="<?= htmlspecialchars($report_date) ?>">
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Report Content (Bullet points or paragraph)</label>
            <textarea id="content" name="content" rows="6" required class="form-control"><?= htmlspecialchars($content) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><?= $id ? 'Update' : 'Add' ?> Report</button>
        <a href="weekly_reports.php" class="btn btn-secondary">Back to Reports</a>
    </form>
</div>
