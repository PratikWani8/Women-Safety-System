<?php
include("../config/db.php");
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    $name  = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = preg_replace('/[^0-9]/', '', $_POST['phone']);
    $pass  = $_POST['password'];
    $cpass = $_POST['confirm_password'];
    if (strlen($pass) < 6) {
        echo "<script>alert('Password must be at least 6 characters');</script>";
        exit;
    }
    if ($pass !== $cpass) {
        echo "<script>alert('Passwords do not match');</script>";
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address');</script>";
        exit;
    }
    if (strlen($phone) !== 10) {
        echo "<script>alert('Phone number must be 10 digits');</script>";
        exit;
    }
    $check = $conn->prepare("SELECT 1 FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        echo "<script>alert('Email already registered');</script>";
        exit;
    }
    $check->close();
    $password = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $conn->prepare(
        "INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssss", $name, $email, $phone, $password);
    if ($stmt->execute()) {
        echo "<script>
            alert('Registration successful');
            window.location.href='login.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('Registration failed');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<header>
    <div class="header-container">
    <h2>📝 User Registration</h2>
    </div>
</header>
<div class="card">
    <form method="post" autocomplete="off">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="Enter full name" required>
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter email" required>
        <label>Phone</label>
        <input type="text" name="phone" pattern="[0-9]{10}" placeholder="10 digit number" required>
        <label>Password</label>
        <input type="password" name="password" placeholder="Min 6 characters" required>
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Re-enter password" required>
        <button type="submit" name="register">Register</button>
        <p style="text-align:center; margin-top:15px;">
            Already registered? <a href="login.php" style="text-decoration: none;">Login</a>
        </p>
    </form>
</div>
<script>
document.querySelector("form").addEventListener("submit", () => {
    const btn = document.querySelector("button");
    btn.innerText = "Registering...";
    btn.style.opacity = "0.8";
});
</script>
</body>
</html>
