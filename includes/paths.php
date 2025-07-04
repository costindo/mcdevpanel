<?php

// Config path (în afara web root)
define('CONFIG_PATH', '/var/config/panel.mcdevpanel.xyz');
define('OPENAI_CONFIG', CONFIG_PATH . '/openai.php');
define('CREDENTIALS_CONFIG', CONFIG_PATH . '/mcdevpanel_credentials.php');

// Directorii
define('SKINS_PATH', 'skins/');
define('IMAGES_PATH', 'images/');
define('TASK_PATH', 'tasks/');
define('DEFAULT_SKIN', 'dark_navy');

// Path to log file
define('LOG_FILE', __DIR__ . '/../logs/app.log');
