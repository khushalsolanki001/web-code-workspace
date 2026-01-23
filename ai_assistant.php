<?php
// ai_assistant.php
header('Content-Type: application/json');

// Load environment variables
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos(trim($line), '//') === 0)
            continue;
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

$apiKey = $_ENV['GROQ_API_KEY'] ?? '';
if (!$apiKey) {
    die(json_encode(['error' => 'GROQ_API_KEY not found in .env']));
}

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
$prompt = $data['prompt'] ?? '';
$codeContext = $data['context'] ?? '';
$language = $data['language'] ?? 'plaintext';

if (!$prompt) {
    die(json_encode(['error' => 'No prompt provided']));
}

// Build system message
$systemMessage = "You are an expert coding assistant integrated into a VS Code-like IDE. ";
$systemMessage .= "Help users with code suggestions, debugging, explanations, and improvements. ";
$systemMessage .= "Be concise and provide code examples when relevant.";

// Build user message with context
$userMessage = $prompt;
if ($codeContext) {
    $userMessage = "Current code context (language: $language):\n```$language\n$codeContext\n```\n\nUser question: $prompt";
}

// Prepare Groq API request
$groqUrl = 'https://api.groq.com/openai/v1/chat/completions';
$requestData = [
    'model' => 'llama-3.3-70b-versatile',
    'messages' => [
        ['role' => 'system', 'content' => $systemMessage],
        ['role' => 'user', 'content' => $userMessage]
    ],
    'temperature' => 0.7,
    'max_tokens' => 2000
];

// Make API call
$ch = curl_init($groqUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    die(json_encode(['error' => 'Groq API error: ' . $response]));
}

$result = json_decode($response, true);
$aiResponse = $result['choices'][0]['message']['content'] ?? 'No response from AI';

echo json_encode([
    'success' => true,
    'response' => $aiResponse
]);
?>