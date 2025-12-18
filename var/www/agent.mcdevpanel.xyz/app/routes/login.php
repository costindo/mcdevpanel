<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? null;

    if (!verify_csrf($submittedToken)) {
        $error = 'Invalid request token. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $config = auth_config();

        if ($username === $config['username'] && password_verify($password, $config['password_hash'])) {
            log_in_user($config['username']);
            header('Location: /');
            exit;
        }

        $error = 'Invalid username or password.';
    }
}

$csrf = csrf_token();
require __DIR__ . '/../views/login.php';
