<?php include SKINS_PATH . DEFAULT_SKIN . '/head.php'; ?>
<body>
    <?php include SKINS_PATH . DEFAULT_SKIN . '/header.php'; ?>

    <main class="content-wrapper">
        <div class="login-wrapper">
            <h2><?= LOGIN_INTRO ?></h2>

            <?php if (!empty($login_error)) : ?>
                <p class="error-message"><?= htmlspecialchars($login_error) ?></p>
            <?php endif; ?>

            <form action="index.php?task=login" method="post">
                <input type="text" name="username" placeholder="Utilizator" value="<?= htmlspecialchars($username_value) ?>" required>
                <input type="password" name="password" placeholder="Parolă" required>
                <button type="submit">Autentificare</button>
            </form>
        </div>
    </main>

    <?php include SKINS_PATH . DEFAULT_SKIN . '/footer.php'; ?>
</body>
</html>
