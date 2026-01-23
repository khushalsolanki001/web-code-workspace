<?php
// file_manager.php
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$root = __DIR__; // Root directory of the workspace

function getSafePath($path, $root)
{
    // Normalize slashes
    $path = str_replace('\\', '/', $path);
    $root = str_replace('\\', '/', $root);

    // Prevent traversal
    $realPath = realpath($root . '/' . $path);
    $realRoot = realpath($root);

    if ($realPath === false || strpos($realPath, $realRoot) !== 0) {
        return false;
    }
    return $realPath;
}

function scanDirRecursive($dir, $relativePath = '')
{
    $files = [];
    $exclude = ['.git', '.vscode', 'node_modules', '.idea', 'vendor'];

    if (!is_dir($dir))
        return [];

    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item == '.' || $item == '..')
            continue;
        if (in_array($item, $exclude))
            continue;

        $path = $dir . '/' . $item;
        $rel = $relativePath ? $relativePath . '/' . $item : $item;

        $isDir = is_dir($path);

        $node = [
            'name' => $item,
            'path' => $rel,
            'type' => $isDir ? 'folder' : 'file'
        ];

        if ($isDir) {
            $node['children'] = scanDirRecursive($path, $rel);
            // Sort children: folders first, then files
            usort($node['children'], function ($a, $b) {
                if ($a['type'] === $b['type'])
                    return strcasecmp($a['name'], $b['name']);
                return $a['type'] === 'folder' ? -1 : 1;
            });
        }

        $files[] = $node;
    }

    // Sort root level
    usort($files, function ($a, $b) {
        if ($a['type'] === $b['type'])
            return strcasecmp($a['name'], $b['name']);
        return $a['type'] === 'folder' ? -1 : 1;
    });

    return $files;
}

switch ($action) {
    case 'list':
        // Return file tree
        try {
            $tree = scanDirRecursive($root);
            echo json_encode($tree);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'read':
        $path = $_POST['path'] ?? '';
        if (!$path)
            die(json_encode(['error' => 'No path provided']));

        $fullPath = getSafePath($path, $root);
        if (!$fullPath || !is_file($fullPath)) {
            die(json_encode(['error' => 'File not found']));
        }

        echo json_encode(['content' => file_get_contents($fullPath)]);
        break;

    case 'create':
        $path = $_POST['file'] ?? '';
        if (!$path) die(json_encode(['error' => 'No filename provided']));
        
        // Basic validation - prevent traversal
        $fullPath = $root . '/' . $path;
        $realRoot = realpath($root);
        
        // Ensure strictly under root
        if (strpos(realpath(dirname($fullPath)), $realRoot) !== 0) {
             die(json_encode(['error' => 'Invalid path']));
        }
        
        if (file_exists($fullPath)) {
            die(json_encode(['error' => 'File already exists']));
        }
        
        if (file_put_contents($fullPath, "") !== false) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Create failed']));
        }
        break;

    case 'write':
        $path = $_POST['path'] ?? '';
        $content = $_POST['content'] ?? '';

        if (!$path)
            die(json_encode(['error' => 'No path provided']));

        $fullPath = getSafePath($path, $root);
        // Allow creating new files if they are within root
        if (!$fullPath) {
            // Try to resolve parent dir to see if it's safe
            $dir = dirname($root . '/' . $path);
            if (strpos(realpath($dir), realpath($root)) !== 0) {
                die(json_encode(['error' => 'Invalid path']));
            }
            $fullPath = $root . '/' . $path;
        }

        if (file_put_contents($fullPath, $content) !== false) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Write failed']);
        }
        break;

    case 'delete':
        $path = $_POST['path'] ?? '';
        $fullPath = getSafePath($path, $root);

        if (!$fullPath)
            die(json_encode(['error' => 'Invalid path']));

        if (is_dir($fullPath)) {
            // Recursive delete directory
            // For safety, let's just use empty check for now or basic rmdir if empty
            if (rmdir($fullPath)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Failed to delete folder (must be empty)']);
            }
        } else {
            if (unlink($fullPath)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Failed to delete file']);
            }
        }
        break;

    case 'create_file':
        $path = $_POST['path'] ?? '';
        // Validation...
        $fullPath = $root . '/' . $path;
        // Check if parent dir exists
        if (!file_exists(dirname($fullPath))) {
            die(json_encode(['error' => 'Parent directory does not exist']));
        }
        if (file_exists($fullPath)) {
            die(json_encode(['error' => 'File already exists']));
        }
        if (file_put_contents($fullPath, "") !== false) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to create file']);
        }
        break;

    case 'create_folder':
        $path = $_POST['path'] ?? '';
        if (!$path) {
            die(json_encode(['error' => 'No path provided']));
        }
        $fullPath = $root . '/' . $path;
        // Check if parent dir exists
        if (!file_exists(dirname($fullPath))) {
            die(json_encode(['error' => 'Parent directory does not exist']));
        }
        if (file_exists($fullPath)) {
            die(json_encode(['error' => 'Folder already exists']));
        }
        if (mkdir($fullPath, 0777, true)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to create folder']);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}
?>