<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}
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
    <p>Welcome to my personal portfolio showcasing my academic journey, skills, and experiences.</p>
  </header>

  <div class="resume-container">
    <h2 class="center-text">Juan Miguel G. Pimentel</h2>
    <p class="center-text">3rd Year Computer Science Student</p>
    <p class="center-text"> 23-07687@g.batstate-u.edu.ph |  +63 956 629 6434</p>

    <hr>

    <h3>Academic History</h3>
    <ul>
      <li><b>Elementary:</b> Anne-Claire Montessori De Lipa (2011–2017)</li>
      <li><b>Junior High:</b> Batangas College of Arts and Sciences, Inc. (2017–2021)</li>
      <li><b>Senior High:</b> Batangas College of Arts and Sciences, Inc. (2021–2023)</li>
      <li><b>College:</b> Batangas State University - Main II (2023–Present)</li>
    </ul>

    <h3>Skills</h3>
    <ul class="grid-list">
      <li>CTF (Capture The Flag) Challenges</li>
      <li>C++ Programming</li>
      <li>SQL Database Management</li>
      <li>Flutter Mobile Development</li>
      <li>PC Hardware Repair & Troubleshooting</li>
    </ul>

    <h3>Experience</h3>
    <ul>
      <li><b>PC Repair & Troubleshooting:</b> Diagnosed and resolved hardware/software issues, performed system maintenance, and upgraded components for clients and personal projects.</li>
      <li><b>CTF Challenges:</b> Regularly participated in cybersecurity competitions, solving cryptography, forensics, and vulnerability analysis challenges.</li>
    </ul>

    <div class="center-text">
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
  </div>
</body>
</html>
