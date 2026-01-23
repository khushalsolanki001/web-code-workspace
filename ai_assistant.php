<?php
// ai_assistant.php - Enhanced AI Assistant with Advanced Logic
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);

class AIAssistant {
    private $apiKey;
    private $cacheFile;
    private $rateLimitFile;
    private $maxRequests = 60; // per minute
    private $cacheExpiry = 3600; // 1 hour
    private $contextWindow = 10; // Keep last 10 messages for context

    public function __construct() {
        $this->loadEnvironment();
        $this->validateApiKey();
        $this->cacheFile = __DIR__ . '/cache/ai_cache.json';
        $this->rateLimitFile = __DIR__ . '/cache/rate_limit.json';
        $this->ensureCacheDirectory();
    }

    private function loadEnvironment() {
        $envFile = __DIR__ . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if (strpos($line, '#') === 0 || strpos($line, '//') === 0 || empty($line)) {
                    continue;
                }
                
                if (strpos($line, '=') === false) {
                    continue;
                }
                
                list($key, $value) = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }
    }

    private function validateApiKey() {
        $this->apiKey = $_ENV['GROQ_API_KEY'] ?? '';
        if (empty($this->apiKey)) {
            $this->sendError('AI service not configured. Please set GROQ_API_KEY in .env file.');
        }
        
        // Validate API key format (basic validation)
        if (!preg_match('/^gsk_/', $this->apiKey)) {
            $this->sendError('Invalid API key format. Please check your GROQ_API_KEY.');
        }
    }

    private function ensureCacheDirectory() {
        $cacheDir = __DIR__ . '/cache';
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
    }

    private function checkRateLimit($clientIp) {
        $rateLimit = $this->getRateLimitData();
        $now = time();
        $windowStart = $now - 60; // 1 minute window

        // Clean old entries
        $rateLimit[$clientIp] = array_filter($rateLimit[$clientIp] ?? [], function($timestamp) use ($windowStart) {
            return $timestamp > $windowStart;
        });

        // Check if rate limit exceeded
        if (count($rateLimit[$clientIp]) >= $this->maxRequests) {
            $this->sendError('Rate limit exceeded. Please wait before making more requests.', 429);
        }

        // Add current request
        $rateLimit[$clientIp][] = $now;
        $this->saveRateLimitData($rateLimit);
    }

    private function getRateLimitData() {
        if (file_exists($this->rateLimitFile)) {
            $data = json_decode(file_get_contents($this->rateLimitFile), true);
            return is_array($data) ? $data : [];
        }
        return [];
    }

    private function saveRateLimitData($data) {
        file_put_contents($this->rateLimitFile, json_encode($data), LOCK_EX);
    }

    private function getCacheKey($prompt, $context, $language) {
        return md5(trim($prompt) . '|' . trim($context) . '|' . $language);
    }

    private function getCachedResponse($cacheKey) {
        if (!file_exists($this->cacheFile)) {
            return null;
        }

        $cache = json_decode(file_get_contents($this->cacheFile), true) ?: [];
        
        if (isset($cache[$cacheKey])) {
            $cached = $cache[$cacheKey];
            if ($cached['timestamp'] > (time() - $this->cacheExpiry)) {
                return $cached;
            }
        }
        
        return null;
    }

    private function setCachedResponse($cacheKey, $response) {
        $cache = [];
        if (file_exists($this->cacheFile)) {
            $cache = json_decode(file_get_contents($this->cacheFile), true) ?: [];
        }

        $cache[$cacheKey] = [
            'response' => $response,
            'timestamp' => time()
        ];

        // Clean old cache entries
        $cache = array_filter($cache, function($item) {
            return $item['timestamp'] > (time() - $this->cacheExpiry);
        });

        file_put_contents($this->cacheFile, json_encode($cache), LOCK_EX);
    }

    private function processRequest() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendError('Invalid JSON request format.');
        }

        $prompt = $data['prompt'] ?? '';
        $codeContext = $data['context'] ?? '';
        $language = $data['language'] ?? 'plaintext';
        $conversationId = $data['conversation_id'] ?? $this->generateConversationId();

        // Validate required fields
        if (empty(trim($prompt))) {
            $this->sendError('Please provide a prompt or question.');
        }

        // Check cache first
        $cacheKey = $this->getCacheKey($prompt, $codeContext, $language);
        $cachedResponse = $this->getCachedResponse($cacheKey);
        if ($cachedResponse) {
            return $cachedResponse['response'];
        }

        return $this->callAI($prompt, $codeContext, $language, $conversationId);
    }

    private function buildSystemMessage($language) {
        $messages = [
            'You are an expert coding assistant integrated into a VS Code-like IDE.',
            'Your role is to help developers write better code through:',
            '- Code explanations and documentation',
            '- Debugging and error resolution',
            '- Code optimization and refactoring suggestions',
            '- Best practices and design patterns',
            '- Code generation and completion',
            '',
            'Guidelines:',
            '- Be concise but thorough',
            '- Provide working code examples when relevant',
            '- Explain complex concepts clearly',
            '- Suggest modern, industry-standard approaches',
            '- Consider performance and security implications',
            '',
            'When providing code:',
            '- Use appropriate syntax highlighting',
            '- Include comments for complex logic',
            '- Show complete, runnable examples',
            '- Explain key decisions and trade-offs',
            '',
            'Current context: ' . ($language ? "Working with {$language} code." : "General programming assistance.")
        ];

        return implode("\n", $messages);
    }

    private function buildUserMessage($prompt, $codeContext, $language, $conversationHistory = []) {
        $message = '';

        // Add conversation context if available
        if (!empty($conversationHistory)) {
            $message .= "Previous conversation context:\n";
            foreach (array_slice($conversationHistory, -4) as $turn) {
                $message .= "Q: " . $turn['question'] . "\n";
                $message .= "A: " . $turn['answer'] . "\n\n";
            }
        }

        // Add code context if provided
        if (!empty(trim($codeContext))) {
            $message .= "Current code context (language: {$language}):\n";
            $message .= "```{$language}\n" . $codeContext . "\n```\n\n";
        }

        $message .= "User question: " . $prompt;
        
        return $message;
    }

    private function getConversationHistory($conversationId) {
        $historyFile = __DIR__ . '/cache/conversation_' . $conversationId . '.json';
        
        if (file_exists($historyFile)) {
            $history = json_decode(file_get_contents($historyFile), true) ?: [];
            return array_slice($history, -$this->contextWindow);
        }
        
        return [];
    }

    private function saveConversationHistory($conversationId, $question, $answer) {
        $historyFile = __DIR__ . '/cache/conversation_' . $conversationId . '.json';
        $history = $this->getConversationHistory($conversationId);
        
        $history[] = [
            'question' => $question,
            'answer' => $answer,
            'timestamp' => time()
        ];
        
        // Keep only last N messages
        $history = array_slice($history, -$this->contextWindow * 2);
        
        file_put_contents($historyFile, json_encode($history), LOCK_EX);
    }

    private function generateConversationId() {
        return bin2hex(random_bytes(16));
    }

    private function callAI($prompt, $codeContext, $language, $conversationId) {
        $conversationHistory = $this->getConversationHistory($conversationId);
        
        $systemMessage = $this->buildSystemMessage($language);
        $userMessage = $this->buildUserMessage($prompt, $codeContext, $language, $conversationHistory);

        // Build messages array for API
        $messages = [
            ['role' => 'system', 'content' => $systemMessage]
        ];

        // Add limited conversation history
        foreach (array_slice($conversationHistory, -4) as $turn) {
            $messages[] = ['role' => 'user', 'content' => $turn['question']];
            $messages[] = ['role' => 'assistant', 'content' => $turn['answer']];
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        // Prepare API request with optimized parameters
        $requestData = [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => $messages,
            'temperature' => 0.3, // Lower for more consistent responses
            'max_tokens' => $this->calculateMaxTokens($prompt, $codeContext),
            'top_p' => 0.9,
            'frequency_penalty' => 0.1,
            'presence_penalty' => 0.1
        ];

        try {
            $response = $this->makeAPICall($requestData);
            $this->saveConversationHistory($conversationId, $prompt, $response);
            
            // Cache the response
            $cacheKey = $this->getCacheKey($prompt, $codeContext, $language);
            $this->setCachedResponse($cacheKey, $response);
            
            return $response;
        } catch (Exception $e) {
            $this->sendError('AI service error: ' . $e->getMessage());
        }
    }

    private function calculateMaxTokens($prompt, $codeContext) {
        $totalLength = strlen($prompt) + strlen($codeContext);
        
        if ($totalLength < 500) {
            return 1000; // Short responses for simple queries
        } elseif ($totalLength < 2000) {
            return 2000; // Medium responses
        } else {
            return 3000; // Longer responses for complex queries
        }
    }

    private function makeAPICall($requestData) {
        $groqUrl = 'https://api.groq.com/openai/v1/chat/completions';
        
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $groqUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($requestData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey,
                'User-Agent: VSCode-Clone/1.0'
            ],
            CURLOPT_TIMEOUT => 30, // 30 seconds timeout
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);

        if ($error) {
            throw new Exception("Connection error: $error");
        }

        // Handle different HTTP status codes
        switch ($httpCode) {
            case 200:
                // Success
                break;
            case 401:
                throw new Exception("Invalid API key. Please check your credentials.");
            case 403:
                throw new Exception("API access forbidden. Check your permissions.");
            case 429:
                throw new Exception("API rate limit exceeded. Please try again later.");
            case 500:
                throw new Exception("AI service temporarily unavailable. Please try again.");
            case 503:
                throw new Exception("AI service overloaded. Please try again in a moment.");
            default:
                throw new Exception("API error (HTTP $httpCode): $response");
        }

        $result = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid response format from AI service.");
        }

        if (!isset($result['choices'][0]['message']['content'])) {
            throw new Exception("No response received from AI service.");
        }

        return trim($result['choices'][0]['message']['content']);
    }

    private function sendError($message, $httpCode = 400) {
        http_response_code($httpCode);
        echo json_encode([
            'success' => false,
            'error' => $message,
            'timestamp' => time()
        ]);
        exit;
    }

    public function handleRequest() {
        // Rate limiting based on client IP
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $this->checkRateLimit($clientIp);

        try {
            $response = $this->processRequest();
            echo json_encode([
                'success' => true,
                'response' => $response,
                'timestamp' => time(),
                'cached' => false
            ]);
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }
}

// Handle the request
$aiAssistant = new AIAssistant();
$aiAssistant->handleRequest();
?>