<?php
$conn = new mysqli("localhost", "root", "", "school_news");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>