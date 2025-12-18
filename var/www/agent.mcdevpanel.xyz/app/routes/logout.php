<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

log_out_user();
header('Location: /login');
exit;
