<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Portfolio</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>My Portfolio</h1>
    <p>Welcome to my personal portfolio showcasing my academic journey, skills, and projects.</p>
  </header>

  <div class="home-container">
    <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
      <h2>Hello, <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
      <a href="portfolio.php" class="btn">Go to Portfolio</a>
      <a href="logout.php" class="logout-btn">Logout</a>
    <?php else: ?>
      <h2>Access My Portfolio</h2>
      <p>Please log in or sign up to view my resume and projects.</p>
      <a href="login.php" class="btn">Login</a>
      <a href="signup.php" class="btn secondary">Sign Up</a>
    <?php endif; ?>
  </div>
</body>
</html>
