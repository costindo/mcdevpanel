<?php
// tasks/ai_assistant.php



// 1. Include paths și setup skin constants
require_once __DIR__ . '/../includes/paths.php';
if (!defined('SKIN') && defined('DEFAULT_SKIN')) {
    define('SKIN', DEFAULT_SKIN);
}
if (!defined('SKIN_URL') && defined('SKINS_PATH') && defined('SKIN')) {
    define('SKIN_URL', '/' . rtrim(SKINS_PATH, '/') . '/' . SKIN);
}

// 2. Logger
require_once __DIR__ . '/../includes/Logger.php';
$logger = new Logger(LOG_FILE);
$logger->info("AI Assistant task started");

// 3. Config OpenAI
require_once OPENAI_CONFIG;

// 4. Funcție de apel API
function call_openai_api(string $model, string $prompt): ?string {
    $api_key = OPENAI_API_KEY;
    $url     = "https://api.openai.com/v1/chat/completions";
    $data = [
        'model'       => $model,
        'messages'    => [['role'=>'user','content'=>$prompt]],
        'max_tokens'  => 1000,
        'temperature' => 0.5,
    ];
    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer {$api_key}",
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,   $headers);
    curl_setopt($ch, CURLOPT_POST,         true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,   json_encode($data));
    $resp = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close($ch);
        return null;
    }
    curl_close($ch);
    $json = json_decode($resp, true);
    return $json['choices'][0]['message']['content'] ?? null;
}

// 5. Procesare input și execuție
$error         = null;
$response      = null;
$aiExecResult  = null;
$command       = trim($_POST['command'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($command === '') {
        $error = "❌ Te rog introdu o comandă.";
    } else {
        $logger->info("User prompt: {$command}");
        $apiPrompt = "Ești Nelu, un asistent AI. Primește comenzi naturale de creare directoare și editare fișiere și răspunde EXCLUSIV prin comenzi prefixate cu dir_create: sau file_write:. Comanda utilizatorului: {$command}";
        $response = call_openai_api('gpt-4', $apiPrompt);
        if ($response === null) {
            $error = "❌ Eroare la comunicarea cu API-ul OpenAI.";
            $logger->error("OpenAI API error for prompt: {$command}");
        } else {
            $logger->info("AI response: {$response}");
            $trimmed = trim($response);
            if (preg_match('/^(dir_create:|file_write:)/', $trimmed)) {
                $_SESSION['suggested_command'] = $trimmed;
                ob_start();
                include __DIR__ . '/ai_assistant/exec_command.php';
                $aiExecResult = ob_get_clean();
            }
        }
    }
}

// 6. Încarcă șablonul skin-ului
include __DIR__ . "/../skins/" . SKIN . "/ai_assistant.php";
