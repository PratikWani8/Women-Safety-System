<?php
include("../config/db.php");
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
$message = "";
if (isset($_POST['change'])) {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];
    $res = $conn->query("SELECT password FROM admin WHERE username='admin'");
    $admin = $res->fetch_assoc();
    if (!password_verify($current, $admin['password'])) {
        $message = "❌ Current password is incorrect";
    }
    elseif ($new !== $confirm) {
        $message = "❌ New passwords do not match";
    }
    elseif (strlen($new) < 6) {
        $message = "❌ Password must be at least 6 characters";
    }
    else {
        $newHash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admin SET password=? WHERE username='admin'");
        $stmt->bind_param("s", $newHash);
        $stmt->execute();
        $message = "✅ Password changed successfully";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Admin Password - Raksha</title>
    <link rel="stylesheet" href="../style.css?v=3">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #change-btn {
  width: 100%;
  padding: 12px;
  margin-top: 15px;
  border: none;
  background: var(--danger);
  color: white;
  font-size: 16px;
  border-radius: 25px;
  cursor: pointer;
  transition: transform 0.2s ease, background 0.3s;
}

#change-btn:hover {
  background: #28a745;
  transform: scale(1.03);
}
</style>
</head>
<body>
<header>
    <div class="header-container">
    <h2>🔐 Change Admin Password</h2>
    </div>
</header>
<div class="card">
<form method="post">
    <?php if($message): ?>
        <p style="text-align:center;"><b><?php echo $message; ?></b></p>
    <?php endif; ?>
    <label>Current Password</label>
    <input type="password" name="current" required>
    <label>New Password</label>
    <input type="password" name="new" required>
    <label>Confirm New Password</label>
    <input type="password" name="confirm" required>
    <button name="change" id="change-btn">Change Password</button>
    <a href="dashboard.php">
        <button type="button">⬅ Back to Dashboard</button>
    </a>
</form>
</div>
</body>
</html>
