<?php
include("../config/db.php");
if (!isset($_SESSION['admin'])) {
    exit;
}
$res = $conn->query("
    SELECT s.*, u.name, u.phone
    FROM emergency_sos s
    JOIN users u ON s.user_id = u.user_id
    ORDER BY s.sent_at DESC
");
$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);