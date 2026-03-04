<?php
session_start();
include "config.php";

$categoryNames = [
    'news' => 'News (General)',
    'news-campus' => 'Campus News',
    'news-sports' => 'Sports',
    'news-scitech' => 'Science & Technology',
    'news-local' => 'Local News',
    'news-foreign' => 'Foreign News',
    'feature' => 'Feature',
    'editorial' => 'Editorial',
    'editorial-cartooning' => 'Cartooning',
    'editorial-article' => 'Article',
    'column' => 'Column',
    'photojournalism' => 'Photojournalism',
    'broadcast' => 'Broadcast Media',
    'literary' => 'Literary'
];

$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

if ($categoryFilter != '') {
    $stmt = $conn->prepare("SELECT * FROM news WHERE category=? ORDER BY created_at DESC");
    $stmt->bind_param("s", $categoryFilter);
} else {
    $stmt = $conn->prepare("SELECT * FROM news ORDER BY created_at DESC");
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>The Sentinel's Quill - Army's Angels Integrated School</title>
<link rel="stylesheet" href="css/style.css">
<link rel="icon" type="image/x-icon" href="journalism-logo.png">
<link rel="stylesheet" href="css/buttons.css">
</head>
<body>

<header class="header">
  <div class="header-container" style="position: relative;">
    <div class="header-top">
      <img src="new-school-logo.png" alt="Army's Angels Integrated School" class="logo">

      <?php if (isset($_SESSION['username'])): ?>
          <span id="username-display"><?= htmlspecialchars($_SESSION['username']) ?></span>
          <button id="logout-btn" onclick="window.location.href='logout.php'">Sign Out</button>
      <?php else: ?>
          <button id="login-btn" onclick="window.location.href='login.php'">Login / Signup</button>
      <?php endif; ?>

      <div class="site-title">
          <h1>The Sentinel's Quill</h1>
          <p>Army's Angels Integrated School Campus Journalism</p>
      </div>
      <img src="journalism-logo.png" alt="The Sentinel's Quill" class="logo">
    </div>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
      <button id="admin-btn" onclick="window.location.href='admin.php'" style="display:block;">Admin Panel</button>
    <?php endif; ?>
  </div>
</header>

<nav class="nav-bar">
  <div class="nav-container">
    <ul class="nav">
      <li><a href="index.php" class="active">Home</a></li>
      <li class="dropdown">
        <a href="index.php?category=news">News</a>
        <div class="dropdown-content">
          <a href="index.php?category=news-campus">Campus</a>
          <a href="index.php?category=news-sports">Sports</a>
          <a href="index.php?category=news-scitech">Science & Technology</a>
          <a href="index.php?category=news-local">Local</a>
          <a href="index.php?category=news-foreign">Foreign</a>
        </div>
      </li>
      <li><a href="index.php?category=feature">Feature</a></li>
      <li class="dropdown">
        <a href="index.php?category=editorial">Editorial</a>
        <div class="dropdown-content">
          <a href="index.php?category=editorial-cartooning">Cartooning</a>
          <a href="index.php?category=editorial-article">Article</a>
        </div>
      </li>
      <li><a href="index.php?category=column">Column</a></li>
      <li><a href="index.php?category=photojournalism">Photojournalism</a></li>
      <li><a href="index.php?category=broadcast">Broadcast Media</a></li>
      <li><a href="index.php?category=literary">Literary</a></li>
    </ul>
  </div>
</nav>

<div class="container">
  <?php
    $displayCategory = $categoryFilter != '' ? $categoryNames[$categoryFilter] : 'Latest Updates';
    echo '<h2 class="page-title">' . $displayCategory . '</h2>';
  ?>

  <div id="news-feed" class="news-feed">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="article">';
            if ($row['image'] != '') {
                echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" alt="Article Image" class="article-image">';
            }
            echo '<h3 class="article-title">' . htmlspecialchars($row['title']) . '</h3>';
            echo '<p class="article-meta">By ' . htmlspecialchars($row['author']) . ' | Category: ' . htmlspecialchars($categoryNames[$row['category']]) . ' | Published: ' . date('M d, Y', strtotime($row['created_at'])) . '</p>';
            echo '<div class="article-content">' . $row['content'] . '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No articles found in this category.</p>';
    }
    ?>
  </div>
</div>

<footer class="footer">
  <p>&copy; 2026 The Sentinel's Quill - Army's Angels Integrated School</p>
</footer>

</body>
</html>