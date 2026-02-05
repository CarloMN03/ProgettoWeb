<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$isAdmin = isset($_SESSION["amministratore"]) && (int)$_SESSION["amministratore"] === 1;
if (!$isAdmin) { return; }

if (!function_exists('e')) {
  function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
}

$current = basename((string)($_SERVER['PHP_SELF'] ?? ''));

$links = [
  ['href' => 'gestioneCDL.php',       'label' => 'Gestione CDL'],
  ['href' => 'gestioneEsami.php',     'label' => 'Gestione Esami'],
  ['href' => 'gestioneStudyGroup.php','label' => 'Gestione Study Group'],
  ['href' => 'gestioneUtenti.php',    'label' => 'Gestione Utenti'],
];
?>

<nav class="admin-nav" aria-label="Sezioni amministratore">
  <ul class="admin-nav-list">
    <?php foreach ($links as $l): 
      $isCurrent = ($current === $l['href']);
    ?>
      <li class="admin-nav-item">
        <a
          class="admin-nav-link <?= $isCurrent ? 'is-active' : '' ?>"
          href="<?= e($l['href']) ?>"
          <?= $isCurrent ? 'aria-current="page"' : '' ?>
        >
          <?= e($l['label']) ?>
        </a>
      </li>
    <?php endforeach; ?>

    <li class="admin-nav-item admin-nav-right">
      <a class="admin-nav-link is-danger" href="logout.php">Log out</a>
    </li>
  </ul>
</nav>