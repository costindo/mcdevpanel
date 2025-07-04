<div class="main-header">
    <div class="header-content">
        <div class="logo">MC Dev Panel</div>

        <?php if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true): ?>
            <form action="index.php?task=logout" method="post">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        <?php endif; ?>
    </div>
</div>