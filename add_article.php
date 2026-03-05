<?php

include "config.php";

function renderResultPage($title, $message, $isSuccess = true, $backUrl = "index.php")
{
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    $statusClass = $isSuccess ? "success" : "error";
    $statusLabel = $isSuccess ? "Success" : "Error";

    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$safeTitle}</title>
    <style>
        :root {
            --bg-1: #f5f7ff;
            --bg-2: #eefaf3;
            --card: #ffffff;
            --text: #1c2536;
            --muted: #5f6b7a;
            --success: #1f8f4b;
            --error: #b42318;
            --shadow: 0 20px 45px rgba(22, 34, 51, 0.12);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 20% 20%, #d9f4e5 0, transparent 40%),
                radial-gradient(circle at 80% 80%, #d8e3ff 0, transparent 45%),
                linear-gradient(135deg, var(--bg-1), var(--bg-2));
            padding: 24px;
        }
        .panel {
            width: min(560px, 100%);
            background: var(--card);
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 28px;
            border: 1px solid rgba(27, 41, 61, 0.07);
        }
        .badge {
            display: inline-block;
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 700;
            padding: 6px 10px;
            border-radius: 999px;
            margin-bottom: 14px;
        }
        .badge.success {
            color: #0f6f37;
            background: #e7f8ee;
        }
        .badge.error {
            color: #9e1a12;
            background: #fdebea;
        }
        h1 {
            margin: 0 0 10px;
            font-size: 28px;
            line-height: 1.2;
        }
        p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
            font-size: 16px;
        }
        .actions {
            margin-top: 24px;
        }
        .button {
            display: inline-block;
            text-decoration: none;
            font-weight: 600;
            padding: 11px 16px;
            border-radius: 10px;
            transition: transform 0.15s ease, opacity 0.2s ease;
        }
        .button:hover {
            transform: translateY(-1px);
            opacity: 0.95;
        }
        .button.success {
            background: var(--success);
            color: #fff;
        }
        .button.error {
            background: var(--error);
            color: #fff;
        }
    </style>
</head>
<body>
    <main class="panel">
        <span class="badge {$statusClass}">{$statusLabel}</span>
        <h1>{$safeTitle}</h1>
        <p>{$safeMessage}</p>
        <div class="actions">
            <a class="button {$statusClass}" href="{$backUrl}">Back to Home Page</a>
        </div>
    </main>
</body>
</html>
HTML;
}

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
            renderResultPage("Upload failed", "There was a problem uploading the article image.", false);
            exit;
        }
    }

    $sql = "INSERT INTO news (title, category, author, content, image) 
            VALUES ('$title', '$category', '$author', '$content', '$imageName')";

    if ($conn->query($sql) === TRUE) {
        renderResultPage("Article published", "Your article has been published successfully.");
    } else {
        renderResultPage("Publish failed", "Database error: " . $conn->error, false);
    }

} else {

    header("Location: admin.php");
    exit;
}
?>
