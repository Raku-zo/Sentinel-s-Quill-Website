<?php
include "config.php";

$q = $_GET['q'];

$stmt = $conn->prepare("SELECT id, title FROM news 
                        WHERE title LIKE CONCAT('%', ?, '%') 
                        LIMIT 6");
$stmt->bind_param("s", $q);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    echo "<div class='suggestion-item' 
          onclick=\"window.location='article.php?id=".$row['id']."'\">"
          .htmlspecialchars($row['title']).
         "</div>";
}