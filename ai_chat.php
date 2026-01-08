<?php
// ai_chat.php
// Secure proxy for Groq API (free, fast, high limits)

header('Content-Type: application/json');

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);
$message = $data['message'] ?? '';

if (empty($message)) {
    echo json_encode(['error' => 'No message provided']);
    exit;
}

// Load Groq API Key from .env file
$apiKey = '';

$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        if (strpos($line, '=') === false) continue;   // Skip invalid lines
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        // Remove surrounding quotes
        if (preg_match('/^"(.*)"$/', $value, $m)) $value = $m[1];
        elseif (preg_match("/^'(.*)'$/", $value, $m)) $value = $m[1];
        
        if ($name === 'GROQ_API_KEY') {
            $apiKey = $value;
            break;
        }
    }
}

// Fallback to environment variable
if (empty($apiKey)) {
    $apiKey = getenv('GROQ_API_KEY');
}

if (empty($apiKey)) {
    echo json_encode(['error' => 'Groq API Key not configured. Please set GROQ_API_KEY in your .env file. Get one free at https://console.groq.com/keys']);
    exit;
}

// Clean the key
$apiKey = trim($apiKey);
if (preg_match('/^"(.*)"$/', $apiKey, $m)) $apiKey = $m[1];
elseif (preg_match("/^'(.*)'$/", $apiKey, $m)) $apiKey = $m[1];

// Choose a powerful and fast model (all free on Groq)
$model = "llama-3.3-70b-versatile";  
// Other great free options:
// "llama-3.1-70b-versatile"
// "mixtral-8x7b-32768" 
// "llama-3.1-8b-instant" (faster, slightly smaller)

// Groq's OpenAI-compatible endpoint
$url = "https://api.groq.com/openai/v1/chat/completions";

// Get current code context if provided
$currentCode = $data['currentCode'] ?? '';

// Build enhanced system prompt for programming
$systemPrompt = "You are an expert programming and coding assistant specialized in software development. Your role is to:

1. **Code Analysis & Debugging**: Analyze code, identify bugs, suggest fixes, and explain issues clearly
2. **Code Generation**: Write clean, efficient, well-documented code following best practices
3. **Code Review**: Review code for improvements, optimization opportunities, and security issues
4. **Explanations**: Explain programming concepts, algorithms, and code logic in detail
5. **Best Practices**: Suggest modern coding patterns, design principles, and industry standards

**Guidelines:**
- Always provide code examples when relevant
- Use proper code formatting with syntax highlighting markers
- Explain your reasoning and approach
- Consider performance, security, and maintainability
- Support multiple programming languages
- Provide complete, runnable code when possible
- Include comments for complex logic

**Code Formatting:**
- Wrap code blocks in triple backticks with language identifier (e.g., ```javascript, ```python, ```php)
- Use inline code with single backticks for variables, functions, and short code snippets
- Format code with proper indentation and structure";

// Build user message with context
$userMessage = $message;
if (!empty($currentCode)) {
    $userMessage = "Current code in editor:\n```\n" . substr($currentCode, 0, 2000) . "\n```\n\nUser question: " . $message;
}

// Prepare request in OpenAI format
$postData = [
    'model' => $model,
    'messages' => [
        [
            'role' => 'system',
            'content' => $systemPrompt
        ],
        [
            'role' => 'user',
            'content' => $userMessage
        ]
    ],
    'max_tokens' => 2000, // Increased for longer code explanations
    'temperature' => 0.3 // Lower temperature for more focused, accurate code responses
];

// Initialize cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'Curl error: ' . $curlError]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Parse response
$responseData = json_decode($response, true);

if ($httpCode !== 200) {
    $errorMsg = 'API request failed (HTTP ' . $httpCode . ')';
    if (isset($responseData['error']['message'])) {
        $errorMsg = $responseData['error']['message'];
    } elseif (isset($responseData['error'])) {
        $errorMsg = json_encode($responseData['error']);
    }
    echo json_encode(['error' => $errorMsg]);
    exit;
}

// Extract generated text
if (isset($responseData['choices'][0]['message']['content'])) {
    $generatedText = $responseData['choices'][0]['message']['content'];
    
    echo json_encode([
        'choices' => [
            [
                'message' => [
                    'content' => trim($generatedText)
                ]
            ]
        ]
    ]);
} else {
    echo json_encode(['error' => 'Unexpected response format', 'raw' => $responseData]);
}
?>