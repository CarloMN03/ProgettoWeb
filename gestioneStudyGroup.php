<?php
declare(strict_types=1);

require_once 'db/DatabaseHelper.php';
require_once 'db/config.php';

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

/* Funzioni formato data/ora */
function formattaData(?string $data): string {
    if (!$data) return '';
    $dataObj = DateTime::createFromFormat('Y-m-d', $data);
    return $dataObj ? $dataObj->format('d/m/Y') : $data;
}

function formattaOra(?string $ora): string {
    if (!$ora) return '';
    $oraObj = DateTime::createFromFormat('H:i:s', $ora);
    return $oraObj ? $oraObj->format('H:i') : $ora;
}

/* POST: elimina */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['azione'] ?? '') === 'elimina') {
        $id = (int)($_POST['idstudygroup'] ?? 0);
        if ($id > 0) {
            $ok = $db->deleteStudyGroup($id);
            if ($ok) $messaggio = 'Study Group eliminato con successo!';
            else $errore = "Errore durante l'eliminazione dello Study Group.";
        }
    }

    $qs = [];
    if ($messaggio !== '') $qs['msg'] = $messaggio;
    if ($errore !== '') $qs['err'] = $errore;

    $self = basename((string)($_SERVER['PHP_SELF'] ?? 'gestioneStudyGroup.php'));
    header('Location: ' . $self . ($qs ? ('?' . http_build_query($qs)) : ''));
    exit;
}

/* GET: messaggi */
if (isset($_GET['msg'])) $messaggio = (string)$_GET['msg'];
if (isset($_GET['err'])) $errore = (string)$_GET['err'];

$studyGroups = $db->getStudyGroupsWithDetails();

/* Statistiche */
$totali = is_array($studyGroups) ? count($studyGroups) : 0;
$partecipantiTot = 0;
if ($totali > 0) {
    foreach ($studyGroups as $sg) {
        $partecipantiTot += (int)($sg['Partecipanti'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="style.css">
  <title>StudyBo - Gestione Study Group</title>
</head>

<body>
  <a class="skip-link" href="#contenuto">Salta al contenuto</a>

  <?php include 'header.php'; ?>
  <?php include 'admin_nav.php'; ?>

  <main class="pagina" id="contenuto" role="main">
   
    <section class="hero" aria-labelledby="titolo-pagina">
      <h1 id="titolo-pagina" style="margin-top:0;">Gestione Study Group</h1>
      <p class="hero-meta" style="margin-top:6px;">
        Area Amministratore • Dipartimento di Ingegneria e Architettura • Campus di Cesena
      </p>
      <p class="hero-desc" style="margin-bottom:0;">
        Qui puoi visualizzare i gruppi di studio e, se necessario, eliminarli.
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
        <h2 id="stats-title">📊 Statistiche</h2>
        <p class="section-hint">Panoramica rapida dei gruppi presenti nel sistema.</p>
      </div>

      <div class="stats-grid" role="list">
        <div class="stat-item" role="listitem">
          <div class="stat-number"><?= (int)$totali ?></div>
          <div class="stat-label">Totali</div>
        </div>

        <div class="stat-item" role="listitem">
          <div class="stat-number"><?= (int)$partecipantiTot ?></div>
          <div class="stat-label">Partecipanti totali</div>
        </div>
      </div>
    </section>

    <!-- Lista Study Group -->
    <section class="sezione" aria-labelledby="lista-title">
      <div class="section-head">
        <h2 id="lista-title">📚 Tutti gli Study Group (<?= (int)$totali ?>)</h2>
        <p class="section-hint">Ogni card mostra i dettagli del gruppo. Puoi eliminare un gruppo dalla relativa card.</p>
      </div>

      <?php if (empty($studyGroups)): ?>
        <div class="scadenze-box">
          <p style="margin:0;">Nessuno Study Group presente.</p>
        </div>
      <?php else: ?>

        <?php foreach ($studyGroups as $sg): ?>
          <?php
            $tema = (string)($sg['tema'] ?? 'Study Group');
            $luogo = (string)($sg['luogo'] ?? '');
            $isOnline = stripos($luogo, 'online') !== false;

            $immagineEsame = (string)($sg['ImgEsame'] ?? '');
            $nomeEsame = (string)($sg['NomeEsame'] ?? '');
            $nomeCdl = (string)($sg['NomeCdl'] ?? '');
            $lingua = (string)($sg['NomeLingua'] ?? '');
            $partecipanti = (int)($sg['Partecipanti'] ?? 0);

            $dataFmt = formattaData($sg['data'] ?? null);
            $oraFmt  = formattaOra($sg['ora'] ?? null);

            $fallback = $nomeEsame !== '' ? $nomeEsame : $tema;
            if (function_exists('mb_strimwidth')) {
              $fallbackShort = mb_strimwidth($fallback, 0, 22, '…', 'UTF-8');
            } else {
              $fallbackShort = (strlen($fallback) > 22) ? (substr($fallback, 0, 21) . '…') : $fallback;
            }
          ?>

          <article class="card-sg" aria-label="Study Group: <?= e($tema) ?>">
            <div class="sg-image-container" aria-hidden="true">
              <?php if ($immagineEsame !== ''): ?>
                <img
                  src="<?= e($immagineEsame) ?>"
                  alt=""
                  loading="lazy"
                  decoding="async"
                  onerror="this.remove();"
                >
              <?php endif; ?>
              <span class="sg-fallback"><?= e($fallbackShort) ?></span>
            </div>

            <div class="sg-content">
              <div class="sg-title-row">
                <h3 class="sg-title"><?= e($tema) ?></h3>
                <span class="badge <?= $isOnline ? 'badge-online' : 'badge-fisico' ?>">
                  <?= $isOnline ? '💻 Online' : '📍 Fisico' ?>
                </span>
              </div>

              <div class="sg-info-grid" role="list">
                <div role="listitem"><strong>📚 Esame:</strong> <?= e($nomeEsame) ?></div>
                <div role="listitem"><strong>🎓 CDL:</strong> <?= e($nomeCdl) ?></div>
                <div role="listitem"><strong>📍 Luogo:</strong> <?= e($luogo) ?></div>
                <div role="listitem"><strong>📅 Data:</strong> <?= e($dataFmt) ?></div>
                <div role="listitem"><strong>🕐 Ora:</strong> <?= e($oraFmt) ?></div>
                <div role="listitem"><strong>🌍 Lingua:</strong> <?= e($lingua) ?></div>
                <div role="listitem"><strong>👥 Partecipanti:</strong> <?= (int)$partecipanti ?></div>
              </div>

              <form method="POST" class="sg-actions"
                    onsubmit="return confirm('Sei sicuro di voler eliminare questo Study Group?');">
                <input type="hidden" name="azione" value="elimina">
                <input type="hidden" name="idstudygroup" value="<?= (int)($sg['idstudygroup'] ?? 0) ?>">
                <button class="btn-delete" type="submit">Elimina Study Group</button>
              </form>
            </div>
          </article>

        <?php endforeach; ?>
      <?php endif; ?>
    </section>
  </main>
  <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>