<!DOCTYPE html>
<html lang="en">
<?php include SKINS_PATH . DEFAULT_SKIN . '/head.php'; ?>
<body>
<?php include SKINS_PATH . DEFAULT_SKIN . '/header.php'; ?>

<main>
  <div class="panel-icons-grid">
    <a href="https://mcdevpanel.xyz/phpmyadmin" target="_blank" class="panel-icon">
      <i class="fas fa-database"></i>
      <span>phpMyAdmin</span>
    </a>
    <a href="index.php?task=file_manager" class="panel-icon">
      <i class="fas fa-folder-open"></i>
      <span>File Manager</span>
    </a>
    <a href="index.php?task=mail" class="panel-icon">
      <i class="fas fa-envelope"></i>
      <span>Mail</span>
    </a>
    <a href="index.php?task=settings" class="panel-icon">
      <i class="fas fa-cog"></i>
      <span>Settings</span>
    </a>
    <a href="index.php?task=backups" class="panel-icon">
      <i class="fas fa-cloud-download-alt"></i>
      <span>Backups</span>
    </a>
    <a href="index.php?task=logs" class="panel-icon">
      <i class="fas fa-file-alt"></i>
      <span>Logs</span>
    </a>
    <a href="index.php?task=ai_assistant" class="panel-icon">
      <i class="fas fa-robot"></i>
      <span>AI Assistant</span>
    </a>
  </div>
</main>

<?php include SKINS_PATH . DEFAULT_SKIN . '/footer.php'; ?>
</body>
</html>
