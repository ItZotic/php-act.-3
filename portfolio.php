<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$db   = "portfolio_db";
$user = "postgres";
$pass = "1234567890";

$error = '';
$userData = [];

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT username, full_name, email, phone, skills, education, bio FROM users WHERE id = :id");
    $stmt->execute(["id" => $_SESSION["user_id"]]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        throw new RuntimeException("Unable to load your resume details.");
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$displayName = $userData['full_name'] ?? '';
if ($displayName === '') {
    $displayName = $userData['username'] ?? '';
}
$initial = '';
if ($displayName !== '') {
    $initialChar = function_exists('mb_substr') ? mb_substr($displayName, 0, 1) : substr($displayName, 0, 1);
    $initial = strtoupper($initialChar);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Private Resume</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="portfolio-stage">
    <div class="portfolio-frame">
      <aside class="side-panel">
        <div>
          <div class="panel-title">simple portfolio</div>
          <p class="panel-subtitle">Preview your resume exactly as it appears before sharing the public link.</p>
        </div>
        <nav class="panel-nav">
          <a class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" href="index.php">home</a>
          <a class="<?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">dashboard</a>
          <a class="<?php echo $currentPage === 'portfolio.php' ? 'active' : ''; ?>" href="portfolio.php">private resume</a>
          <a href="public_resume.php?id=<?php echo urlencode($_SESSION['user_id']); ?>" target="_blank">public view</a>
          <a href="logout.php">logout</a>
        </nav>
        <div class="panel-footer">
          <p>Everything look right? Share the turquoise-styled public version in seconds.</p>
          <a class="panel-cta" href="public_resume.php?id=<?php echo urlencode($_SESSION['user_id']); ?>" target="_blank">share public link</a>
        </div>
      </aside>

      <main class="content-area">
        <div class="content-card resume-card">
          <?php if (!empty($error)): ?>
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
              <a class="btn" href="dashboard.php">edit information</a>
              <a class="btn outline" href="public_resume.php?id=<?php echo urlencode($_SESSION['user_id']); ?>" target="_blank">open public resume</a>
              <a class="btn ghost" href="logout.php">logout</a>
            </div>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>
</body>
</html>
