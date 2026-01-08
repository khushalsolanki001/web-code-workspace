<?php
require_once __DIR__ . "/db.php";
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$workspace = $_POST['workspace'] ?? $_GET['workspace'] ?? 'SET-A';
$workspace = mysqli_real_escape_string($conn, $workspace);

switch($action) {
    case 'read':
        $filePath = $_GET['file'] ?? '';
        if (empty($filePath)) {
            echo json_encode(['error' => 'File path required']);
            exit;
        }
        
        // Security: only allow files from uploads directory
        $filePath = str_replace('..', '', $filePath);
        if (strpos($filePath, 'uploads/') !== 0) {
            echo json_encode(['error' => 'Invalid file path']);
            exit;
        }
        
        if (!file_exists($filePath)) {
            echo json_encode(['error' => 'File not found']);
            exit;
        }
        
        $content = file_get_contents($filePath);
        echo json_encode([
            'success' => true,
            'content' => $content,
            'path' => $filePath,
            'name' => basename($filePath)
        ]);
        break;
        
    case 'save':
        $filePath = $_POST['file'] ?? '';
        $content = $_POST['content'] ?? '';
        
        if (empty($filePath)) {
            echo json_encode(['error' => 'File path required']);
            exit;
        }
        
        // Security: only allow files from uploads directory
        $filePath = str_replace('..', '', $filePath);
        if (strpos($filePath, 'uploads/') !== 0) {
            echo json_encode(['error' => 'Invalid file path']);
            exit;
        }
        
        if (!file_exists($filePath)) {
            echo json_encode(['error' => 'File not found']);
            exit;
        }
        
        if (file_put_contents($filePath, $content) !== false) {
            echo json_encode(['success' => true, 'message' => 'File saved successfully']);
        } else {
            echo json_encode(['error' => 'Failed to save file']);
        }
        break;
        
    case 'delete':
        $filePath = $_POST['file'] ?? '';
        
        if (empty($filePath)) {
            echo json_encode(['error' => 'File path required']);
            exit;
        }
        
        // Security: only allow files from uploads directory
        $filePath = str_replace('..', '', $filePath);
        if (strpos($filePath, 'uploads/') !== 0) {
            echo json_encode(['error' => 'Invalid file path']);
            exit;
        }
        
        // Delete from database
        $filePathEscaped = mysqli_real_escape_string($conn, $filePath);
        $conn->query("DELETE FROM messages WHERE file_path = '$filePathEscaped' AND workspace = '$workspace'");
        
        // Delete file
        if (file_exists($filePath) && unlink($filePath)) {
            echo json_encode(['success' => true, 'message' => 'File deleted successfully']);
        } else {
            echo json_encode(['error' => 'Failed to delete file']);
        }
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
}
?>

