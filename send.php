<?php
require_once __DIR__ . "/db.php";

$username = $_POST['username'] ?? '';
$message  = $_POST['message'] ?? '';
$workspace = $_POST['workspace'] ?? 'SET-A';
$filePath = '';

$username = mysqli_real_escape_string($conn, $username);
$message  = mysqli_real_escape_string($conn, $message);
$workspace = mysqli_real_escape_string($conn, $workspace);

/* FILE UPLOAD (OPTIONAL) */
if (!empty($_FILES['file']) && $_FILES['file']['error'] === 0) {

    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    // Preserve original filename
    $originalName = basename($_FILES['file']['name']);
    $filename = $originalName;
    $target = $uploadDir . $filename;
    
    // Handle duplicates: if file exists, add (1), (2), etc.
    $counter = 1;
    while (file_exists($target)) {
        $pathInfo = pathinfo($originalName);
        $name = $pathInfo['filename'];
        $ext = isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';
        $filename = $name . " ($counter)" . $ext;
        $target = $uploadDir . $filename;
        $counter++;
    }

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        // Save relative path for browser access
        $filePath = "uploads/" . $filename;
    }
}

/* DO NOT INSERT EMPTY ROWS */
if ($username !== '' || $message !== '' || $filePath !== '') {

    $stmt = $conn->prepare(
        "INSERT INTO messages (username, message, file_path, workspace) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssss", $username, $message, $filePath, $workspace);
    $stmt->execute();
}

echo "OK";
