<?php
include("../config/db.php");
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SOS Alerts</title>
    <link rel="stylesheet" href="../style.css?v=6">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .sos-alert {
            border-left: 6px solid #d32f2f;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            50% { background: #fff1f1; }
        }
    </style>
</head>
<body>
<header>
    <h2>🚨 SOS Alert Panel (Live)</h2>
</header>
<div class="card">
    <h3>Active SOS Alerts</h3>
    <div id="sosContainer">
        <p>Loading SOS alerts...</p>
    </div>
    <a href="sos_map.php">
        <button class="danger">🗺 View SOS Map</button>
    </a>
    <a href="dashboard.php">
        <button>⬅ Back to Dashboard</button>
    </a>
</div>
<script>
let lastCount = 0;
function loadSOS() {
    fetch("fetch_sos.php")
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById("sosContainer");
            container.innerHTML = "";
            if (data.length === 0) {
                container.innerHTML = "<p>No SOS alerts found.</p>";
                return;
            }
            if (data.length > lastCount && lastCount !== 0) {
                alert("🚨 NEW SOS ALERT RECEIVED!");
            }
            lastCount = data.length;
            data.forEach(s => {
                container.innerHTML += `
                    <div class="card sos-alert">
                        <p>
                            <b>User:</b> ${s.name}<br>
                            <b>Phone:</b> ${s.phone}<br>
                            <b>Message:</b> ${s.message}<br>
                            <b>Location:</b> ${s.location}<br>
                            <small>${s.sent_at}</small>
                        </p>
                    </div>
                `;
            });
        });
}
loadSOS();
setInterval(loadSOS, 5000);
</script>
</body>
</html>
