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

                $success = "Account created! You can now log in.";
                header("Location: login.php");
                exit;
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
  <title>Sign Up</title>
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
      <section class="card form-card">
        <div>
          <h1 class="page-title">Create your account</h1>
          <p class="subtitle">Sign up to start crafting a clean, minimal resume that shines in turquoise.</p>
        </div>

        <?php if($error): ?>
          <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if($success): ?>
          <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
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

          <button type="submit">Sign Up</button>
        </form>

        <div class="actions">
          <a class="btn outline" href="login.php">Already have an account?</a>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
