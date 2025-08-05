<?php
require 'includes/db.php';

$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET password = ?, invite_token = NULL WHERE invite_token = ?");
    $stmt->execute([$password, $token]);
    echo "Password set. <a href='index.php'>Login</a>";
} else {
?>
<form method="POST">
  <input type="password" name="password" required placeholder="New Password">
  <button type="submit">Set Password</button>
</form>
<?php } ?>
