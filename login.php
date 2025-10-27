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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="page-shell">
    <header class="site-header">
      <div class="brand">Turquoise Portfolio</div>
      <nav class="site-nav">
        <a href="index.php">Home</a>
        <a href="signup.php">Sign Up</a>
      </nav>
    </header>

    <main>
      <section class="card form-card">
        <div>
          <h1 class="page-title">Welcome back</h1>
          <p class="subtitle">Log in to access your dashboard and keep your turquoise resume up to date.</p>
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

          <button type="submit">Login</button>
        </form>

        <div class="actions">
          <a class="btn outline" href="signup.php">Create an account</a>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
