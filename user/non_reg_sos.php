<?php
include("../config/db.php");

$success = false;

if (isset($_POST['send'])) {

    $location = $_POST['location'];
    $message  = $_POST['msg'];

    $stmt = $conn->prepare(
        "INSERT INTO non_reg_sos (location, message)
         VALUES (?, ?)"
    );

    $stmt->bind_param("ss", $location, $message);
    $stmt->execute();

    $success = true;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Emergency SOS (Guest) - Raksha</title>
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<header>
<div class="header-container">
<h2>🚨 Emergency SOS (Without Login)</h2>
</div>
</header>

<div class="card">

<?php if ($success): ?>
<script>
alert("🚨 SOS SENT SUCCESSFULLY!");
</script>
<?php endif; ?>

<form method="post">

<input type="hidden" name="location" id="location">

<label>Emergency Message</label>
<textarea name="msg" required>Help! I am in danger.</textarea>

<button type="button" onclick="getLocation()">📍 Capture Location</button>

<button class="danger" name="send">
🚨 SEND SOS
</button>

</form>

</div>

<script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (pos) {
                document.getElementById("location").value =
                    pos.coords.latitude + "," + pos.coords.longitude;

                alert("📍 Location Captured");
            },
            function () {
                alert("❌ Permission Denied");
            }
        );
    }
}
</script>

</body>
</html>
