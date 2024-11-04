<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $author_id = $_SESSION['user_id'];
    $image_path = null;

    // Image upload handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if file is an actual image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false && move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image_path = $targetFile;
        } else {
            echo "Error uploading image.";
        }
    }

    $stmt = $conn->prepare("INSERT INTO posts (title, body, author_id, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $title, $body, $author_id, $image_path);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Create New Post</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Post Title" required><br>
            <textarea name="body" placeholder="Write your post here..." required></textarea><br>
            <input type="file" name="image" accept="image/*"><br>
            <button type="submit">Publish</button>
        </form>
    </div>
</body>
</html>
