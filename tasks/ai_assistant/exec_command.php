<?php
// tasks/ai_assistant/exec_command.php
// Versiune îmbunătățită: suport pentru comenzi multiple și execuție fișier/director

// Rădăcina proiectului (se apelează din index.php unde sesiunea e deja pornită)
$projectRoot = realpath(__DIR__ . '/../../');
if (!$projectRoot) {
    echo "❌ Nu pot determina rădăcina proiectului.";
    return;
}

// Preluăm comanda(e) sugerată(e)
$suggested = $_SESSION['suggested_command'] ?? '';
if (trim($suggested) === '') {
    echo "⚠️ Nicio comandă de executat.";
    return;
}

// Împărțim pe linii pentru comenzi multiple
$lines = preg_split('/\r?\n/', $suggested);
$results = [];

foreach ($lines as $cmd) {
    $cmd = trim($cmd);
    if ($cmd === '') continue;

    // Blacklist minim
    if (preg_match('/rm\s+-rf|shutdown|reboot|:>|\|\||&&/', $cmd)) {
        $results[] = "❌ Comanda blocată pentru siguranță: {$cmd}";
        continue;
    }

    // 1) dir_create:/cale
    if (preg_match('/^dir_create:(.+)$/', $cmd, $m)) {
        $raw = trim($m[1]);
        $fullPath = ($raw[0] === '/')
            ? $raw
            : $projectRoot . '/' . ltrim($raw, '/');
        $fullPath = str_replace('//','/',$fullPath);
        if (strpos($fullPath, $projectRoot) !== 0) {
            $results[] = "❌ Cale invalidă: {$raw}";
        } elseif (!is_dir($fullPath) && @mkdir($fullPath, 0755, true)) {
            $results[] = "✅ Director creat: {$fullPath}";
        } elseif (is_dir($fullPath)) {
            $results[] = "⚠️ Director deja există: {$fullPath}";
        } else {
            $results[] = "❌ Nu s-a putut crea directorul: {$fullPath}";
        }
        continue;
    }

    // 2) file_write:/cale:base64
    if (preg_match('/^file_write:([^:]+):(.+)$/s', $cmd, $m)) {
        $raw = trim($m[1]);
        $b64 = $m[2];
        $fullPath = ($raw[0] === '/')
            ? $raw
            : $projectRoot . '/' . ltrim($raw, '/');
        $fullPath = str_replace('//','/',$fullPath);
        if (strpos($fullPath, $projectRoot) !== 0) {
            $results[] = "❌ Cale invalidă: {$raw}";
        } else {
            $dir = dirname($fullPath);
            if (!is_dir($dir)) @mkdir($dir, 0755, true);
            $data = base64_decode($b64);
            if (@file_put_contents($fullPath, $data) !== false) {
                $results[] = "✅ Fișier scris (base64): {$fullPath}";
            } else {
                $results[] = "❌ Eroare la scrierea fișierului: {$fullPath}";
            }
        }
        continue;
    }

    // 3) file_write:/cale, raw content după virgulă
    if (preg_match('/^file_write:([^,]+),(.*)$/s', $cmd, $m)) {
        $raw = trim($m[1]);
        $content = ltrim($m[2]);
        $fullPath = ($raw[0] === '/')
            ? $raw
            : $projectRoot . '/' . ltrim($raw, '/');
        $fullPath = str_replace('//','/',$fullPath);
        if (strpos($fullPath, $projectRoot) !== 0) {
            $results[] = "❌ Cale invalidă: {$raw}";
        } else {
            $dir = dirname($fullPath);
            if (!is_dir($dir)) @mkdir($dir, 0755, true);
            if (@file_put_contents($fullPath, $content) !== false) {
                $results[] = "✅ Fișier scris (raw): {$fullPath}";
            } else {
                $results[] = "❌ Eroare la crearea fișierului: {$fullPath}";
            }
        }
        continue;
    }

    // 4) fallback: execuție shell simplă pentru comenzi necritice
    $output = shell_exec($cmd . ' 2>&1');
    $results[] = $output !== null
        ? trim($output)
        : "✅ Comanda a fost executată: {$cmd}";
}

// Afișăm toate rezultatele
echo nl2br(htmlspecialchars(implode("\n", $results)));