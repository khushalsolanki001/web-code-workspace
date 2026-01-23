<?php
header('Content-Type: application/json');

// Enhanced terminal backend with better security and functionality
$data = json_decode(file_get_contents('php://input'), true);
$command = $data['command'] ?? '';
$cwd = $data['cwd'] ?? getcwd();

if (empty($command)) {
    echo json_encode(['output' => '', 'cwd' => getcwd()]);
    exit;
}

// Enhanced security check
$blacklistedPatterns = [
    'rm -rf',
    'rm -r',
    'format',
    'fdisk',
    'mkfs',
    'dd if=',
    '> /dev/',
    'chmod 777',
    'wget.*|sh',
    'curl.*|sh',
    'eval.*\$',
    'exec.*\$',
    'system.*\$',
    'passthru.*\$'
];

foreach ($blacklistedPatterns as $pattern) {
    if (preg_match('/' . preg_quote($pattern, '/') . '/i', $command)) {
        echo json_encode([
            'output' => "⚠️ Command blocked for security reasons\r\n",
            'error' => true,
            'type' => 'security'
        ]);
        exit;
    }
}

// Change to working directory
if (is_dir($cwd)) {
    chdir($cwd);
}

// Handle built-in commands
switch (strtolower(trim($command))) {
    case 'help':
    case 'history':
    case 'clear':
    case 'cls':
    case 'exit':
    case 'quit':
        // These are handled client-side
        echo json_encode([
            'output' => '',
            'cwd' => getcwd(),
            'builtin' => true
        ]);
        exit;

    case 'pwd':
        echo json_encode([
            'output' => getcwd() . "\n",
            'cwd' => getcwd()
        ]);
        exit;

    case 'whoami':
        echo json_encode([
            'output' => get_current_user() . "\n",
            'cwd' => getcwd()
        ]);
        exit;

    case 'date':
        echo json_encode([
            'output' => date('Y-m-d H:i:s') . "\n",
            'cwd' => getcwd()
        ]);
        exit;

    case 'uname':
        echo json_encode([
            'output' => php_uname('a') . "\n",
            'cwd' => getcwd()
        ]);
        exit;

    case 'ls':
        // Enhanced ls command
        $files = array_diff(scandir('.'), ['.', '..']);
            $output = '';
        foreach ($files as $file) {
            $color = '';
            $icon = '';
            
            if (is_dir($file)) {
                $color = "\033[38;5;208m"; // Blue for directories
                $icon = 'D';
            } else {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $icon = in_array($ext, ['json', 'xml', 'config', 'log']) ? 'F' : 'T';
                switch ($ext) {
                    case 'php': $color = "\033[38;5;135m"; break; // Purple
                    case 'js': $color = "\033[38;5;226m"; break; // Yellow
                    case 'html': $color = "\033[38;5;196m"; break; // Red
                    case 'css': $color = "\033[38;5;51m"; break; // Blue
                    case 'json': $color = "\033[38;5;172m"; break; // Light blue
                    default: $color = "\033[38;5;46m"; // Cyan
                }
            }
            $output .= sprintf("%s  %s%s\033[0m\n", $color, $icon, $file);
        }
        echo json_encode([
            'output' => $output,
            'cwd' => getcwd()
        ]);
        exit;
}

// Handle 'cd' command
if (preg_match('/^cd\s+(.*)$/', $command, $matches)) {
    $target = trim($matches[1]);

    // Handle quoted paths
    if (
        (str_starts_with($target, '"') && str_ends_with($target, '"')) ||
        (str_starts_with($target, "'") && str_ends_with($target, "'"))
    ) {
        $target = substr($target, 1, -1);
    }

    // Expand ~ to home directory
    if ($target === '~') {
        $target = $_SERVER['HOME'] ?? getcwd();
    }

    $realNewDir = realpath($target);
    if ($realNewDir && is_dir($realNewDir)) {
        chdir($realNewDir);
        echo json_encode([
            'output' => "Changed to: " . getcwd() . "\n",
            'cwd' => getcwd()
        ]);
    } else {
        echo json_encode([
            'output' => "cd: No such directory: $target\n",
            'error' => true,
            'type' => 'not_found'
        ]);
    }
    exit;
}

// Execute other commands
// Use safer execution method
$descriptorspec = [
    ['file', '/dev/null', 'w'],
    ['file', '/dev/null', 'w'],
    ['file', '/dev/null', 'r']
];

$process = proc_open($command . ' 2>&1', $descriptorspec, $pipes);

if (is_resource($process)) {
    $output = stream_get_contents($pipes[1]);
    $errorOutput = stream_get_contents($pipes[2]);
    $returnCode = proc_close($process);

    // Close all pipes
    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    $output = trim($output);
    if (!empty($errorOutput)) {
        $output .= "\nSTDERR: " . trim($errorOutput);
    }

    if (!empty($output)) {
        $output .= "\n";
    }

    echo json_encode([
        'output' => $output,
        'cwd' => getcwd(),
        'return_code' => $returnCode
    ]);
} else {
    echo json_encode([
        'output' => "Failed to execute command\n",
        'error' => true,
        'type' => 'execution_error'
    ]);
}
?>