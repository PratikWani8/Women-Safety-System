<?php
include("../config/db.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
$success = false;
if (isset($_POST['send'])) {

    $location = $_POST['location'];
    $message  = $_POST['msg'];

    $stmt = $conn->prepare(
        "INSERT INTO emergency_sos (user_id, location, message)
         VALUES (?, ?, ?)"
    );
    $stmt->bind_param("iss", $_SESSION['user_id'], $location, $message);
    $stmt->execute();

    $success = true;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send SOS</title>
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .pulse {
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255,77,77,0.6); }
            70% { box-shadow: 0 0 0 15px rgba(255,77,77,0); }
            100% { box-shadow: 0 0 0 0 rgba(255,77,77,0); }
        }
    </style>
</head>
<body>
<header>
    <div class="header-container">
    <h2>🚨 Emergency SOS</h2>
    </div>
</header>
<div class="card">
<?php if ($success): ?>
    <script>
        alert("🚨 SOS ALERT SENT!\nAuthorities have been notified through the system.");
    </script>
    <p style="text-align:center; font-weight:bold; color:#d32f2f;">
        🚨 SOS Sent Successfully
    </p>
<?php endif; ?>
<form method="post">
    <input type="hidden" name="location" id="location">
    <label>Emergency Message</label>
    <textarea name="msg" rows="4" required>Help! I am in danger.</textarea>
    <button type="button" onclick="getLocation()">📍 Capture Live Location</button>
    <button class="danger pulse" name="send">
        🚨 SEND SOS
    </button>
</form>
<a href="dashboard.php">
    <button>⬅ Back to Dashboard</button>
</a>
</div>
<script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (pos) {
                document.getElementById("location").value =
                    pos.coords.latitude + "," + pos.coords.longitude;
                alert("📍 Location captured successfully");
            },
            function () {
                alert("❌ Location permission denied");
            }
        );
    } else {
        alert("Geolocation not supported");
    }
}
</script>
</body>
</html>
