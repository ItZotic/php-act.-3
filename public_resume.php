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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Public Resume</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="page-shell">
    <header class="site-header">
      <div class="brand">Turquoise Portfolio</div>
      <nav class="site-nav">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
      </nav>
    </header>

    <main>
      <section class="card">
        <?php if ($error): ?>
          <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php else: ?>
          <div class="resume-layout">
            <div class="resume-header">
              <h2><?php echo htmlspecialchars($userData['full_name']); ?></h2>
              <p><?php echo htmlspecialchars($userData['bio']); ?></p>
              <div class="resume-meta">
                <span><?php echo htmlspecialchars($userData['email']); ?></span>
                <span><?php echo htmlspecialchars($userData['phone']); ?></span>
              </div>
            </div>

            <div class="resume-section">
              <h3>Skills</h3>
              <p><?php echo htmlspecialchars($userData['skills']); ?></p>
            </div>

            <div class="resume-section">
              <h3>Education</h3>
              <p><?php echo htmlspecialchars($userData['education']); ?></p>
            </div>

            <div class="resume-footer">
              <p>Profile owned by <?php echo htmlspecialchars($userData['username']); ?>.</p>
              <a class="btn outline" href="login.php">Login to edit your profile</a>
            </div>
          </div>
        <?php endif; ?>
      </section>
    </main>
  </div>
</body>
</html>
