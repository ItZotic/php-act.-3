<?php
session_start();

$host = "localhost";
$db   = "portfolio_db";
$user = "postgres";
$pass = "1234567890";

$error = "";
$success = "";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        if ($username === "" || $password === "") {
            $error = "All fields are required.";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute(["username" => $username]);

            if ($stmt->fetch()) {
                $error = "Username already taken.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, email, phone, skills, education, bio) VALUES (:username, :password, '', '', '', '', '', '')");
                $stmt->execute([
                    "username" => $username,
                    "password" => $hashedPassword
                ]);

                header("Location: login.php");
                exit;
            }
        }
    }
} catch (PDOException $e) {
    $error = "Database connection failed: " . $e->getMessage();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="portfolio-stage">
    <div class="portfolio-frame">
      <aside class="side-panel">
        <div>
          <div class="panel-title">simple portfolio</div>
          <p class="panel-subtitle">Start your turquoise portfolio and unlock a shareable public resume.</p>
        </div>
        <nav class="panel-nav">
          <a class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" href="index.php">home</a>
          <a href="login.php">login</a>
          <a class="<?php echo $currentPage === 'signup.php' ? 'active' : ''; ?>" href="signup.php">sign up</a>
        </nav>
        <div class="panel-footer">
          <p>Already registered? Log in to keep your resume fresh and aligned.</p>
          <a class="panel-cta" href="login.php">back to login</a>
        </div>
      </aside>

      <main class="content-area">
        <div class="content-card narrow">
          <div>
            <h1 class="page-title">Create your account</h1>
            <p class="lead-text">Sign up to manage your resume in private and instantly publish a polished public version.</p>
          </div>

          <?php if($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <form action="signup.php" method="POST" class="form-grid">
            <div class="field">
              <label for="username">Username</label>
              <input type="text" id="username" name="username" required>
            </div>

            <div class="field">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">sign up</button>
          </form>

          <div class="form-actions">
            <a class="btn outline" href="login.php">already have an account?</a>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>
