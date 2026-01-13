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
    <title>Complaint Status</title>
    <link rel="stylesheet" href="../style.css?v=2">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header>
    <h2>📄 Complaint Status</h2>
</header>
<div class="card">
<?php
$res = $conn->query(
    "SELECT * FROM complaints WHERE user_id=".$_SESSION['user_id']
);
if($res->num_rows == 0){
    echo "<p>No complaints submitted yet.</p>";
}
while($row = $res->fetch_assoc()){
    echo "
    <p>
        <strong>{$row['incident_type']}</strong><br>
        Status: <b>{$row['status']}</b><br>
        Date: {$row['reported_at']}
    </p>
    <hr>";
}
?>
<a href="dashboard.php"><button>⬅ Back</button></a>
</div>
</body>
</html>
