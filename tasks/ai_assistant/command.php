<?php
require_once('/var/config/openai.php');

$prompt = trim($_POST['command'] ?? '');
if ($prompt === '') {
    echo "⚠️ Comanda este goală.";
    return;
}

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $OPENAI_API_KEY
    ],
    CURLOPT_POSTFIELDS => json_encode([
        'model' => 'gpt-3.5-turbo',
        'messages' => [[
            'role' => 'system',
            'content' => 'Ești un administrator de server Linux cu Apache. Rădăcina site-ului este /var/www/mcdevpanel/. 
Dacă ți se cere să instalezi un pachet, folosește comenzi cu sudo apt install -y. 
Dacă se cere un subdomeniu, creează folderul în /var/www/mcdevpanel/, un fișier .conf în /etc/apache2/sites-available/, 
apoi folosește a2ensite și systemctl reload apache2. Răspunde doar cu o singură comandă shell, fără explicații.'
        ], [
            'role' => 'user',
            'content' => $prompt
        ]],
        'temperature' => 0.3
    ])
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "Eroare cURL: $error";
    return;
}

$data = json_decode($response, true);
if (!isset($data['choices'][0]['message']['content'])) {
    echo "Eroare OpenAI: răspuns invalid.";
    return;
}

echo trim($data['choices'][0]['message']['content']);
