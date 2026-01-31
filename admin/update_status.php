<?php
include("../config/db.php");
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit;
}
$id = $_GET['id'];
if(isset($_POST['update'])){
    $status = $_POST['status'];
    $conn->query(
        "UPDATE complaints SET status='$status' WHERE complaint_id=$id"
    );
    $res = $conn->query(
        "SELECT u.phone FROM users u 
         JOIN complaints c ON u.user_id=c.user_id 
         WHERE c.complaint_id=$id"
    );
    $user = $res->fetch_assoc();
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Status</title>
    <link rel="stylesheet" href="../style.css?v=3">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header>
    <div class="header-container">
    <h2>🔄 Update Complaint Status</h2>
    </div>
</header>
<div class="card">
<form method="post">
    <label>Status</label>
    <select name="status">
        <option>Pending</option>
        <option>In Progress</option>
        <option>Resolved</option>
    </select>
    <button name="update">Update</button>
</form>
</div>
</body>
</html>
