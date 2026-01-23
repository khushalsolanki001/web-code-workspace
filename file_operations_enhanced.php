<?php
// file_operations_enhanced.php
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$root = __DIR__;

function getSafePath($path, $root)
{
    $path = str_replace('\\', '/', $path);
    $root = str_replace('\\', '/', $root);
    $realPath = realpath($root . '/' . $path);
    $realRoot = realpath($root);
    if ($realPath === false || strpos($realPath, $realRoot) !== 0) {
        return false;
    }
    return $realPath;
}

function deleteRecursive($dir)
{
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        is_dir($path) ? deleteRecursive($path) : unlink($path);
    }
    return rmdir($dir);
}

switch ($action) {
    case 'rename':
        $oldPath = $_POST['old_path'] ?? '';
        $newPath = $_POST['new_path'] ?? '';

        if (!$oldPath || !$newPath) {
            die(json_encode(['error' => 'Missing paths']));
        }

        $fullOldPath = getSafePath($oldPath, $root);
        $fullNewPath = $root . '/' . $newPath;

        if (!$fullOldPath) {
            die(json_encode(['error' => 'Invalid old path']));
        }

        if (file_exists($fullNewPath)) {
            die(json_encode(['error' => 'Target already exists']));
        }

        if (rename($fullOldPath, $fullNewPath)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Rename failed']);
        }
        break;

    case 'delete_recursive':
        $path = $_POST['path'] ?? '';
        $fullPath = getSafePath($path, $root);

        if (!$fullPath) {
            die(json_encode(['error' => 'Invalid path']));
        }

        if (deleteRecursive($fullPath)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Delete failed']);
        }
        break;

    default:
        // Forward to original file_manager.php
        include 'file_manager.php';
}
?>