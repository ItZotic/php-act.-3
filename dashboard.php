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

$success = "";
$errors = [];
$userData = [];

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(["id" => $_SESSION["user_id"]]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        throw new RuntimeException("Unable to load your profile details.");
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $fullName = trim($_POST["full_name"] ?? "");
        $email    = trim($_POST["email"] ?? "");
        $phone    = trim($_POST["phone"] ?? "");
        $skills   = trim($_POST["skills"] ?? "");
        $education = trim($_POST["education"] ?? "");
        $bio      = trim($_POST["bio"] ?? "");

        if ($fullName === "") {
            $errors[] = "Full name is required.";
        }

        if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "A valid email address is required.";
        }

        if ($phone === "") {
            $errors[] = "Phone number is required.";
        }

        if ($skills === "") {
            $errors[] = "Please enter at least one skill.";
        }

        if ($education === "") {
            $errors[] = "Education information is required.";
        }

        if ($bio === "") {
            $errors[] = "Bio section cannot be empty.";
        }

        if (!$errors) {
            $update = $pdo->prepare(
                "UPDATE users SET full_name = :full_name, email = :email, phone = :phone, skills = :skills, education = :education, bio = :bio WHERE id = :id"
            );
            $update->execute([
                "full_name" => $fullName,
                "email" => $email,
                "phone" => $phone,
                "skills" => $skills,
                "education" => $education,
                "bio" => $bio,
                "id" => $_SESSION["user_id"]
            ]);

            $success = "Your resume has been updated.";

            $stmt->execute(["id" => $_SESSION["user_id"]]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="portfolio-stage">
    <div class="portfolio-frame">
      <aside class="side-panel">
        <div>
          <div class="panel-title">simple portfolio</div>
          <p class="panel-subtitle">Manage every detail of your turquoise resume before sharing it with the world.</p>
        </div>
        <nav class="panel-nav">
          <a class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" href="index.php">home</a>
          <a class="<?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">dashboard</a>
          <a class="<?php echo $currentPage === 'portfolio.php' ? 'active' : ''; ?>" href="portfolio.php">private resume</a>
          <a href="public_resume.php?id=<?php echo urlencode($userData["id"]); ?>" target="_blank">public view</a>
          <a href="logout.php">logout</a>
        </nav>
        <div class="panel-footer">
          <p>Need to share quickly? Use the public link below for a recruiter-friendly snapshot.</p>
          <a class="panel-cta" href="public_resume.php?id=<?php echo urlencode($userData["id"]); ?>" target="_blank">public resume</a>
        </div>
      </aside>

      <main class="content-area">
        <div class="content-card">
          <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="lead-text">Hi <?php echo htmlspecialchars($userData["username"] ?? ""); ?>, keep your information up to date so the public page always reflects your latest story.</p>
          </div>

          <?php if ($success): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
          <?php endif; ?>

          <?php if ($errors): ?>
            <div class="alert error">
              <ul>
                <?php foreach ($errors as $error): ?>
                  <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form action="dashboard.php" method="POST">
            <div class="form-grid two-col">
              <div class="field">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($userData["full_name"] ?? ""); ?>" required>
              </div>

              <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData["email"] ?? ""); ?>" required>
              </div>

              <div class="field">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($userData["phone"] ?? ""); ?>" required>
              </div>

              <div class="field full">
                <label for="skills">Skills</label>
                <textarea id="skills" name="skills" rows="4" required><?php echo htmlspecialchars($userData["skills"] ?? ""); ?></textarea>
              </div>

              <div class="field full">
                <label for="education">Education</label>
                <textarea id="education" name="education" rows="4" required><?php echo htmlspecialchars($userData["education"] ?? ""); ?></textarea>
              </div>

              <div class="field full">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="5" required><?php echo htmlspecialchars($userData["bio"] ?? ""); ?></textarea>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit">save changes</button>
              <a class="btn outline" href="portfolio.php">preview private resume</a>
              <a class="btn ghost" href="public_resume.php?id=<?php echo urlencode($userData["id"]); ?>" target="_blank">open public view</a>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
</body>
</html>
