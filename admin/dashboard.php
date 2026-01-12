<?php
include("../config/db.php");
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="admin_dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="dashboard">
<aside class="sidebar">
    <h2 class="logo">Admin Panel</h2>
    <nav>
        <a class="active" href="dashboard.php">📊 Dashboard</a>
        <a href="sos_panel.php">🚨 SOS Alerts</a>
        <a href="change_password.php">🔐 Change Password</a>
        <a href="../auth/logout.php" class="logout">🚪 Logout</a>
    </nav>
</aside>
<main class="main-content">
<header class="topbar">
    <h1>Complaint Dashboard</h1>
</header>
<?php
$total     = $conn->query("SELECT COUNT(*) c FROM complaints")->fetch_assoc()['c'];
$pending   = $conn->query("SELECT COUNT(*) c FROM complaints WHERE status='Pending'")->fetch_assoc()['c'];
$progress  = $conn->query("SELECT COUNT(*) c FROM complaints WHERE status='In Progress'")->fetch_assoc()['c'];
$resolved  = $conn->query("SELECT COUNT(*) c FROM complaints WHERE status='Resolved'")->fetch_assoc()['c'];
?>
<section class="stats">
    <div class="stat-card total">
        <h2 class="counter" data-target="<?php echo $total; ?>">0</h2>
        <p>Total</p>
    </div>
    <div class="stat-card pending">
        <h2 class="counter" data-target="<?php echo $pending; ?>">0</h2>
        <p>Pending</p>
    </div>
    <div class="stat-card progress">
        <h2 class="counter" data-target="<?php echo $progress; ?>">0</h2>
        <p>In Progress</p>
    </div>
    <div class="stat-card resolved">
        <h2 class="counter" data-target="<?php echo $resolved; ?>">0</h2>
        <p>Resolved</p>
    </div>
</section>
<section class="charts-row">
    <div class="card chart-card">
        <h3>Complaints Distribution</h3>
        <canvas id="barChart"></canvas>
    </div>
    <div class="card chart-card small-chart">
        <h3>Status Overview</h3>
        <canvas id="statusChart"></canvas>
    </div>
</section>
<section class="card">
<h3>All Complaints</h3>
<?php
$res = $conn->query("
    SELECT complaints.*, users.name AS accused_name
    FROM complaints
    JOIN users ON complaints.user_id = users.user_id
    ORDER BY complaints.reported_at DESC
");

if ($res->num_rows === 0) echo "<p>No complaints found.</p>";
while ($row = $res->fetch_assoc()) {
?>
<div class="complaint">
    <div>
        <b>ID:</b> <?php echo $row['complaint_id']; ?><br>
        <b>Accused:</b>
        <?php echo htmlspecialchars($row['accused_name']); ?><br>
        <b>Type:</b> <?php echo $row['incident_type']; ?><br>
        <b>Status:</b> <span class="status <?php echo strtolower(str_replace(' ','',$row['status'])); ?>">
            <?php echo $row['status']; ?>
        </span><br>
        <small><?php echo $row['reported_at']; ?></small>
    </div>
    <div class="actions">
        <?php if (!empty($row['evidence'])) { ?>
            <a href="../uploads/<?php echo $row['evidence']; ?>" target="_blank">View Evidence</a>
        <?php } ?>
        <a href="update_status.php?id=<?php echo $row['complaint_id']; ?>">
            <button>Update</button>
        </a>
    </div>
</div>
<?php } ?>
</section>
</main>
</div>
<script>
document.querySelectorAll(".counter").forEach(counter => {
    let target = +counter.dataset.target;
    let count = 0;
    let step = Math.ceil(target / 60);
    let update = () => {
        count += step;
        if (count > target) count = target;
        counter.innerText = count;
        if (count < target) requestAnimationFrame(update);
    };
    update();
});
const total = <?php echo $total; ?>;
const dataVals = [
    <?php echo $pending; ?>,
    <?php echo $progress; ?>,
    <?php echo $resolved; ?>
];
const barPercentPlugin = {
    id: 'barPercent',
    afterDatasetsDraw(chart) {
        const {ctx} = chart;
        chart.getDatasetMeta(0).data.forEach((bar, i) => {
            let val = chart.data.datasets[0].data[i];
            if (!val) return;
            let percent = total ? ((val / total) * 100).toFixed(1) + "%" : "0%";
            ctx.fillStyle = "#fff";
            ctx.font = "bold 13px Segoe UI";
            ctx.textAlign = "center";
            ctx.fillText(percent, bar.x, bar.y + 20);
        });
    }
};
const doughnutCenterText = {
    id: 'centerText',
    afterDraw(chart) {
        const { ctx } = chart;
        const dataset = chart.data.datasets[0];
        const sum = dataset.data.reduce((a, b) => a + b, 0);
        if (!sum) return;
        const maxIndex = dataset.data.indexOf(Math.max(...dataset.data));
        const percent = ((dataset.data[maxIndex] / sum) * 100).toFixed(1) + "%";
        const label = chart.data.labels[maxIndex];
        const { width, height } = chart;
        ctx.save();
        ctx.font = "bold 26px Segoe UI";
        ctx.fillStyle = "#333";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        ctx.fillText(percent, width / 2, height / 2 - 8);
        ctx.font = "14px Segoe UI";
        ctx.fillStyle = "#777";
        ctx.fillText(label, width / 2, height / 2 + 18);
        ctx.restore();
    }
};
new Chart(barChart, {
    type: "bar",
    data: {
        labels: ["Pending","In Progress","Resolved"],
        datasets: [{
            data: dataVals,
            backgroundColor: ["#ff9800","#03a9f4","#4caf50"],
            borderRadius: 8
        }]
    },
    options: {
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { display:false } }
    },
    plugins: [barPercentPlugin]
});
new Chart(statusChart, {
    type: "doughnut",
    data: {
        labels: ["Pending","In Progress","Resolved"],
        datasets: [{
            data: dataVals,
            backgroundColor: ["#ff9800","#03a9f4","#4caf50"]
        }]
    },
    options: {
        cutout: "70%",
        plugins: {
            legend: { position: "bottom" }
        }
    },
    plugins: [doughnutCenterText]
});
</script>
</body>
</html>
