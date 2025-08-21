<?php
require 'includes/auth.php';
require_once 'includes/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';

if (isset($_POST['submit'])) {
    $platform = $_POST['platform'];
    $title= $_POST['title'];
    $date = $_POST['stat_date'];
    $followers = $_POST['followers'] ?? NULL;
    $likes = $_POST['likes'] ?? NULL;
    $comments = $_POST['comments'] ?? NULL;
    $shares = $_POST['shares'] ?? NULL;
    $posts_count = $_POST['posts_count'] ?? 0;
    $website_views = $_POST['website_views'] ?? NULL;
    $website_clicks = $_POST['website_clicks'] ?? NULL;

    $stmt = $pdo->prepare("
    INSERT INTO marketing_stats 
    (title, platform, stat_date, followers, likes, comments, shares, posts_count, website_views, website_clicks)
    VALUES (:title, :platform, :stat_date, :followers, :likes, :comments, :shares, :posts_count, :website_views, :website_clicks)
    ");


    $stmt->execute([
        ':title' => $title,
        ':platform' => $platform,
        ':stat_date' => $date,
        ':followers' => $followers,
        ':likes' => $likes,
        ':comments' => $comments,
        ':shares' => $shares,
        ':posts_count' => $posts_count,
        ':website_views' => $website_views,
        ':website_clicks' => $website_clicks
    ]);

    $message = "Daily stats added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Daily Marketing Stats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; padding: 30px; }
        .form-container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .form-container h2 { margin-bottom: 20px; color: #00308F; }
        .form-label { font-weight: 500; }
        .submit-btn { background: #00308F; color: white; border: none; padding: 10px 20px; border-radius: 8px; }
        .submit-btn:hover { background: #002366; }
        .message { margin-bottom: 15px; font-weight: 500; color: green; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add Daily Marketing Stats</h2>

    <?php if($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Platform</label>
            <select name="platform" class="form-select" required>
                <option value="Instagram">Instagram</option>
                <option value="Facebook">Facebook</option>
                <option value="TikTok">TikTok</option>
                <option value="LinkedIn">LinkedIn</option>
                <option value="Website">Website</option>
            </select>
        </div>
        
         <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control"  placeholder="Add Short Post Title" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="stat_date" class="form-control" required>
        </div>

        <!-- Social Media Metrics -->
        <div class="mb-3">
            <label class="form-label">Followers</label>
            <input type="number" name="followers" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Likes</label>
            <input type="number" name="likes" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Comments</label>
            <input type="number" name="comments" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Shares</label>
            <input type="number" name="shares" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Posts Count</label>
            <input type="number" name="posts_count" class="form-control">
        </div>

        <!-- Website Metrics -->
        <div class="mb-3">
            <label class="form-label">Website Views</label>
            <input type="number" name="website_views" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Website Clicks</label>
            <input type="number" name="website_clicks" class="form-control">
        </div>

          <!-- Buttons -->
    <div class="d-flex gap-2">
        <button type="submit" name="submit" class="submit-btn">Add Stats</button>
        <a href="marketing_dashboard.php" class="submit-btn" style="background-color: #6c757d;">Back</a>
    </div>
    </form>
</div>

</body>
</html>
