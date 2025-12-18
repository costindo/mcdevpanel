<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>
    <p>Welcome<?php echo isset($_SESSION['username']) ? ', ' . htmlspecialchars((string) $_SESSION['username'], ENT_QUOTES, 'UTF-8') : ''; ?>.</p>
    <p><a href="/logout">Logout</a></p>
</body>
</html>
