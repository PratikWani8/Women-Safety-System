<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <title>Raksha - Women Safety</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="index.css" />
</head>
<body>

  <!-- Alert Banner -->
  <div class="top-banner">
    <p>🚨 Emergency Helpline: 112 | Women Helpline: 181</p>
    <a href="https://www.digitalindia.gov.in/initiative/ncw-womens-helpline">
    <button>Get Help</button>
    </a>
  </div>

  <!-- Navbar -->
  <header>
    <div class="nav-container">

      <div class="logo">Raksha</div>

      <nav>
        <a href="index.php">Home</a>
        <a href="user/safety.php">Safety Tips</a>
        <a href="#">Report</a>
        <a href="auth/register.php">User</a>
        <a href="admin/admin_login.php">Admin</a>
      </nav>

      <a href="auth/register.php">
      <button class="start-btn">Start Protection</button>
    </a>

    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">

  <div class="hero-left">

    <div class="badge">
      💖 Trusted by 10,000+ Women
    </div>

    <h1>
      Your Personal Safety <br>
      <span>Protection</span><br>
      Companion
    </h1>

    <p>
      A smart platform for women to report safety incidents, send emergency SOS alerts,
        and track complaint status securely.
    </p>

    <div class="hero-buttons">
      <a href="auth/register.php">
      <button class="primary-btn">Start ➞</button>
      </a>
      <a href="user/safety.php">
      <button class="secondary-btn">Safety Guide</button>
      </a>
    </div>
    </div>

     <div class="hero-right">
    <img src="assets/hero_img.png" alt="Website Preview">
    </div>

  </section>

  <footer style="text-align:center; padding:15px; color:#666; font-family: 'Segoe UI', sans-serif;">
    © <?php echo date("Y"); ?> Raksha - Women Safety System | Designed for Safety • Security • Empowerment for Women | All Rights Reserved.
</footer>

<script>
  const links = document.querySelectorAll("nav a");
  const currentPage = window.location.pathname.split("/").pop();

  links.forEach(link => {
    if (link.getAttribute("href") === currentPage) {
      link.classList.add("active");
    }
  });
</script>

</body>
</html>
