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
$isAdmin = isset($_SESSION["amministratore"]) && (int)($_SESSION["amministratore"]) === 1;
if (!$isAdmin) {
    header('Location: home.php');
    exit;
}

$db = new DatabaseHelper(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

$messaggio = '';
$errore = '';
$editing = null;

/* POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $azione = (string)($_POST['azione'] ?? '');

    /* AGGIUNGI */
    if ($azione === 'aggiungi') {
        $nome = trim((string)($_POST['nomeesame'] ?? ''));
        $anno = (int)($_POST['annoesame'] ?? 0);
        $cdl  = (int)($_POST['idcdl'] ?? 0);
        $imgesame = '';

        if (isset($_FILES['imgesame']) && ($_FILES['imgesame']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['imgesame']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = 'img/esami/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $uploadResult = $db->uploadImage($uploadPath, $_FILES['imgesame']);

                if (is_array($uploadResult) && ($uploadResult[0] ?? 0) === 1) {
                    $imgesame = (string)$uploadResult[1];
                } else {
                    $errore = 'Errore caricamento immagine: ' . (string)($uploadResult[1] ?? 'Upload non riuscito.');
                }
            } else {
                $errore = 'Errore durante il caricamento del file.';
            }
        }

        if (empty($errore) && !empty($nome) && $anno > 0 && $cdl > 0) {
            $result = $db->insertEsame($nome, $anno, $cdl, $imgesame);
            if ($result) {
                $messaggio = 'Esame aggiunto con successo!';
            } else {
                $errore = 'Errore durante l\'aggiunta dell\'esame.';
            }
        } else if (empty($errore)) {
            $errore = 'Compila tutti i campi per aggiungere un esame.';
        }
    }

    /* ELIMINA */
    if ($azione === 'elimina') {
        $id = (int)($_POST['idesame'] ?? 0);
        if ($id > 0) {
            $sg = $db->getStudyGroup($id);
        foreach ($sg as $s){
            $db->deleteStudyGroup($s["idstudygroup"]);
           }
            $ok = (bool)$db->deleteEsame($id);
            $ok ? $messaggio = 'Esame eliminato con successo!' : $errore = "Errore durante l'eliminazione dell'esame.";
        }
    }

    /* MODIFICA */
    if ($azione === 'modifica') {
        $id   = (int)($_POST['idesame'] ?? 0);
        $nome = trim((string)($_POST['nomeesame'] ?? ''));
        $anno = (int)($_POST['annoesame'] ?? 0);
        $cdl  = (int)($_POST['idcdl'] ?? 0);

        $esameCorrente = $db->getEsameById($id);
        $imgesame = (string)($esameCorrente[0]['imgesame'] ?? '');

        if (isset($_FILES['imgesame']) && ($_FILES['imgesame']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['imgesame']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = 'img/esami/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $uploadResult = $db->uploadImage($uploadPath, $_FILES['imgesame']);

                if (is_array($uploadResult) && ($uploadResult[0] ?? 0) === 1) {
                    $nuova = (string)$uploadResult[1];
                    if ($imgesame !== '' && file_exists($uploadPath . $imgesame) && $imgesame !== $nuova) {
                        unlink($uploadPath . $imgesame);
                    }
                    $imgesame = $nuova;
                } else {
                    $errore = 'Errore caricamento immagine: ' . (string)($uploadResult[1] ?? 'Upload non riuscito.');
                }
            } else {
                $errore = 'Errore durante il caricamento del file.';
            }
        }

        if (empty($errore) && $id > 0 && !empty($nome) && $anno > 0 && $cdl > 0) {
            $result = $db->updateEsame($id, $nome, $anno, $cdl, $imgesame);
            if ($result) {
                $messaggio = 'Esame modificato con successo!';
            } else {
                $errore = "Errore durante la modifica dell'esame.";
            }
        } elseif (empty($errore)) {
            $errore = "Errore durante la modifica dell'esame.";
        }
    }

    /* Redirect pulito (no doppio ?) */
    $qs = [];
    if ($messaggio !== '') $qs['msg'] = $messaggio;
    if ($errore !== '') $qs['err'] = $errore;

    $self = basename((string)($_SERVER['PHP_SELF'] ?? 'gestioneEsami.php'));
    header('Location: ' . $self . ($qs ? ('?' . http_build_query($qs)) : ''));
    exit;
}

/* GET edit */
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $esame = $db->getEsameById($edit_id);
    if (!empty($esame)) $editing = $esame[0];
}

/* GET msg/err */
if (isset($_GET['msg'])) $messaggio = (string)$_GET['msg'];
if (isset($_GET['err'])) $errore = (string)$_GET['err'];

$corsiLaurea = $db->getAllCdl();
$esami = $db->getAllEsami();

/* Raggruppa esami per CDL */
$esamiPerCdl = [];
foreach ($esami as $ex) {
    $idCdl = (int)($ex['idcdl'] ?? 0);
    $esamiPerCdl[$idCdl][] = $ex;
}

/* Mappa CDL */
$cdlMap = [];
foreach ($corsiLaurea as $cdl) {
    $cdlMap[(int)$cdl['idcdl']] = $cdl;
}

$totEsami = is_array($esami) ? count($esami) : 0;
$totCdlConEsami = count($esamiPerCdl);
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- CSS FUORI cartella -->
  <link rel="stylesheet" href="style.css">
  <title>StudyBo - Gestione Esami</title>
</head>

<body>
  <a class="skip-link" href="#contenuto">Salta al contenuto</a>

  <?php include 'header.php'; ?>
  <?php include 'admin_nav.php'; ?>

  <main class="pagina" id="contenuto" role="main">

    <section class="hero" aria-labelledby="titolo-pagina">
      <h1 id="titolo-pagina" class="admin-title">Gestione Esami</h1>
      <p class="hero-meta admin-meta">
        Area Amministratore • Dipartimento di Ingegneria e Architettura • Campus di Cesena
      </p>
      <p class="hero-desc admin-desc">
        Aggiungi, modifica o elimina esami. Puoi associare ogni esame a un Corso di Laurea e a un anno.
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
        <p class="section-hint">Panoramica rapida degli esami presenti.</p>
      </div>

      <div class="stats-grid" role="list">
        <div class="stat-item" role="listitem">
          <div class="stat-number"><?= (int)$totEsami ?></div>
          <div class="stat-label">Esami totali</div>
        </div>
        <div class="stat-item" role="listitem">
          <div class="stat-number"><?= (int)$totCdlConEsami ?></div>
          <div class="stat-label">CDL con esami</div>
        </div>
      </div>
    </section>

    <!-- Layout admin: lista + form -->
    <section class="sezione" aria-labelledby="lista-title">
      <div class="section-head">
        <h2 id="lista-title">📚 Esami presenti (<?= (int)$totEsami ?>)</h2>
        <p class="section-hint">Gli esami sono raggruppati per Corso di Laurea.</p>
      </div>

      <div class="admin-layout">

        <!-- lista-->
        <div class="admin-list" aria-label="Elenco esami">

          <?php if (empty($esami)): ?>
            <div class="scadenze-box">
              <p class="no-margin">Nessun esame presente. Aggiungine uno dal pannello a destra.</p>
            </div>
          <?php else: ?>

            <?php foreach ($corsiLaurea as $cdl): ?>
              <?php
                $idCdl = (int)$cdl['idcdl'];
                if (!isset($esamiPerCdl[$idCdl])) continue;
              ?>
              <h3 class="admin-subtitle"><?= e((string)$cdl['nomecdl']) ?></h3>

              <?php foreach ($esamiPerCdl[$idCdl] as $esame): ?>
                <?php
                  $idEsame = (int)($esame['idesame'] ?? 0);
                  $nomeEs  = (string)($esame['nomeesame'] ?? '');
                  $annoEs  = (int)($esame['annoesame'] ?? 0);
                  $imgEs   = (string)($esame['imgesame'] ?? '');

                  $isEditingThis = ($editing && (int)$editing['idesame'] === $idEsame);

                  $fallback = $nomeEs !== '' ? $nomeEs : 'Esame';
                  if (function_exists('mb_strimwidth')) {
                    $fallbackShort = mb_strimwidth($fallback, 0, 22, '…', 'UTF-8');
                  } else {
                    $fallbackShort = (strlen($fallback) > 22) ? (substr($fallback, 0, 21) . '…') : $fallback;
                  }
                ?>

                <article class="card-sg exam-card <?= $isEditingThis ? 'is-editing' : '' ?>" aria-label="Esame: <?= e($nomeEs) ?>">
                  <div class="sg-image-container" aria-hidden="true">
                    <?php if ($imgEs !== ''): ?>
                      <img
                        src="img/esami/<?= e($imgEs) ?>"
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
                      <h3 class="sg-title"><?= e($nomeEs) ?></h3>
                      <span class="badge badge-year" aria-label="Anno <?= (int)$annoEs ?>">
                        📅 <?= (int)$annoEs ?>° anno
                      </span>
                    </div>

                    <div class="sg-info-grid" role="list">
                      <div role="listitem"><strong>🎓 CDL:</strong> <?= e((string)$cdl['nomecdl']) ?></div>
                      <div role="listitem"><strong>📍 Campus:</strong> <?= e((string)$cdl['sede']) ?></div>
                      <div role="listitem"><strong>🆔 ID Esame:</strong> <?= (int)$idEsame ?></div>
                      <div role="listitem"><strong>🖼️ Immagine:</strong> <?= $imgEs !== '' ? 'Presente' : 'Non presente' ?></div>
                    </div>

                    <div class="admin-actions">
                      <a class="btn-secondary btn-small" href="?edit=<?= (int)$idEsame ?>">Modifica</a>

                      <form method="POST" class="inline-form"
                            onsubmit="return confirm('Sei sicuro di voler eliminare questo esame?');">
                        <input type="hidden" name="azione" value="elimina">
                        <input type="hidden" name="idesame" value="<?= (int)$idEsame ?>">
                        <button class="btn-delete" type="submit">Elimina</button>
                      </form>
                    </div>
                  </div>
                </article>

              <?php endforeach; ?>
            <?php endforeach; ?>

          <?php endif; ?>
        </div>

        <!-- form -->
        <aside class="admin-aside" aria-label="<?= $editing ? 'Modifica esame' : 'Aggiungi nuovo esame' ?>">
          <div class="sezione admin-panel">
            <h2 class="admin-panel-title"><?= $editing ? 'Modifica Esame' : 'Aggiungi nuovo Esame' ?></h2>
            <p class="section-hint">I campi sono obbligatori tranne l’immagine.</p>

            <form class="acc-form" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="azione" value="<?= $editing ? 'modifica' : 'aggiungi' ?>">
              <?php if ($editing): ?>
                <input type="hidden" name="idesame" value="<?= (int)$editing['idesame'] ?>">
              <?php endif; ?>

              <label class="acc-label" for="nomeesame">Nome dell'Esame *</label>
              <input class="sb-input" type="text" id="nomeesame" name="nomeesame"
                     placeholder="Inserisci nome esame"
                     value="<?= $editing ? e((string)$editing['nomeesame']) : '' ?>"
                     required>

              <label class="acc-label" for="annoesame">Anno di Corso *</label>
              <select class="sb-input" id="annoesame" name="annoesame" required>
                <option value="">-- Seleziona l'anno --</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <option value="<?= $i ?>" <?= ($editing && (int)$editing['annoesame'] === $i) ? 'selected' : '' ?>>
                    <?= $i ?>° anno
                  </option>
                <?php endfor; ?>
              </select>

              <label class="acc-label" for="idcdl">Corso di Laurea *</label>
              <select class="sb-input" id="idcdl" name="idcdl" required>
                <option value="">-- Seleziona un Corso di Laurea --</option>
                <?php foreach ($corsiLaurea as $c): ?>
                  <option value="<?= (int)$c['idcdl'] ?>" <?= ($editing && (int)$editing['idcdl'] === (int)$c['idcdl']) ? 'selected' : '' ?>>
                    <?= e((string)$c['nomecdl']) ?> — <?= e((string)$c['sede']) ?>
                  </option>
                <?php endforeach; ?>
              </select>

              <label class="acc-label" for="imgesame">Immagine (opzionale)</label>

              <?php if ($editing && !empty($editing['imgesame'])): ?>
                <div class="img-preview">
                  <p class="acc-help img-preview-title"><strong>Attuale:</strong></p>
                  <img class="current-img-preview"
                       src="img/esami/<?= e((string)$editing['imgesame']) ?>"
                       alt="Anteprima immagine attuale">
                  <p class="acc-help">File: <?= e((string)$editing['imgesame']) ?></p>
                </div>
              <?php endif; ?>

              <input class="sb-input sb-file" type="file" id="imgesame" name="imgesame" accept="image/jpeg,image/png,image/gif,image/jpg">
              <p class="acc-help">Formati: JPG, JPEG, PNG, GIF (max 500KB)</p>

              <div class="admin-form-actions">
                <button class="btn-primary" type="submit">
                  <?= $editing ? 'Salva modifiche' : 'Aggiungi esame' ?>
                </button>

                <?php if ($editing): ?>
                  <a class="btn-secondary" href="<?= e(basename((string)($_SERVER['PHP_SELF'] ?? 'gestioneEsami.php'))) ?>">
                    Annulla
                  </a>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </aside>
      </div>
    </section>
  </main>
  <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>

