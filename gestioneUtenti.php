<?php
declare(strict_types=1);

require_once 'db/DatabaseHelper.php';
require_once 'db/config.php';

if (!defined('UPLOAD_DIR')) {
    define("UPLOAD_DIR", "./upload/");
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Escape HTML */
if (!function_exists('e')) {
    function e(string $s): string {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }
}

/* Solo admin */
$isAdmin = isset($_SESSION["amministratore"]) && (int)$_SESSION["amministratore"] === 1;
if (!$isAdmin) {
    header('Location: home.php');
    exit;
}

$db = new DatabaseHelper(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

$messaggio = '';
$errore = '';

/* POST azioni */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $azione = (string)($_POST['azione'] ?? '');
    $username = trim((string)($_POST['username'] ?? ''));

    if ($username !== '') {
        $ok = false;

        if ($azione === 'elimina') {
    try {
        $ok = (bool)$db->deleteUser($username);
        $ok ? $messaggio = 'Utente eliminato con successo!' : $errore = "Errore durante l'eliminazione dell'utente.";
    } catch (mysqli_sql_exception $e) {
        $errore = "Impossibile eliminare l'utente: ha dati collegati (es. preferenze).";
    }
}


        if ($azione === 'blocca') {
            $ok = (bool)$db->blockUser($username);
            $ok ? $messaggio = 'Utente bloccato con successo!' : $errore = "Errore durante il blocco dell'utente.";
        }

        if ($azione === 'sblocca') {
            $ok = (bool)$db->unblockUser($username);
            $ok ? $messaggio = 'Utente sbloccato con successo!' : $errore = "Errore durante lo sblocco dell'utente.";
        }

        if ($azione === 'rendi_admin') {
            $ok = (bool)$db->makeUserAdmin($username);
            $ok ? $messaggio = 'Utente promosso ad amministratore!' : $errore = 'Errore durante la promozione.';
        }

        if ($azione === 'rimuovi_admin') {
            $ok = (bool)$db->removeUserAdmin($username);
            $ok ? $messaggio = 'Privilegi di amministratore rimossi!' : $errore = 'Errore durante la rimozione dei privilegi.';
        }
    }

    /* Redirect pulito con query string corretta */
    $qs = [];
    if ($messaggio !== '') $qs['msg'] = $messaggio;
    if ($errore !== '') $qs['err'] = $errore;

    $self = basename((string)($_SERVER['PHP_SELF'] ?? 'gestioneUtenti.php'));
    header('Location: ' . $self . ($qs ? ('?' . http_build_query($qs)) : ''));
    exit;
}

/* GET msg/err */
if (isset($_GET['msg'])) $messaggio = (string)$_GET['msg'];
if (isset($_GET['err'])) $errore = (string)$_GET['err'];

/* Dati */
$utentiNormali   = $db->getNormalUsers();
$utentiBloccati  = $db->getBlockedUsers();
$amministratori  = $db->getAdminUsers();

$totAttivi = is_array($utentiNormali) ? count($utentiNormali) : 0;
$totBlocc  = is_array($utentiBloccati) ? count($utentiBloccati) : 0;
$totAdmin  = is_array($amministratori) ? count($amministratori) : 0;

/* Iniziali per “avatar” testuale */
function iniziali(string $nome, string $cognome): string {
    $n = trim($nome);
    $c = trim($cognome);
    $a = $n !== '' ? mb_substr($n, 0, 1, 'UTF-8') : '';
    $b = $c !== '' ? mb_substr($c, 0, 1, 'UTF-8') : '';
    $out = strtoupper($a . $b);
    return $out !== '' ? $out : 'U';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="style.css">
  <title>StudyBo - Gestione Utenti</title>
</head>

<body onload="checkNotifica()">
  <a class="skip-link" href="#contenuto">Salta al contenuto</a>

  <?php include 'header.php'; ?>
  <?php include 'admin_nav.php'; ?>

  <main class="pagina" id="contenuto" role="main">

    <section class="hero" aria-labelledby="titolo-pagina">
      <h1 id="titolo-pagina" class="admin-title">Gestione Utenti</h1>
      <p class="hero-meta admin-meta">
        Area Amministratore • Dipartimento di Ingegneria e Architettura • Campus di Cesena
      </p>
      <p class="hero-desc admin-desc">
        Da qui puoi bloccare/sbloccare utenti, eliminarli e gestire i privilegi di amministratore.
      </p>
    </section>

    <?php if ($messaggio): ?>
      <div class="alert alert-success" role="status" aria-live="polite">
        <?= e($messaggio) ?>
      </div>
    <?php endif; ?>

    <?php if ($errore): ?>
      <div class="alert alert-error" role="alert" aria-live="assertive">
        <?= e($errore) ?>
      </div>
    <?php endif; ?>

    <!-- Statistiche -->
    <section class="sezione" aria-labelledby="stats-title">
      <div class="section-head">
        <h2 id="stats-title">📊 Statistiche sistema</h2>
        <p class="section-hint">Panoramica rapida dello stato utenti.</p>
      </div>

      <div class="stats-grid" role="list">
        <div class="stat-item" role="listitem">
          <div class="stat-number"><?= (int)$totAttivi ?></div>
          <div class="stat-label">Utenti attivi</div>
        </div>
        <div class="stat-item" role="listitem">
          <div class="stat-number"><?= (int)$totBlocc ?></div>
          <div class="stat-label">Utenti bloccati</div>
        </div>
        <div class="stat-item" role="listitem">
          <div class="stat-number"><?= (int)$totAdmin ?></div>
          <div class="stat-label">Amministratori</div>
        </div>
      </div>
    </section>

    <!-- Layout admin -->
    <section class="sezione" aria-labelledby="lista-title">
      <div class="section-head">
        <h2 id="lista-title">Gestione</h2>
        <p class="section-hint">Le azioni sono immediate. Usa con attenzione “Elimina”.</p>
      </div>

      <div class="admin-layout">
        <!-- Colonna sinistra: attivi + bloccati -->
        <div class="admin-list">

          <!-- UTENTI ATTIVI -->
          <h3 class="admin-subtitle">Utenti attivi (<?= (int)$totAttivi ?>)</h3>

          <?php if (empty($utentiNormali)): ?>
            <div class="scadenze-box">
              <p style="margin:0;">Nessun utente attivo presente nel sistema.</p>
            </div>
          <?php else: ?>
            <?php foreach ($utentiNormali as $u): ?>
              <?php
                $nome = (string)($u['nome'] ?? '');
                $cognome = (string)($u['cognome'] ?? '');
                $username = (string)($u['username'] ?? '');
                $img = (string)($u['imguser'] ?? '');
                $initials = iniziali($nome, $cognome);
              ?>
              <article class="card-sg user-card" aria-label="Utente: <?= e($nome . ' ' . $cognome) ?>">
                <div class="sg-image-container user-avatar" aria-hidden="true">
                  <?php if(!empty($img)): ?>
                    <img src="<?php echo UPLOAD_DIR . e($img); ?>" 
                         alt="Foto profilo di <?php echo e($nome . ' ' . $cognome); ?>">
                  <?php else: ?>
                    <span class="sg-fallback"><?= e($initials) ?></span>
                  <?php endif; ?>
                </div>

                <div class="sg-content">
                  <div class="sg-title-row">
                    <h3 class="sg-title"><?= e(trim($nome . ' ' . $cognome)) ?></h3>
                    <span class="badge badge-user">👤 Attivo</span>
                  </div>

                  <div class="sg-info-grid" role="list">
                    <div role="listitem"><strong>Username:</strong> <?= e($username) ?></div>
                  </div>

                  <div class="admin-actions">
                    <form method="POST" class="inline-form"
                          onsubmit="return confirm('Sei sicuro di voler bloccare questo utente?');">
                      <input type="hidden" name="azione" value="blocca">
                      <input type="hidden" name="username" value="<?= e($username) ?>">
                      <button class="btn-warn" type="submit">Blocca</button>
                    </form>

                    <form method="POST" class="inline-form"
                          onsubmit="return confirm('Confermi di voler rendere questo utente amministratore?');">
                      <input type="hidden" name="azione" value="rendi_admin">
                      <input type="hidden" name="username" value="<?= e($username) ?>">
                      <button class="btn-neutral" type="submit">Rendi amministratore</button>
                    </form>

                    <form method="POST" class="inline-form"
                          onsubmit="return confirm('Sei sicuro di voler eliminare questo utente? Questa azione è irreversibile!');">
                      <input type="hidden" name="azione" value="elimina">
                      <input type="hidden" name="username" value="<?= e($username) ?>">
                      <button class="btn-delete" type="submit">Elimina</button>
                    </form>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>

          <!-- UTENTI BLOCCATI -->
          <h3 class="admin-subtitle">Utenti bloccati (<?= (int)$totBlocc ?>)</h3>

          <?php if (empty($utentiBloccati)): ?>
            <div class="scadenze-box">
              <p style="margin:0;">Nessun utente bloccato.</p>
            </div>
          <?php else: ?>
            <?php foreach ($utentiBloccati as $u): ?>
              <?php
                $nome = (string)($u['nome'] ?? '');
                $cognome = (string)($u['cognome'] ?? '');
                $username = (string)($u['username'] ?? '');
                $img = (string)($u['imguser'] ?? '');
                $initials = iniziali($nome, $cognome);
              ?>
              <article class="card-sg user-card is-blocked" aria-label="Utente bloccato: <?= e($nome . ' ' . $cognome) ?>">
                <div class="sg-image-container user-avatar" aria-hidden="true">
                  <?php if(!empty($img)): ?>
                    <img src="<?php echo UPLOAD_DIR . e($img); ?>" 
                         alt="Foto profilo di <?php echo e($nome . ' ' . $cognome); ?>">
                  <?php else: ?>
                    <span class="sg-fallback"><?= e($initials) ?></span>
                  <?php endif; ?>
                </div>

                <div class="sg-content">
                  <div class="sg-title-row">
                    <h3 class="sg-title"><?= e(trim($nome . ' ' . $cognome)) ?></h3>
                    <span class="badge badge-blocked">🔒 Bloccato</span>
                  </div>

                  <div class="sg-info-grid" role="list">
                    <div role="listitem"><strong>Username:</strong> <?= e($username) ?></div>
                  </div>

                  <div class="admin-actions">
                    <form method="POST" class="inline-form">
                      <input type="hidden" name="azione" value="sblocca">
                      <input type="hidden" name="username" value="<?= e($username) ?>">
                      <button class="btn-success" type="submit">Sblocca</button>
                    </form>

                    <form method="POST" class="inline-form"
                          onsubmit="return confirm('Sei sicuro di voler eliminare questo utente? Questa azione è irreversibile!');">
                      <input type="hidden" name="azione" value="elimina">
                      <input type="hidden" name="username" value="<?= e($username) ?>">
                      <button class="btn-delete" type="submit">Elimina</button>
                    </form>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Colonna destra: amministratori -->
        <aside class="admin-aside">
          <div class="sezione admin-panel">
            <h2 class="admin-panel-title">👑 Amministratori (<?= (int)$totAdmin ?>)</h2>
            <p class="section-hint">Puoi rimuovere i privilegi o eliminare l’account.</p>

            <?php if (empty($amministratori)): ?>
              <div class="scadenze-box">
                <p style="margin:0;">Nessun amministratore presente.</p>
              </div>
            <?php else: ?>
              <?php foreach ($amministratori as $a): ?>
                <?php
                  $nome = (string)($a['nome'] ?? '');
                  $cognome = (string)($a['cognome'] ?? '');
                  $username = (string)($a['username'] ?? '');
                  $img = (string)($a['imguser'] ?? '');
                  $bloccato = !empty($a['Bloccato']);
                  $initials = iniziali($nome, $cognome);
                ?>
                <article class="card-sg user-card <?= $bloccato ? 'is-blocked' : '' ?>" aria-label="Amministratore: <?= e($nome . ' ' . $cognome) ?>">
                  <div class="sg-image-container user-avatar" aria-hidden="true">
                    <?php if(!empty($img)): ?>
                      <img src="<?php echo UPLOAD_DIR . e($img); ?>" 
                           alt="Foto profilo di <?php echo e($nome . ' ' . $cognome); ?>">
                    <?php else: ?>
                      <span class="sg-fallback"><?= e($initials) ?></span>
                    <?php endif; ?>
                  </div>

                  <div class="sg-content">
                    <div class="sg-title-row">
                      <h3 class="sg-title"><?= e(trim($nome . ' ' . $cognome)) ?></h3>
                      <span class="badge badge-admin">👑 Admin</span>
                    </div>

                    <div class="sg-info-grid" role="list">
                      <div role="listitem"><strong>Username:</strong> <?= e($username) ?></div>
                      <?php if ($bloccato): ?>
                        <div role="listitem"><strong>Stato:</strong> <span class="badge badge-blocked">🔒 Bloccato</span></div>
                      <?php endif; ?>
                    </div>

                    <div class="admin-actions">
                      <form method="POST" class="inline-form"
                            onsubmit="return confirm('Sei sicuro di voler rimuovere i privilegi di amministratore a questo utente?');">
                        <input type="hidden" name="azione" value="rimuovi_admin">
                        <input type="hidden" name="username" value="<?= e($username) ?>">
                        <button class="btn-neutral" type="submit">Rimuovi admin</button>
                      </form>

                      <form method="POST" class="inline-form"
                            onsubmit="return confirm('Sei sicuro di voler eliminare questo amministratore? Questa azione è irreversibile!');">
                        <input type="hidden" name="azione" value="elimina">
                        <input type="hidden" name="username" value="<?= e($username) ?>">
                        <button class="btn-delete" type="submit">Elimina</button>
                      </form>
                    </div>
                  </div>
                </article>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </aside>
      </div>
    </section>
  </main>
     <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>
