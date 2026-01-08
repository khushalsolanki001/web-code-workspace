<?php
require_once __DIR__ . "/db.php";

$workspace = $_GET['workspace'] ?? 'SET-A';
$workspace = mysqli_real_escape_string($conn, $workspace);

// Get all files for this workspace
$result = $conn->query("SELECT DISTINCT file_path FROM messages WHERE workspace = '$workspace' AND file_path != '' AND file_path IS NOT NULL ORDER BY file_path ASC");

$files = [];
while ($row = $result->fetch_assoc()) {
    if (file_exists($row['file_path'])) {
        $files[] = [
            'path' => $row['file_path'],
            'name' => basename($row['file_path']),
            'size' => filesize($row['file_path'])
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($files);
?>

