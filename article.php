<?php
include "config.php";

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();

if(!$article) {
    echo "<p style='text-align:center; margin-top:50px;'>Article not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($article['title']) ?> - The Sentinel's Quill</title>
<link rel="stylesheet" href="css/style.css">
<style>
/* Article container centered and wider border */
.article-container {
    max-width: 950px;
    margin: 3rem auto;
    background: var(--white);
    padding: 3rem 2.5rem;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    border: 3px solid var(--primary-green);
}

/* Article title */
.article-container h1.article-title {
    color: var(--primary-green);
    font-size: 2.3rem;
    margin-bottom: 0.75rem;
    text-align: center;
}

/* Meta info */
.article-meta {
    color: var(--text-light);
    font-size: 1rem;
    margin-bottom: 2rem;
    text-align: center;
}

/* Full-width responsive image without cutting */
.article-image {
    width: 100%;
    max-height: 600px;
    object-fit: contain; /* ensure the whole image fits */
    border-radius: 12px;
    margin-bottom: 2rem;
    display: block;
}

/* Article content */
.article-content {
    line-height: 1.8;
    color: var(--text-dark);
    font-size: 1.05rem;
    text-align: justify;
}

/* Back button */
.back-btn {
    display: inline-block;
    margin-top: 2rem;
    padding: 0.7rem 2rem;
    background: var(--primary-green);
    color: var(--white);
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    font-size: 0.95rem;
    transition: 0.3s;
}

.back-btn:hover {
    background: var(--secondary-green);
    transform: translateY(-2px);
}
</style>
</head>
<body>

<header class="header">
  <div class="header-container">
    <div class="header-top logos">
      <img src="new-school-logo.png" alt="Army's Angels Integrated School" class="logo">
      <div class="site-title">
          <h1>The Sentinel's Quill</h1>
          <p>Army's Angels Integrated School Campus Journalism</p>
      </div>
      <img src="journalism-logo.png" alt="The Sentinel's Quill" class="logo">
    </div>
  </div>
</header>

<div class="article-container">
    <h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>
    <div class="article-meta">
        <?= date('F j, Y', strtotime($article['created_at'])) ?> |
        <?= htmlspecialchars($categoryNames[$article['category']] ?? $article['category']) ?>
    </div>

    <?php if(!empty($article['image'])): ?>
        <img src="uploads/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="article-image">
    <?php endif; ?>

    <div class="article-content">
        <?= $article['content'] ?>
    </div>

    <a href="index.php" class="back-btn">← Back to Home</a>
</div>

</body>
</html>