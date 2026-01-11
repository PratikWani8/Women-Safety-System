<?php
include("../config/db.php");
if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../style.css?v=2">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header>
    <h2>👩 User Dashboard</h2>
</header>
<div class="card">
    <p>Welcome! Choose an action below:</p>

    <a href="report_complaint.php"><button>🚨 Report Complaint</button></a>
    <a href="send_sos.php"><button class="danger">📲 Send SOS</button></a>
    <a href="view_status.php"><button>📄 View Complaint Status</button></a>
    <a href="../auth/logout.php"><button>🚪 Logout</button></a>
</div>
</body>
</html>
