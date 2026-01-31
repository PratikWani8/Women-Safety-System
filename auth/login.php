<?php
include("../config/db.php"); 
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
        echo "<script>alert('All fields are required');</script>";
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format');</script>";
    }
    else {
        $res = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($res && $res->num_rows === 1) {
            $user = $res->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                header("Location: ../user/dashboard.php");
                exit;
            } else {
                echo "<script>alert('Invalid password');</script>";
            }
        } else {
            echo "<script>alert('Email not registered');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header>
    <div class="header-container">
    <h2>🔐 User Login</h2>
    </div>
</header>
<div class="card">
<form method="post" autocomplete="off">
    <label>Email</label>
    <input type="email" name="email" placeholder="Enter email" required>
    <label>Password</label>
    <input type="password" name="password" placeholder="Enter password" required>
    <button type="submit" name="login">Login</button>
    <p style="text-align:center; margin-top:15px;">
        New user? <a href="register.php" style="text-decoration: none;">Register here</a>
    </p>
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
