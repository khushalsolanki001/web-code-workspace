<?php
header('Content-Type: application/json');

// Simple security check - you might want to enhance this
$allowed_commands = ['dir', 'ls', 'echo', 'ver', 'php', 'git'];

$data = json_decode(file_get_contents('php://input'), true);
$command = $data['command'] ?? '';
$cwd = $data['cwd'] ?? getcwd();

if (empty($command)) {
    echo json_encode(['output' => '']);
    exit;
}

// Security: Prevent extremely dangerous commands (basic filter)
// In a real scenario, this is very dangerous. Use with caution.
if (stripos($command, 'rm -rf') !== false || stripos($command, 'format') !== false) {
    echo json_encode(['output' => "Command not allowed for security reasons.\r\n"]);
    exit;
}

// Change directory if needed
if (is_dir($cwd)) {
    chdir($cwd);
}

// Handle 'cd' command specially
if (preg_match('/^cd\s+(.+)$/', $command, $matches)) {
    $target = trim($matches[1]);

    // Handle quoted paths
    if (
        (str_starts_with($target, '"') && str_ends_with($target, '"')) ||
        (str_starts_with($target, "'") && str_ends_with($target, "'"))
    ) {
        $target = substr($target, 1, -1);
    }

    // Resolve path
    $realNewDir = realpath($target);
    if ($realNewDir && is_dir($realNewDir)) {
        chdir($realNewDir);
        echo json_encode(['output' => "", 'cwd' => getcwd()]);
    } else {
        echo json_encode(['output' => "cd: The system cannot find the path specified: $target\r\n", 'cwd' => $cwd]);
    }
    exit;
}

// Execute command
// 2>&1 redirects stderr to stdout
$output = [];
$return_var = 0;
exec($command . ' 2>&1', $output, $return_var);

$outputStr = implode("\r\n", $output);
if (!empty($outputStr)) {
    $outputStr .= "\r\n";
}

echo json_encode([
    'output' => $outputStr,
    'cwd' => getcwd()
]);
?>