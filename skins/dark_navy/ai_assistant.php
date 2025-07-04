<?php
include __DIR__ . '/head.php';
include __DIR__ . '/header.php';
?>

<main class="main-panel flex flex-col items-center p-6"
      style="min-height: calc(100vh - 160px); background-color: var(--bg-color);">
  <div class="content-container max-w-4xl w-full">

    <!-- Banner Nelu -->
    <div class="flex flex-row-reverse items-center mb-6">
      <img src="<?php echo SKIN_URL ?>/images/nelu_portrait.png"
           alt="Nelu"
           class="w-16 h-16 rounded-full ml-4 border-2 border-primary" />
      <h2 class="text-3xl font-bold text-primary">Salut, sunt Nelu</h2>
    </div>

    <!-- Eroare de input -->
    <?php if (!empty($error)): ?>
      <div class="mb-4 bg-error-light text-error px-4 py-2 rounded">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <!-- Comanda generată de AI -->
    <?php if (!empty($response)): ?>
      <div class="mb-4 bg-response-light text-response px-4 py-3 rounded whitespace-pre-wrap">
        <strong>Comandă AI:</strong><br>
        <?php echo nl2br(htmlspecialchars($response)); ?>
      </div>
    <?php endif; ?>

    <!-- Rezultatul execuției automate -->
    <?php if (!empty($aiExecResult)): ?>
      <div class="mb-6 bg-panel-bg text-response px-4 py-3 rounded whitespace-pre-wrap">
        <strong>Rezultat execuție:</strong><br>
        <?php echo nl2br(htmlspecialchars($aiExecResult)); ?>
      </div>
    <?php endif; ?>

    <!-- Formular -->
    <form method="post" action="" class="w-full bg-panel-bg p-6 rounded">
      <div class="mb-4">
        <label for="command" class="block text-secondary mb-2">
          Introdu comanda pentru Nelu:
        </label>
        <input type="text"
               id="command"
               name="command"
               value="<?php echo htmlspecialchars($command ?? ''); ?>"
               placeholder="Ex: Crează un director tests"
               class="w-full p-3 rounded bg-input-bg border border-input-border text-white focus:outline-none focus:ring-2 focus:ring-primary" />
      </div>
      <div class="text-right">
        <button type="submit"
                class="px-6 py-2 bg-button-bg hover:bg-button-hover text-button-text font-semibold rounded">
          Trimite
        </button>
      </div>
    </form>

  </div>
</main>

<?php
include __DIR__ . '/footer.php';
?>
