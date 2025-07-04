<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Includem constantele de cale și mesaje
include_once 'includes/paths.php';
include_once 'includes/messages.php';

// Gestionăm logout-ul direct din index.php
if (isset($_GET['task']) && $_GET['task'] === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Gestionăm autentificarea doar pentru task=login
if (isset($_GET['task']) && $_GET['task'] === 'login') {
    require_once CREDENTIALS_CONFIG;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = $_POST['username'] ?? '';
        $pass = $_POST['password'] ?? '';
        $peppered = $CONFIG_PEPPER . $pass;

        if ($user === $CONFIG_USER && password_verify($peppered, $CONFIG_HASH)) {
            $_SESSION['authenticated'] = true;
            $_SESSION['LAST_ACTIVITY'] = time();
            header('Location: index.php');
            exit;
        } else {
            // Setăm variabila pentru mesaj de eroare
            $login_error = 'Date de autentificare incorecte.';
        }
    }
}

// Dacă există un task definit și nu este login sau logout
$task = $_GET['task'] ?? '';

if ($task && $task !== 'login' && $task !== 'logout') {
    $task_file = __DIR__ . "/tasks/{$task}.php";
    if (file_exists($task_file)) {
        include $task_file;
        exit;
    } else {
        echo "<p style='color:red; text-align:center;'>Nu pot executa încă acest task: <strong>" . htmlspecialchars($task) . "</strong></p>";
        exit;
    }
}

// Dacă suntem autentificat, includem panel.php
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    include_once 'includes/panel.php';
} else {
    // Pregătim variabila $login_error pentru login_form.php
    if (!isset($login_error)) {
        $login_error = '';
    }
    include_once 'includes/login_form.php';
}
