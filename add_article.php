<?php

include "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $author = $conn->real_escape_string($_POST['authorName']);
    $content = $conn->real_escape_string($_POST['content']);
    $imageName = "";

    if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {

        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            echo "<p style='color:red;'>Error uploading image.</p>";
            exit;
        }
    }

    $sql = "INSERT INTO news (title, category, author, content, image) 
            VALUES ('$title', '$category', '$author', '$content', '$imageName')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Article published successfully!</p>";
        echo "<p><a href='admin.php'>Back to Admin Page</a></p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }

} else {

    header("Location: admin.php");
    exit;
}
?>