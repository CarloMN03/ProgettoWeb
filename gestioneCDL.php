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

    /* AGGIUNGI */
    if (($_POST['azione'] ?? '') === 'aggiungi') {
        $nome   = trim((string)($_POST['nomecdl'] ?? ''));
        $campus = trim((string)($_POST['sede'] ?? ''));
        $durata = (int)($_POST['durata'] ?? 3);
        $img    = '';

        /* Upload immagine (opzionale) */
        if (isset($_FILES['img']) && ($_FILES['img']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = 'img/cdl/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $uploadResult = $db->uploadImage($uploadPath, $_FILES['img']);
                if (is_array($uploadResult) && ($uploadResult[0] ?? 0) === 1) {
                    $img = (string)$uploadResult[1];
                } else {
                    $errore = 'Errore caricamento immagine: ' . (string)($uploadResult[1] ?? 'Upload non riuscito.');
                }
            } else {
                $errore = 'Errore durante il caricamento del file.';
            }
        }

        if ($errore === '' && $nome !== '' && $campus !== '') {
            if(empty($db->getLastIdCdl())) {
              $newidcdl = 1;
            } else {
              $newidcdl = $db->getLastIdCdl()[0]["lastidcdl"] + 1;
            }
            $ok = $db->insertCdl($newidcdl, $nome, $campus, $img, $durata);
            if ($ok) $messaggio = 'Corso aggiunto con successo!';
            else $errore = "Errore durante l'aggiunta del corso." . var_dump($newidcdl);
        } elseif ($errore === '') {
            $errore = 'Compila tutti i campi obbligatori per aggiungere un corso.';
        }
    }

    /* ELIMINA */
    if (($_POST['azione'] ?? '') === 'elimina') {
        $id = (int)($_POST['idcdl'] ?? 0);
        $cdlInfo = $db->getCdlById($id);

        if ($id > 0) {
          $esame = $db->getEsamiCdl($id);
          var_dump($esame);
          foreach ($esame as $e){
            $sg = $db->getStudyGroup($e["idesame"]);
        foreach ($sg as $s){
            $db->deleteStudyGroup($s["idstudygroup"]);
           }
            $db->deleteEsame($e["idesame"]);
          }
            $ok = $db->deleteCdl($id);
            if ($ok) {
                $imgOld = (string)($cdlInfo[0]['img'] ?? '');
                if ($imgOld !== '' && file_exists('img/cdl/' . $imgOld)) {
                    unlink('img/cdl/' . $imgOld);
                }
                $messaggio = 'Corso eliminato con successo!';
            } else {
                $errore = "Errore durante l'eliminazione del corso. Potrebbe avere esami associati.";
            }
        }
    }

    /* MODIFICA */
    if (($_POST['azione'] ?? '') === 'modifica') {
        $id     = (int)($_POST['idcdl'] ?? 0);
        $nome   = trim((string)($_POST['nomecdl'] ?? ''));
        $campus = trim((string)($_POST['sede'] ?? ''));

        $cdlCorrente = $db->getCdlById($id);
        $img = (string)($cdlCorrente[0]['img'] ?? '');

        /* Upload nuova immagine (opzionale) */
        if (isset($_FILES['img']) && $_FILES['img']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = 'img/cdl/';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $uploadResult = $db->uploadImage($uploadPath, $_FILES['img']);

                if ($uploadResult[0] === 1) {
                    // Nuova immagine caricata con successo
                    $nuovaImmagine = $uploadResult[1];

                    // Cancella la vecchia immagine se esisteva ed era diversa
                    if (!empty($img) && file_exists($uploadPath . $img) && $img !== $nuovaImmagine) {
                        unlink($uploadPath . $img);
                    }

                    // Aggiorna la variabile $img con il nuovo nome
                    $img = $nuovaImmagine;
                } else {
                    $errore = 'Errore caricamento immagine: ' . $uploadResult[1];
                }
            } else {
                $errore = 'Errore tecnico durante l\'upload del file.';
            }
        }

         if (empty($errore) && $id > 0 && !empty($nome) && !empty($campus)) {

            $result = $db->updateCdl($id, $nome, $campus, $img);

            if ($result) {
                $messaggio = 'Corso modificato con successo!';
                $editing = null;
                header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=' . urlencode($messaggio));
                exit;
            } else {
                $errore = 'Errore durante la modifica del corso nel database.';
            }
        } else if (empty($errore)) {
            $errore = 'Dati mancanti per la modifica.';
        }
    }

    /* Redirect con query string */
    if ($messaggio !== '' || $errore !== '') {
        $qs = [];
        if ($messaggio !== '') $qs['msg'] = $messaggio;
        if ($errore !== '') $qs['err'] = $errore;

        /* Se errore in modifica, riapri edit */
        if ($errore !== '' && isset($_POST['idcdl'])) {
            $qs['edit'] = (int)$_POST['idcdl'];
        }

        $self = basename((string)($_SERVER['PHP_SELF'] ?? 'gestioneCDL.php'));
        header('Location: ' . $self . '?' . http_build_query($qs));
        exit;
    }
}

/* GET edit */
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $cdl = $db->getCdlById($edit_id);
    if (!empty($cdl)) $editing = $cdl[0];
}

if (isset($_GET['msg'])) $messaggio = (string)$_GET['msg'];
if (isset($_GET['err'])) $errore = (string)$_GET['err'];

$corsiLaurea = $db->getAllCdl();

$corsiPerCampus = [];
foreach ($corsiLaurea as $corso) {
    $campus = (string)($corso['sede'] ?? 'Altro');
    $corsiPerCampus[$campus][] = $corso;
}

$totCdl = count($corsiLaurea);
$totCampus = count($corsiPerCampus);
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <link rel="stylesheet" href="style.css">
  <title>StudyBo - Gestione Corsi di Laurea</title>
</head>

<body onload="checkNotifica()">
  <a class="skip-link" href="#contenuto">Salta al contenuto</a>

  <?php include 'header.php'; ?>
  <?php include 'admin_nav.php'; ?>

  <main class="pagina" id="contenuto" role="main">

    <section class="hero" aria-labelledby="titolo-pagina">
      <h1 id="titolo-pagina" class="admin-title">Gestione Corsi di Laurea</h1>
      <p class="hero-meta admin-meta">
        Area Amministratore • Dipartimento di Ingegneria e Architettura • Campus di Cesena
      </p>
      <p class="hero-desc admin-desc">
        Gestisci i corsi di laurea: puoi aggiungere, modificare e rimuovere corsi (attenzione: l’eliminazione può impattare esami associati).
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

    <section class="sezione" aria-labelledby="stats-title">
      <div class="section-head">
        <h2 id="stats-title">📊 Statistiche</h2>
        <p class="section-hint">Panoramica rapida dei corsi presenti.</p>
      </div>

      <div class="stats-grid" role="list">
        <div class="stat-item" role="listitem">
          <div class="stat-number"><?= (int)$totCdl ?></div>
          <div class="stat-label">Corsi totali</div>
        </div>
        <div class="stat-item" role="listitem">
          <div class="stat-number"><?= (int)$totCampus ?></div>
          <div class="stat-label">Campus</div>
        </div>
      </div>
    </section>

    <section class="sezione" aria-labelledby="lista-title">
      <div class="section-head">
        <h2 id="lista-title">📚 Corsi di Laurea (<?= (int)$totCdl ?>)</h2>
        <p class="section-hint">Seleziona “Modifica” su una card per aprire il pannello di modifica a destra.</p>
      </div>

      <div class="admin-layout">
        <!-- Lista -->
        <div class="admin-list" aria-label="Elenco corsi di laurea">

          <?php if (empty($corsiLaurea)): ?>
            <div class="scadenze-box">
              <p class="no-margin">Nessun corso di laurea presente. Aggiungine uno dal pannello a destra.</p>
            </div>
          <?php else: ?>

            <?php foreach ($corsiPerCampus as $campus => $corsi): ?>
              <h3 class="admin-subtitle">Campus: <?= e($campus) ?></h3>

              <?php foreach ($corsi as $corso): ?>
                <?php
                  $id = (int)($corso['idcdl'] ?? 0);
                  $nome = (string)($corso['nomecdl'] ?? '');
                  $sede = (string)($corso['sede'] ?? '');
                  $durata = (int)($corso['durata'] ?? 0);
                  $img = (string)($corso['img'] ?? '');

                  $isEditingThis = ($editing && (int)$editing['idcdl'] === $id);

                  $fallback = $nome !== '' ? $nome : 'Corso';
                  if (function_exists('mb_strimwidth')) {
                    $fallbackShort = mb_strimwidth($fallback, 0, 22, '…', 'UTF-8');
                  } else {
                    $fallbackShort = (strlen($fallback) > 22) ? (substr($fallback, 0, 21) . '…') : $fallback;
                  }
                ?>

                <article class="card-sg <?= $isEditingThis ? 'is-editing' : '' ?>" aria-label="Corso di laurea: <?= e($nome) ?>">
                  <div class="sg-image-container" aria-hidden="true">
                    <?php if ($img !== ''): ?>
                      <img
                        src="img/cdl/<?= e($img) ?>"
                        alt=""
                        loading="lazy"
                        decoding="async"
                        onerror="this.remove();"
                      />
                    <?php endif; ?>
                    <span class="sg-fallback"><?= e($fallbackShort) ?></span>
                  </div>

                  <div class="sg-content">
                    <div class="sg-title-row">
                      <h3 class="sg-title"><?= e($nome) ?></h3>
                      <span class="badge badge-fisico" aria-label="Durata <?= (int)$durata ?> anni">
                        ⏳ <?= (int)$durata ?> anni
                      </span>
                    </div>

                    <div class="sg-info-grid" role="list">
                      <div role="listitem"><strong>🎓 Campus:</strong> <?= e($sede) ?></div>
                      <div role="listitem"><strong>🆔 ID:</strong> <?= (int)$id ?></div>
                      <div role="listitem">
                        <strong>🖼️ Immagine:</strong>
                        <?= $img !== '' ? 'Presente' : 'Non presente' ?>
                      </div>
                    </div>

                    <div class="admin-actions">
                      <a class="btn-secondary btn-small" href="?edit=<?= (int)$id ?>">Modifica</a>
                      <a class="btn-secondary btn-small" href="gestioneInfoCDL.php?idcdl=<?php echo (int)$id; ?>">Gestione Info Cdl</a>

                      <form method="POST" class="inline-form"
                            onsubmit="return confirm('Sei sicuro di voler eliminare questo corso? Verranno eliminati anche tutti gli esami associati!');">
                        <input type="hidden" name="azione" value="elimina">
                        <input type="hidden" name="idcdl" value="<?= (int)$id ?>">
                        <button class="btn-delete" type="submit">Elimina</button>
                      </form>
                    </div>
                  </div>
                </article>

              <?php endforeach; ?>
            <?php endforeach; ?>

          <?php endif; ?>
        </div>

        <aside class="admin-aside" aria-label="<?= $editing ? 'Modifica corso di laurea' : 'Aggiungi nuovo corso di laurea' ?>">
          <div class="sezione admin-panel">
            <h2 class="admin-panel-title">
              <?= $editing ? 'Modifica Corso di Laurea' : 'Aggiungi nuovo Corso di Laurea' ?>
            </h2>
            <p class="section-hint">
              I campi con * sono obbligatori.
              <?= $editing ? ' Se non carichi nulla, l’immagine attuale resta invariata.' : '' ?>
            </p>

            <form class="acc-form" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="azione" value="<?= $editing ? 'modifica' : 'aggiungi' ?>">
              <?php if ($editing): ?>
                <input type="hidden" name="idcdl" value="<?= (int)$editing['idcdl'] ?>">
              <?php endif; ?>

              <label class="acc-label" for="nomecdl">Nome del Corso di Laurea *</label>
              <input class="sb-input" type="text" id="nomecdl" name="nomecdl"
                     placeholder="Es: Laurea in Ingegneria Informatica"
                     value="<?= $editing ? e((string)$editing['nomecdl']) : '' ?>"
                     required>

              <label class="acc-label" for="sede">Campus *</label>
              <select class="sb-input" id="sede" name="sede" required>
                <option value="">-- Seleziona Campus --</option>
                <?php
                  $campusList = ['Cesena','Bologna','Forlì','Ravenna','Rimini'];
                  $curr = $editing ? (string)$editing['sede'] : '';
                  foreach ($campusList as $c) {
                    $sel = ($curr === $c) ? 'selected' : '';
                    echo '<option value="'.e($c).'" '.$sel.'>'.e($c).'</option>';
                  }
                ?>
              </select>

              <?php if (!$editing): ?>
                <label class="acc-label" for="durata">Durata (anni) *</label>
                <select class="sb-input" id="durata" name="durata" required>
                  <option value="3">3 anni (Laurea Triennale)</option>
                  <option value="2">2 anni (Laurea Magistrale)</option>
                  <option value="5">5 anni (Laurea Magistrale a Ciclo Unico)</option>
                </select>
              <?php else: ?>
                <p class="acc-help"><strong>Durata:</strong> <?= (int)($editing['durata'] ?? 0) ?> anni (non modificabile qui)</p>
              <?php endif; ?>

              <label class="acc-label" for="img">
                <?= $editing ? 'Cambia immagine (opzionale)' : 'Immagine (opzionale)' ?>
              </label>

              <?php if ($editing && !empty($editing['img'])): ?>
                <div class="img-preview">
                  <p class="acc-help img-preview-title"><strong>Attuale:</strong></p>
                  <img src="img/cdl/<?php echo htmlspecialchars($editing['img']); ?>" class="current-img-preview" alt="Anteprima immagine attuale">
                </div>
              <?php endif; ?>

              <input class="sb-input" type="file" id="img" name="img" accept="image/jpeg,image/png,image/gif,image/jpg">

              <p class="acc-help">
                Formati accettati: JPG, PNG, GIF (max 500KB).
              </p>

              <div class="admin-form-actions">
                <button class="btn-primary" type="submit">
                  <?= $editing ? 'Salva modifiche' : 'Aggiungi corso' ?>
                </button>

                <?php if ($editing): ?>
                  <a class="btn-secondary" href="<?= e(basename((string)($_SERVER['PHP_SELF'] ?? 'gestioneCDL.php'))) ?>">
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
