<?php
include("../config/db.php"); 
if (isset($_POST['login'])) {
    $u = trim($_POST['username']);
    $p = $_POST['password'];
    if (empty($u) || empty($p)) {
        echo "<script>alert('All fields are required');</script>";
    }
    else {
        $res = $conn->query("SELECT * FROM admin WHERE username='$u'");

        if ($res && $res->num_rows === 1) {
            $a = $res->fetch_assoc();

            if (password_verify($p, $a['password'])) {
                $_SESSION['admin'] = true;
                header("Location: dashboard.php");
                exit;
            } else {
                echo "<script>alert('Invalid password');</script>";
            }
        } else {
            echo "<script>alert('Invalid admin login');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../style.css?v=2">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header>
    <h2>👮 Admin Login</h2>
</header>
<div class="card">
<form method="post" autocomplete="off">
    <label>Username</label>
    <input type="text" name="username" placeholder="Enter username" required>
    <label>Password</label>
    <input type="password" name="password" placeholder="Enter password" required>
    <button class="danger" type="submit" name="login">Login</button>
</form>
</div>
<script>
document.querySelector("form").addEventListener("submit", () => {
    const btn = document.querySelector("button");
    btn.innerText = "Logging in...";
    btn.style.opacity = "0.8";
});
</script>
</body>
</html>
