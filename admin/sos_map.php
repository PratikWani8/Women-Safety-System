<?php
include("../config/db.php");
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
$data = [];
$res = $conn->query("SELECT * FROM emergency_sos");
while ($row = $res->fetch_assoc()) {
    if ($row['location']) {
        [$lat, $lng] = explode(",", $row['location']);
        $data[] = [
            "lat" => $lat,
            "lng" => $lng,
            "msg" => $row['message'],
            "time" => $row['sent_at']
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SOS Map View</title>
    <link rel="stylesheet" href="../style.css?v=4">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <style>
        #map {
            height: 350px;
            width: 100%;
            border-radius: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<header>
    <h2>🗺 SOS Location Map</h2>
</header>
<div class="card">
    <div id="map"></div>
    <a href="sos_panel.php">
        <button>⬅ Back to SOS Panel</button>
    </a>
</div>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
const sosData = <?php echo json_encode($data); ?>;
const map = L.map('map').setView([20.5937, 78.9629], 5);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© Map'
}).addTo(map);
sosData.forEach(s => {
    L.marker([s.lat, s.lng]).addTo(map)
        .bindPopup(
            `<b>🚨 SOS Alert</b><br>
             ${s.msg}<br>
             <small>${s.time}</small>`
        );
});
</script>
</body>
</html>
