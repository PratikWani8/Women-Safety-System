<?php
include("../config/db.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT name FROM users WHERE user_id = '$user_id'");
$user = $result->fetch_assoc();
$username = $user['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - Raksha</title>
    <link rel="stylesheet" href="../style.css?v=4">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header>
    <div class="header-container">
    <h2>👩 User Dashboard</h2>
    </div>
</header>
<div class="card">
    <p>
        Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>!  
        Choose an action below:
    </p>
    <a href="report_complaint.php"><button>🚨 Report Complaint</button></a>
    <a href="send_sos.php"><button class="danger">📲 Send SOS</button></a>
    <a href="view_status.php"><button>📄 View Complaint Status</button></a>
    <a href="../auth/logout.php"><button>🚪 Logout</button></a>
</div>
</body>
</html>
