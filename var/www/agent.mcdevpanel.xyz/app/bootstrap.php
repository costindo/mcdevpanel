<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Path to the credentials configuration outside the web root
const AUTH_CONFIG = __DIR__ . '/../../../config/agent.mcdevpanel.xyz/auth.php';

if (!file_exists(AUTH_CONFIG)) {
    throw new RuntimeException('Authentication config file not found.');
}

$authConfig = require AUTH_CONFIG;

if (!is_array($authConfig) || !isset($authConfig['username'], $authConfig['password_hash'])) {
    throw new RuntimeException('Invalid auth configuration.');
}

/**
 * Return the loaded authentication configuration.
 */
function auth_config(): array
{
    global $authConfig;

    return $authConfig;
}

/**
 * Determine if the current session represents an authenticated user.
 */
function is_logged_in(): bool
{
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Ensure the user is authenticated, otherwise redirect to the login form.
 */
function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: /login');
        exit;
    }
}

/**
 * Generate or return the CSRF token stored in the session.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Validate a submitted CSRF token against the session value.
 */
function verify_csrf(?string $token): bool
{
    return isset($_SESSION['csrf_token'], $token) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Mark the session as authenticated.
 */
function log_in_user(string $username): void
{
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
}

/**
 * Clear the session and remove the session cookie.
 */
function log_out_user(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
    }

    session_destroy();
}
