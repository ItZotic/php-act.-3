<?php
session_start();
$loggedIn = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Portfolio Home</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="page-shell">
    <header class="site-header">
      <div class="brand">Turquoise Portfolio</div>
      <nav class="site-nav">
        <a href="index.php">Home</a>
        <?php if ($loggedIn): ?>
          <a href="dashboard.php">Dashboard</a>
          <a href="portfolio.php">Private Resume</a>
          <a href="public_resume.php?id=<?php echo urlencode($_SESSION['user_id']); ?>" target="_blank">Public View</a>
          <a href="logout.php">Logout</a>
        <?php else: ?>
          <a href="login.php">Login</a>
          <a href="signup.php">Sign Up</a>
        <?php endif; ?>
      </nav>
    </header>

    <main>
      <section class="card hero-card">
        <?php if ($loggedIn): ?>
          <h1 class="page-title">Welcome back, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
          <p class="subtitle">Continue polishing your resume, keep your story up to date, and share a turquoise-inspired public profile with one click.</p>
          <div class="button-row">
            <a class="btn" href="dashboard.php">Go to Dashboard</a>
            <a class="btn outline" href="portfolio.php">View Private Resume</a>
            <a class="btn ghost" href="public_resume.php?id=<?php echo urlencode($_SESSION['user_id']); ?>" target="_blank">Open Public Resume</a>
          </div>
        <?php else: ?>
          <h1 class="page-title">A Minimal Portfolio with Turquoise Personality</h1>
          <p class="subtitle">Build a sleek, distraction-free resume hub. Manage your information privately and share a polished public view when you&rsquo;re ready.</p>
          <div class="button-row">
            <a class="btn" href="login.php">Login</a>
            <a class="btn outline" href="signup.php">Create an Account</a>
          </div>
        <?php endif; ?>
      </section>
    </main>
  </div>
</body>
</html>
