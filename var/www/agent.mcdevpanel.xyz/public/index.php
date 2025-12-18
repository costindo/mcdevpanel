<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if ($path === '/logout') {
    require __DIR__ . '/../app/routes/logout.php';
    exit;
}

if (!is_logged_in()) {
    require __DIR__ . '/../app/routes/login.php';
    exit;
}

if ($path === '/login') {
    header('Location: /');
    exit;
}

require __DIR__ . '/../app/routes/dashboard.php';
