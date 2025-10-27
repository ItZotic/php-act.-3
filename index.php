<?php
session_start();
$loggedIn = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Portfolio Home</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="portfolio-stage">
    <div class="portfolio-frame">
      <aside class="side-panel">
        <div>
          <div class="panel-title">simple portfolio</div>
          <p class="panel-subtitle">Craft a crisp turquoise profile and keep every section consistent.</p>
        </div>
        <nav class="panel-nav">
          <a class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" href="index.php">home</a>
          <?php if ($loggedIn): ?>
            <a class="<?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">dashboard</a>
            <a class="<?php echo $currentPage === 'portfolio.php' ? 'active' : ''; ?>" href="portfolio.php">private resume</a>
            <a href="public_resume.php?id=<?php echo urlencode($_SESSION['user_id']); ?>" target="_blank">public view</a>
            <a href="logout.php">logout</a>
          <?php else: ?>
            <a class="<?php echo $currentPage === 'login.php' ? 'active' : ''; ?>" href="login.php">login</a>
            <a class="<?php echo $currentPage === 'signup.php' ? 'active' : ''; ?>" href="signup.php">sign up</a>
          <?php endif; ?>
        </nav>
        <?php if ($loggedIn): ?>
          <div class="panel-footer">
            <p>Share your resume link anytime without leaving this minimal dashboard.</p>
            <a class="panel-cta" href="public_resume.php?id=<?php echo urlencode($_SESSION['user_id']); ?>" target="_blank">copy public link</a>
          </div>
        <?php else: ?>
          <div class="panel-footer">
            <p>Sign in or create an account to unlock editing tools and a shareable resume.</p>
            <a class="panel-cta" href="login.php">get started</a>
          </div>
        <?php endif; ?>
      </aside>

      <main class="content-area">
        <div class="content-card hero-card">
          <?php if ($loggedIn): ?>
            <h1 class="page-title">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>.</h1>
            <p class="lead-text">Keep refining your turquoise portfolio. Update your details privately, then share the polished public version whenever you are ready.</p>
            <div class="button-row">
              <a class="btn" href="dashboard.php">open dashboard</a>
              <a class="btn outline" href="portfolio.php">view private resume</a>
              <a class="btn ghost" href="public_resume.php?id=<?php echo urlencode($_SESSION['user_id']); ?>" target="_blank">open public resume</a>
            </div>
          <?php else: ?>
            <h1 class="page-title">A clean turquoise showcase for your story.</h1>
            <p class="lead-text">Build a simple two-column resume experience: manage your information behind the scenes and publish a read-only profile for recruiters and friends.</p>
            <div class="button-row">
              <a class="btn" href="login.php">login</a>
              <a class="btn outline" href="signup.php">create an account</a>
            </div>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>
</body>
</html>
