<?php
// Preluăm valorile introduse anterior (dacă există)
$username_value = $_POST['username'] ?? '';
$login_error = $_SERVER['REQUEST_METHOD'] === 'POST' ? 'Date de autentificare incorecte.' : '';

// Includem interfața vizuală pentru formularul de login
include_once SKINS_PATH . DEFAULT_SKIN . '/login_form.php';
?>