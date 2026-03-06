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
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$isHomeActive = $categoryFilter === '';
$isNewsActive = $categoryFilter === 'news' || strpos($categoryFilter, 'news-') === 0;
$isEditorialActive = $categoryFilter === 'editorial' || strpos($categoryFilter, 'editorial-') === 0;

if ($search != '') {
    $stmt = $conn->prepare("SELECT * FROM news WHERE title LIKE CONCAT('%', ?, '%') OR content LIKE CONCAT('%', ?, '%') ORDER BY created_at DESC");
    $stmt->bind_param("ss", $search, $search);
} elseif ($categoryFilter != '') {
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
<link rel="stylesheet" href="css/buttons.css">
<link rel="icon" type="image/x-icon" href="journalism-logo.png">
<style>
.search-wrapper {
  background: #fff;
  padding: 10px 0;
  border-bottom: 1px solid #ddd;
}
.search-container {
  max-width: 700px;
  margin: auto;
  position: relative;
}
.search-form {
  display: flex;
  border: 2px solid #1a5d1a;
  border-radius: 30px;
  overflow: hidden;
}
.search-form input {
  flex: 1;
  padding: 10px 15px;
  border: none;
  outline: none;
  font-size: 1rem;
}
.search-form button {
  background: #1a5d1a;
  color: white;
  border: none;
  padding: 0 20px;
  cursor: pointer;
  font-size: 1rem;
}
.search-form button:hover {
  background: #2d7a2d;
}
#search-suggestions {
  position: absolute;
  top: 100%;
  width: 100%;
  background: white;
  border: 1px solid #ddd;
  border-top: none;
  max-height: 250px;
  overflow-y: auto;
  display: none;
  z-index: 999;
  border-radius: 0 0 10px 10px;
}
.suggestion-item {
  padding: 10px 15px;
  cursor: pointer;
  border-bottom: 1px solid #eee;
}
.suggestion-item:hover {
  background: #f5f5f5;
}

/* Added minimal styling for article previews */
.article-card {
  display: flex;
  border: 1px solid #ddd;
  border-radius: 8px;
  margin: 15px auto;
  max-width: 900px;
  overflow: hidden;
  background: #fff;
}
.article-image {
  width: 200px;
  object-fit: cover;
}
.article-content {
  padding: 15px;
}
.article-content h2 {
  margin-top: 0;
}
.article-content a {
  color: #1a5d1a;
  font-weight: bold;
  text-decoration: none;
}
.article-content a:hover {
  text-decoration: underline;
}
</style>
</head>
<body>

<header class="header">
  <div class="header-container" style="position: relative;">
    <div class="header-top">
      <img src="new-school-logo.png" alt="Army's Angels Integrated School" class="logo">
      <div class="admin-auth">
        <?php if (isset($_SESSION['username'])): ?>
          <span id="username-display"><?= htmlspecialchars($_SESSION['username']) ?></span>
          <button id="logout-btn" onclick="window.location.href='logout.php'">Sign Out</button>
        <?php else: ?>
          <button id="login-btn" onclick="window.location.href='login.php'">Login</button>
        <?php endif; ?>
      </div>

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

<div class="search-wrapper">
  <div class="search-container">
    <form action="index.php" method="GET" class="search-form">
      <input 
        type="text" 
        name="search" 
        id="search-input" 
        placeholder="Search articles..." 
        autocomplete="off"
        value="<?= htmlspecialchars($search ?? '') ?>"
      >
      <button type="submit">🔍</button>
    </form>
    <div id="search-suggestions"></div>
  </div>
</div>

<nav class="nav-bar">
  <div class="nav-container">
    <ul class="nav">
      <li><a href="index.php" class="<?= $isHomeActive ? 'active' : '' ?>">Home</a></li>
      <li class="dropdown">
        <a href="index.php?category=news" class="<?= $isNewsActive ? 'active' : '' ?>">News</a>
        <div class="dropdown-content">
          <a href="index.php?category=news-campus" class="<?= $categoryFilter === 'news-campus' ? 'active' : '' ?>">Campus</a>
          <a href="index.php?category=news-sports" class="<?= $categoryFilter === 'news-sports' ? 'active' : '' ?>">Sports</a>
          <a href="index.php?category=news-scitech" class="<?= $categoryFilter === 'news-scitech' ? 'active' : '' ?>">Science & Technology</a>
          <a href="index.php?category=news-local" class="<?= $categoryFilter === 'news-local' ? 'active' : '' ?>">Local</a>
          <a href="index.php?category=news-foreign" class="<?= $categoryFilter === 'news-foreign' ? 'active' : '' ?>">Foreign</a>
        </div>
      </li>
       <li><a href="index.php?category=feature" class="<?= $categoryFilter === 'feature' ? 'active' : '' ?>">Feature</a></li>
      <li class="dropdown">
        <a href="index.php?category=editorial" class="<?= $isEditorialActive ? 'active' : '' ?>">Editorial</a>
        <div class="dropdown-content">
          <a href="index.php?category=editorial-cartooning" class="<?= $categoryFilter === 'editorial-cartooning' ? 'active' : '' ?>">Cartooning</a>
          <a href="index.php?category=editorial-article" class="<?= $categoryFilter === 'editorial-article' ? 'active' : '' ?>">Article</a>
        </div>
      </li>
      <li><a href="index.php?category=column" class="<?= $categoryFilter === 'column' ? 'active' : '' ?>">Column</a></li>
      <li><a href="index.php?category=photojournalism" class="<?= $categoryFilter === 'photojournalism' ? 'active' : '' ?>">Photojournalism</a></li>
      <li><a href="index.php?category=broadcast" class="<?= $categoryFilter === 'broadcast' ? 'active' : '' ?>">Broadcast Media</a></li>
      <li><a href="index.php?category=literary" class="<?= $categoryFilter === 'literary' ? 'active' : '' ?>">Literary</a></li>
    </ul>
  </div>
</nav>

<!-- Articles Listing -->
<div class="articles-list">
<?php while($row = $result->fetch_assoc()): ?>
  <div class="article-card">
    <img class="article-image" src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
    <div class="article-content">
      <h2><?= htmlspecialchars($row['title']) ?></h2>
      <p><small><?= date('F j, Y', strtotime($row['created_at'])) ?> | <?= htmlspecialchars($categoryNames[$row['category']] ?? $row['category']) ?></small></p>
      <p><?= substr(strip_tags($row['content']), 0, 150) ?>...</p>
      <a href="article.php?id=<?= $row['id'] ?>">Read More</a>
    </div>
  </div>
<?php endwhile; ?>
</div>

</body>
</html>