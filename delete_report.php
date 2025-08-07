<?php
require 'includes/auth.php';
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Delete the report from the database
    $stmt = $pdo->prepare("DELETE FROM weekly_reports WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect back to the same date view if available
    $redirect = 'weekly_reports.php';
    if (isset($_POST['date']) && !empty($_POST['date'])) {
        $redirect .= '?date=' . urlencode($_POST['date']);
    }

    header("Location: $redirect");
    exit;
} else {
    // If not POST or ID missing, redirect back
    header("Location: weekly_reports.php");
    exit;
}
