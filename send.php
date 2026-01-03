<?php
require_once __DIR__ . "/db.php";

$username = $_POST['username'] ?? '';
$message  = $_POST['message'] ?? '';
$filePath = '';

$username = mysqli_real_escape_string($conn, $username);
$message  = mysqli_real_escape_string($conn, $message);

/* FILE UPLOAD (OPTIONAL) */
if (!empty($_FILES['file']) && $_FILES['file']['error'] === 0) {

    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $filename = time() . "_" . basename($_FILES['file']['name']);
    $target   = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        // Save relative path for browser access
        $filePath = "uploads/" . $filename;
    }
}

/* DO NOT INSERT EMPTY ROWS */
if ($username !== '' || $message !== '' || $filePath !== '') {

    $stmt = $conn->prepare(
        "INSERT INTO messages (username, message, file_path) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $username, $message, $filePath);
    $stmt->execute();
}

echo "OK";
