<?php
session_start();

$host = "localhost";
$db   = "portfolio_db";
$user = "postgres";
$pass = "1234567890";

$error = "";

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
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["loggedin"] = true;
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Incorrect username or password.";
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
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="portfolio-stage">
    <div class="portfolio-frame">
      <aside class="side-panel">
        <div>
          <div class="panel-title">simple portfolio</div>
          <p class="panel-subtitle">Return to your turquoise dashboard and update your resume instantly.</p>
        </div>
        <nav class="panel-nav">
          <a class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" href="index.php">home</a>
          <a class="<?php echo $currentPage === 'login.php' ? 'active' : ''; ?>" href="login.php">login</a>
          <a href="signup.php">sign up</a>
        </nav>
        <div class="panel-footer">
          <p>Need an account? Create one in seconds and publish a sleek public resume.</p>
          <a class="panel-cta" href="signup.php">create account</a>
        </div>
      </aside>

      <main class="content-area">
        <div class="content-card narrow">
          <div>
            <h1 class="page-title">Welcome back</h1>
            <p class="lead-text">Log in to access your dashboard, fine-tune your profile, and keep every section in sync.</p>
          </div>

          <?php if($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <form action="login.php" method="POST" class="form-grid">
            <div class="field">
              <label for="username">Username</label>
              <input type="text" id="username" name="username" required>
            </div>

            <div class="field">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">login</button>
          </form>

          <div class="form-actions">
            <a class="btn outline" href="signup.php">create an account</a>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>
