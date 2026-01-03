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

// Prepare request in OpenAI format
$postData = [
    'model' => $model,
    'messages' => [
        [
            'role' => 'system',
            'content' => 'You are a helpful coding assistant. Provide clear, concise, and accurate responses about programming and code.'
        ],
        [
            'role' => 'user',
            'content' => $message
        ]
    ],
    'max_tokens' => 1000,
    'temperature' => 0.7
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