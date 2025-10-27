<?php
$host = "localhost";
$db   = "portfolio_db";
$user = "postgres";
$pass = "1234567890";

$userId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$userData = null;
$error = '';

if ($userId <= 0) {
    $error = 'Invalid resume link.';
} else {
    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT username, full_name, email, phone, skills, education, bio FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            $error = 'The requested resume could not be found.';
        }
    } catch (PDOException $e) {
        $error = 'Unable to load resume: ' . $e->getMessage();
    }
}

$displayName = '';
$initial = '';
if ($userData) {
    $displayName = $userData['full_name'] ?: $userData['username'];
    if ($displayName !== '') {
        $initialChar = function_exists('mb_substr') ? mb_substr($displayName, 0, 1) : substr($displayName, 0, 1);
        $initial = strtoupper($initialChar);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Public Resume</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="portfolio-stage">
    <div class="portfolio-frame">
      <aside class="side-panel">
        <div>
          <div class="panel-title">simple portfolio</div>
          <p class="panel-subtitle">A turquoise resume that anyone can view without logging in.</p>
        </div>
        <nav class="panel-nav">
          <a href="index.php">home</a>
          <a href="login.php">login</a>
          <a href="signup.php">sign up</a>
        </nav>
        <div class="panel-footer">
          <p>Want a page like this? Create an account and tailor your own turquoise profile.</p>
          <a class="panel-cta" href="signup.php">build yours</a>
        </div>
      </aside>

      <main class="content-area">
        <div class="content-card resume-card">
          <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
          <?php else: ?>
            <div class="resume-headline">
              <div class="avatar"><?php echo htmlspecialchars($initial); ?></div>
              <h1><?php echo htmlspecialchars($displayName); ?></h1>
              <p class="role"><?php echo htmlspecialchars($userData['bio']); ?></p>
              <div class="contact-bar">
                <span><?php echo htmlspecialchars($userData['email']); ?></span>
                <span><?php echo htmlspecialchars($userData['phone']); ?></span>
              </div>
            </div>

            <div class="resume-body">
              <div class="info-card highlight">
                <h3>About</h3>
                <p><?php echo htmlspecialchars($userData['bio']); ?></p>
              </div>
              <div class="info-card">
                <h3>Skills</h3>
                <p><?php echo htmlspecialchars($userData['skills']); ?></p>
              </div>
              <div class="info-card">
                <h3>Education</h3>
                <p><?php echo htmlspecialchars($userData['education']); ?></p>
              </div>
            </div>

            <div class="resume-actions">
              <a class="btn outline" href="login.php">login to edit</a>
            </div>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>
</body>
</html>
