<?php
include "config.php";

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    $sql_img = $conn->query("SELECT image FROM news WHERE id=$delete_id");
    if ($sql_img->num_rows > 0) {
        $row_img = $sql_img->fetch_assoc();
        if ($row_img['image'] != '' && file_exists("uploads/".$row_img['image'])) {
            unlink("uploads/".$row_img['image']);
        }
    }
    
    $conn->query("DELETE FROM news WHERE id=$delete_id");
    
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - The Sentinel's Quill</title>
<link rel="stylesheet" href="css/style.css">
<link rel="icon" type="image/x-icon" href="journalism-logo.png">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="css/adminpanel.css">
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
    <a href="index.php" class="admin-link">View Site</a>
  </div>
</div>
</header>

<div class="admin-container">
<a href="index.php" class="back-link">← Back to Home</a>
<h2 class="page-title">Create New Article</h2>

<div id="success-message" class="success-message"></div>
<div id="error-message" class="error-message"></div>

<form id="article-form" action="add_article.php" method="POST" enctype="multipart/form-data">
  
  <div class="form-group">
    <label for="title">Article Title *</label>
    <input type="text" id="title" name="title" required placeholder="Enter article title">
  </div>
  <div class="form-group">
    <label for="category">Category *</label>
    <select id="category" name="category" required>
      <option value="">Select a category</option>
      <optgroup label="News">
        <option value="news">News (General)</option>
        <option value="news-campus">News - Campus</option>
        <option value="news-sports">News - Sports</option>
        <option value="news-scitech">News - Science & Technology</option>
        <option value="news-local">News - Local</option>
        <option value="news-foreign">News - Foreign</option>
      </optgroup>
      <option value="feature">Feature</option>
      <optgroup label="Editorial">
        <option value="editorial">Editiorial (General)</option>
        <option value="editorial-cartooning">Editorial - Cartooning</option>
        <option value="editorial-article">Editorial - Article</option>
      </optgroup>
      <option value="column">Column</option>
      <option value="photojournalism">Photojournalism</option>
      <option value="broadcast">Broadcast Media</option>
      <option value="literary">Literary</option>
    </select>
  </div>
  <div class="form-group">
    <label for="authorName">Author Name *</label>
    <input type="text" id="authorName" name="authorName" required placeholder="Enter your name">
  </div>
  <div class="form-group">
    <label for="image">Featured Image (Optional)</label>
    <input type="file" id="image" name="image" accept="image/*">
  </div>
  <div class="form-group">
    <label>Article Content *</label>
    <div class="editor-wrapper">
      <div id="editor"></div>
    </div>
  </div>
  <button type="submit" class="submit-btn">Publish Article</button>
</form>

<h2 class="page-title">Manage Articles</h2>
<?php

$articles = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
if ($articles->num_rows > 0) {
    echo '<table class="article-table">';
    echo '<tr><th>Title</th><th>Author</th><th>Category</th><th>Image</th><th>Published</th><th>Action</th></tr>';
    while ($row = $articles->fetch_assoc()) {
        echo '<tr>';
        echo '<td>'.htmlspecialchars($row['title']).'</td>';
        echo '<td>'.htmlspecialchars($row['author']).'</td>';
        echo '<td>'.htmlspecialchars($row['category']).'</td>';
        echo '<td>';
        if ($row['image'] != '') {
            echo '<img src="uploads/'.htmlspecialchars($row['image']).'" alt="Article Image">';
        }
        echo '</td>';
        echo '<td>'.date('M d, Y', strtotime($row['created_at'])).'</td>';
        echo '<td><a class="delete-btn" href="admin.php?delete_id='.$row['id'].'" onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>No articles posted yet.</p>';
}
?>
</div>

<footer class="footer">
<p>&copy; 2025 The Sentinel's Quill - Army's Angels Integrated School</p>
<p>Campus Journalism | Founded 1998</p>
</footer>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>

var quill = new Quill('#editor', { theme: 'snow' });

var form = document.getElementById('article-form');
form.onsubmit = function() {
  var contentInput = document.createElement('input');
  contentInput.setAttribute('type', 'hidden');
  contentInput.setAttribute('name', 'content');
  contentInput.value = quill.root.innerHTML;
  form.appendChild(contentInput);
};
</script>

</body>
</html>