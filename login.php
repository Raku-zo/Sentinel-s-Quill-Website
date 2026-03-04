<?php
session_start();
include "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            
            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];

                
                if ($user["role"] === "admin") {
                    header("Location: admin.php");
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - The Sentinel's Quill</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Crimson+Text:wght@400;600&display=swap" rel="stylesheet">


  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" type="image/x-icon" href="journalism-logo.png">
</head>

<body>
<header class="header">
  <div class="header-container">
    <div class="header-top">
      <img src="new-school-logo.png" alt="Army's Angels Integrated School" class="logo">
      <div class="site-title">
        <h1>The Sentinel's Quill</h1>
        <p>Army's Angels Integrated School Campus Journalism</p>
      </div>
      <img src="journalism-logo.png" alt="The Sentinel's Quill" class="logo">
    </div>
  </div>
</header>

<div class="nav-strip"></div>

<main class="login-wrapper">
  <div class="login-card">
    <div class="shield-wrap">
    
    </div>

    <?php if (!empty($error)) : ?>
      <div class="error-msg visible"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group">
        <input type="text" name="username" class="field-input" placeholder="USERNAME" autocomplete="username" required>
      </div>

      <div class="form-group">
        <input type="password" name="password" class="field-input" placeholder="PASSWORD" autocomplete="current-password" required>
      </div>

      <button class="login-btn" type="submit">Login</button>
    </form>

    <a href="index.php" class="back-link">← Back to Home</a>
  </div>
</main>

<footer class="footer">
  <p>&copy; 2026 The Sentinel's Quill – Army's Angels Integrated School</p>
</footer>


</body>
</html>