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


if (isset($_POST["submit-desc-cdl"], $_POST["descrizione-cdl"])) {
    $updateDesc = $db->updateDescCdl($_GET["idcdl"], $_POST["descrizione-cdl"]);
    $updateDesc ? $messaggio = "Descrizione modificata con successo." : $errore = "Errore modifica descrizione.";
}

if (isset($_POST["submit-studia"], $_POST["studio-cdl"], $_POST["idstudio"])) {
    $updateStudio = $db->updateCosaSiStudia($_GET["idcdl"], $_POST["idstudio"], $_POST["studio-cdl"]);
    $updateStudio ? $messaggio = "Materia modificata con successo." : $errore = "Errore modifica materia.";
}

if (isset($_POST["add-submit-studia"], $_POST["add-studio-cdl"], $_POST["idstudio"])) {
    $last = $db->getLastIdStudia($_GET["idcdl"]);
    $newidstudio = empty($last) ? 1 : ((int)$last[0]["lastidstudia"] + 1);

    $ok = $db->addCosaSiStudia($_GET["idcdl"], $newidstudio, $_POST["add-studio-cdl"]);
    $ok ? $messaggio = "Materia aggiunta con successo." : $errore = "Errore aggiunta materia.";
}

if (isset($_POST["remove-studia"], $_POST["idstudio"])) {
    $removeStudio = $db->removeCosaSiStudia($_GET["idcdl"], $_POST["idstudio"]);
    $removeStudio ? $messaggio = "Materia rimossa con successo." : $errore = "Errore rimozione materia.";
}

if (isset($_POST["submit-adatto"], $_POST["adatto-cdl"], $_POST["idadatto"])) {
    $updateAdatto = $db->updateAdatto($_GET["idcdl"], $_POST["idadatto"], $_POST["adatto-cdl"]);
    $updateAdatto ? $messaggio = "A chi è adatto modificato con successo." : $errore = "Errore modifica a chi è adatto.";
}

if (isset($_POST["add-submit-adatto"], $_POST["add-adatto-cdl"], $_POST["idadatto"])) {
    $last = $db->getLastIdAdatto($_GET["idcdl"]);
    $newidadatto = empty($last) ? 1 : ((int)$last[0]["lastidadatto"] + 1);

    $ok = $db->addAdatto($_GET["idcdl"], $newidadatto, $_POST["add-adatto-cdl"]);
    $ok ? $messaggio = "A chi è adatto aggiunto con successo." : $errore = "Errore aggiunta a chi è adatto.";
}

if (isset($_POST["remove-adatto"], $_POST["idadatto"])) {
    $removeAdatto = $db->removeAdatto($_GET["idcdl"], $_POST["idadatto"]);
    $removeAdatto ? $messaggio = "A chi è adatto rimosso con successo." : $errore = "Errore rimozione a chi è adatto.";
}

if (isset($_POST["submit-sbocchi"], $_POST["sbocchi-cdl"], $_POST["idsbocchi"])) {
    $updateSbocchi = $db->updateSbocchi($_GET["idcdl"], $_POST["idsbocchi"], $_POST["sbocchi-cdl"]);
    $updateSbocchi ? $messaggio = "Sbocchi modificati con successo." : $errore = "Errore modifica sbocchi.";
}

if (isset($_POST["add-submit-sbocchi"], $_POST["add-sbocchi-cdl"], $_POST["idsbocchi"])) {
    $last = $db->getLastIdSbocchi($_GET["idcdl"]);
    $newidsbocchi = empty($last) ? 1 : ((int)$last[0]["lastidsbocchi"] + 1);

    $ok = $db->addSbocchi($_GET["idcdl"], $newidsbocchi, $_POST["add-sbocchi-cdl"]);
    $ok ? $messaggio = "Sbocco aggiunto con successo." : $errore = "Errore aggiunta sbocco.";
}

if (isset($_POST["remove-sbocchi"], $_POST["idsbocchi"])) {
    $removeSbocco = $db->removeSbocchi($_GET["idcdl"], $_POST["idsbocchi"]);
    $removeSbocco ? $messaggio = "Sbocco rimosso con successo." : $errore = "Errore rimozione sbocco.";
}

if (isset($_POST["remove-materia"], $_POST["idmateria"])) {
    $removeMateria = $db->removeEsame($_GET["idcdl"], $_POST["idmateria"]);
    $removeMateria ? $messaggio = "Materia principale rimossa con successo." : $errore = "Errore rimozione materia principale.";
}

if (isset($_POST["add-submit-materia"], $_POST["add-materia-cdl"])) {
    $updateEsame = $db->addEsamePrincipale($_GET["idcdl"], $_POST["add-materia-cdl"]);
    $updateEsame ? $messaggio = "Materia principale aggiunta con successo." : $errore = "Errore inserimento materia principale.";
}

if (isset($_POST["submit-argomento"], $_POST["idesame"], $_POST["idargomento"], $_POST["argomento-cdl"])) {
    $updateArgomento = $db->updateArgomento($_GET["idcdl"], $_POST["idesame"], $_POST["idargomento"], $_POST["argomento-cdl"]);
    $updateArgomento ? $messaggio = "Argomento modificato con successo." : $errore = "Errore modifica argomento.";
}

if (isset($_POST["add-submit-argomento"], $_POST["add-argomento-cdl"], $_POST["idesame"])) {
    $last = $db->getLastIdArgomento($_GET["idcdl"], $_POST["idesame"]);
    $newidargomento = empty($last) ? 1 : ((int)$last[0]["lastidargomento"] + 1);

    $ok = $db->addArgomento($_GET["idcdl"], $_POST["idesame"], $newidargomento, $_POST["add-argomento-cdl"]);
    $ok ? $messaggio = "Argomento aggiunto con successo." : $errore = "Errore aggiunta argomento.";
}

if (isset($_POST["remove-argomento"], $_POST["idesame"], $_POST["idargomento"])) {
    $removeArgomento = $db->removeArgomento($_GET["idcdl"], $_POST["idesame"], $_POST["idargomento"]);
    $removeArgomento ? $messaggio = "Argomento rimosso con successo." : $errore = "Errore rimozione argomento.";
}


$cdl = $studio = $adatto = $sbocchi = $materie = $argomenti = $esami = [];

$nextIdStudia = 1;
$nextIdAdatto = 1;
$nextIdSbocchi = 1;

if (isset($_GET["idcdl"])) {
    $idcdl = $_GET["idcdl"];

    $cdl = $db->getCdlById($idcdl);
    $studio = $db->getStudi($idcdl);
    $adatto = $db->getAdatto($idcdl);
    $sbocchi = $db->getSbocchi($idcdl);
    $materie = $db->getMaterie($idcdl);
    $argomenti = $db->getArgomenti($idcdl);
    $esami = $db->getEsamiByIdCdl($idcdl);

    $lastS = $db->getLastIdStudia($idcdl);
    $nextIdStudia = empty($lastS) ? 1 : ((int)$lastS[0]["lastidstudia"] + 1);

    $lastA = $db->getLastIdAdatto($idcdl);
    $nextIdAdatto = empty($lastA) ? 1 : ((int)$lastA[0]["lastidadatto"] + 1);

    $lastB = $db->getLastIdSbocchi($idcdl);
    $nextIdSbocchi = empty($lastB) ? 1 : ((int)$lastB[0]["lastidsbocchi"] + 1);
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <link rel="stylesheet" href="style.css">
  <title>StudyBo - Gestione Informazioni Corsi di Laurea</title>

  <script>
  (function () {
    try {
      const raw = localStorage.getItem("sb_prefs");
      const p = raw ? JSON.parse(raw) : {};
      const root = document.documentElement;

      root.classList.remove("theme-dark","theme-contrast","theme-cvd","no-images","reduce-motion");

      if (p.theme === "dark") root.classList.add("theme-dark");
      if (p.theme === "contrast") root.classList.add("theme-contrast");
      if (p.theme === "cvd") root.classList.add("theme-cvd");

      const fs = Number(p.fontScale || 1);
      root.style.setProperty("--font-scale", String(fs));

      if (p.noImages) root.classList.add("no-images");
      if (p.reduceMotion) root.classList.add("reduce-motion");
    } catch (e) {}
  })();
  </script>
</head>

<body onload="checkNotifica()">
  <a class="skip-link" href="#contenuto">Salta al contenuto</a>

  <?php include 'header.php'; ?>
  <?php include 'admin_nav.php'; ?>

  <main class="pagina" id="contenuto" role="main">

    <section class="hero" aria-labelledby="titolo-pagina">
      <h1 id="titolo-pagina" class="admin-title">
        Gestione Informazioni Corso di <?= e($cdl[0]["nomecdl"] ?? "") ?>
      </h1>
      <p class="hero-meta admin-meta">
        Area Amministratore • Dipartimento di Ingegneria e Architettura • Campus di Cesena
      </p>
      <p class="hero-desc admin-desc">
        Gestisci le informazioni descrittive del Corso di Laurea
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

    <!-- DESCRIZIONE -->
    <section class="hero" aria-labelledby="gestione-descrizione">
      <div class="form-info admin-panel">
        <h2 id="gestione-descrizione">Modifica Descrizione Corso di Laurea</h2>

        <form action="" method="POST">
          <label for="descrizione-cdl">Descrizione Cdl:</label>
          <input
            type="text"
            id="descrizione-cdl"
            name="descrizione-cdl"
            value="<?= e($cdl[0]["descrizionecdl"] ?? "") ?>"
          />
          <div class="form-actions" role="group" aria-label="Azioni descrizione">
            <input class="btn-secondary btn-small" type="submit" name="submit-desc-cdl" value="Modifica"/>
          </div>
        </form>
      </div>
    </section>

    <!-- COSA SI STUDIA -->
    <section class="hero" aria-labelledby="gestione-cosasistudia">
      <div class="form-info admin-panel">
        <h2 id="gestione-cosasistudia">Modifica Cosa si studia</h2>

        <ul>
          <?php foreach($studio as $studioItem): ?>
            <li>
              <form action="" method="POST">
                <label for="studio-cdl<?= (int)$studioItem["idstudia"] ?>">Materia di studio:</label>
                <input
                  type="text"
                  id="studio-cdl<?= (int)$studioItem["idstudia"] ?>"
                  name="studio-cdl"
                  value="<?= e($studioItem["descrizionestudia"] ?? "") ?>"
                />
                <input type="hidden" name="idstudio" value="<?= (int)$studioItem["idstudia"] ?>"/>

                <div class="form-actions" role="group" aria-label="Azioni materia di studio">
                  <input class="btn-secondary btn-small" type="submit" name="submit-studia" value="Modifica"/>
                  <input class="btn-delete" type="submit" name="remove-studia" value="Rimuovi"/>
                </div>
              </form>
            </li>
          <?php endforeach; ?>

          <li>
            <form action="" method="POST">
              <label for="add-studio-cdl">Materia di studio:</label>
              <input type="text" id="add-studio-cdl" name="add-studio-cdl"/>
              <input type="hidden" name="idstudio" value="<?= (int)$nextIdStudia ?>"/>

              <div class="form-actions" role="group" aria-label="Azioni aggiungi materia di studio">
                <input class="btn-secondary btn-small" type="submit" name="add-submit-studia" value="Aggiungi"/>
              </div>
            </form>
          </li>
        </ul>
      </div>
    </section>

    <!-- A CHI È ADATTO -->
    <section class="hero" aria-labelledby="gestione-achiadatto">
      <div class="form-info admin-panel">
        <h2 id="gestione-achiadatto">Modifica a chi è adatto</h2>

        <ul>
          <?php foreach($adatto as $adattoItem): ?>
            <li>
              <form action="" method="POST">
                <label for="adatto-cdl<?= (int)$adattoItem["idadatto"] ?>">A chi è adatto:</label>
                <input
                  type="text"
                  id="adatto-cdl<?= (int)$adattoItem["idadatto"] ?>"
                  name="adatto-cdl"
                  value="<?= e($adattoItem["descrizioneadatto"] ?? "") ?>"
                />
                <input type="hidden" name="idadatto" value="<?= (int)$adattoItem["idadatto"] ?>"/>

                <div class="form-actions" role="group" aria-label="Azioni a chi è adatto">
                  <input class="btn-secondary btn-small" type="submit" name="submit-adatto" value="Modifica"/>
                  <input class="btn-delete" type="submit" name="remove-adatto" value="Rimuovi"/>
                </div>
              </form>
            </li>
          <?php endforeach; ?>

          <li>
            <form action="" method="POST">
              <label for="add-adatto-cdl">A chi è adatto:</label>
              <input type="text" id="add-adatto-cdl" name="add-adatto-cdl"/>
              <input type="hidden" name="idadatto" value="<?= (int)$nextIdAdatto ?>"/>

              <div class="form-actions" role="group" aria-label="Azioni aggiungi a chi è adatto">
                <input class="btn-secondary btn-small" type="submit" name="add-submit-adatto" value="Aggiungi"/>
              </div>
            </form>
          </li>
        </ul>
      </div>
    </section>

    <!-- SBOCCHI -->
    <section class="hero" aria-labelledby="gestione-sbocchi">
      <div class="form-info admin-panel">
        <h2 id="gestione-sbocchi">Modifica sbocchi tipici</h2>

        <ul>
          <?php foreach($sbocchi as $sbocco): ?>
            <li>
              <form action="" method="POST">
                <label for="sbocchi-cdl<?= (int)$sbocco["idsbocchi"] ?>">Sbocco:</label>
                <input
                  type="text"
                  id="sbocchi-cdl<?= (int)$sbocco["idsbocchi"] ?>"
                  name="sbocchi-cdl"
                  value="<?= e($sbocco["descrizionesbocchi"] ?? "") ?>"
                />
                <input type="hidden" name="idsbocchi" value="<?= (int)$sbocco["idsbocchi"] ?>"/>

                <div class="form-actions" role="group" aria-label="Azioni sbocco">
                  <input class="btn-secondary btn-small" type="submit" name="submit-sbocchi" value="Modifica"/>
                  <input class="btn-delete" type="submit" name="remove-sbocchi" value="Rimuovi"/>
                </div>
              </form>
            </li>
          <?php endforeach; ?>

          <li>
            <form action="" method="POST">
              <label for="add-sbocchi-cdl">Sbocco:</label>
              <input type="text" id="add-sbocchi-cdl" name="add-sbocchi-cdl"/>
              <input type="hidden" name="idsbocchi" value="<?= (int)$nextIdSbocchi ?>"/>

              <div class="form-actions" role="group" aria-label="Azioni aggiungi sbocco">
                <input class="btn-secondary btn-small" type="submit" name="add-submit-sbocchi" value="Aggiungi"/>
              </div>
            </form>
          </li>
        </ul>
      </div>
    </section>

    <!-- materie principali e argomenti -->
<section class="hero" aria-labelledby="gestione-materie">
  <div class="form-info admin-panel">
    <h2 id="gestione-materie">Materie principali</h2>

    <ul>
      <?php foreach($materie as $materia): ?>
        <?php
          $matId = (int)$materia["idesame"];
          $matKey = (int)$materia["idcdl"] . '-' . $matId;
          $titleId = "materia-title-" . $matKey;
        ?>
        <li>
          <div class="materia-header">
            <h3 class="materia-title" id="<?= e($titleId) ?>">
              <?= e($materia["nomeesame"] ?? "") ?>
            </h3>

            <form action="" method="POST" class="materia-remove inline-form">
              <input type="hidden" name="idmateria" value="<?= $matId ?>"/>
              <div class="form-actions actions-inline" role="group" aria-label="Azioni materia principale">
                <input class="btn-delete" type="submit" name="remove-materia" value="Rimuovi"/>
              </div>
            </form>
          </div>

          <ul class="sub-list" aria-labelledby="<?= e($titleId) ?>">
            <?php foreach($argomenti as $argomento): ?>
              <?php if($argomento["idcdl"] == $materia["idcdl"] && $argomento["idesame"] == $materia["idesame"]): ?>
                <?php
                  $argId = (int)$argomento["idargomento"];
                  $argInputId = "argomento-" . (int)$argomento["idcdl"] . "-" . (int)$argomento["idesame"] . "-" . $argId;
                ?>
                <li>
                  <form action="" method="POST">
                    <label for="<?= e($argInputId) ?>">Argomento:</label>
                    <input
                      type="text"
                      id="<?= e($argInputId) ?>"
                      name="argomento-cdl"
                      value="<?= e($argomento["descrizioneargomento"] ?? "") ?>"
                    />
                    <input type="hidden" name="idesame" value="<?= (int)$argomento["idesame"] ?>"/>
                    <input type="hidden" name="idargomento" value="<?= $argId ?>"/>

                    <div class="form-actions actions-inline" role="group" aria-label="Azioni argomento">
                      <input class="btn-secondary btn-small" type="submit" name="submit-argomento" value="Modifica"/>
                      <input class="btn-delete" type="submit" name="remove-argomento" value="Rimuovi"/>
                    </div>
                  </form>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>

            <li>
              <?php $addArgId = "add-argomento-" . $matKey; ?>
              <form action="" method="POST">
                <label for="<?= e($addArgId) ?>">Nuovo argomento:</label>
                <input type="text" id="<?= e($addArgId) ?>" name="add-argomento-cdl"/>
                <input type="hidden" name="idesame" value="<?= $matId ?>"/>

                <div class="form-actions actions-inline" role="group" aria-label="Azioni aggiungi argomento">
                  <input class="btn-secondary btn-small" type="submit" name="add-submit-argomento" value="Aggiungi"/>
                </div>
              </form>
            </li>
          </ul>
        </li>
      <?php endforeach; ?>

      <li>
        <form action="" method="POST">
          <label for="add-materia-cdl">Aggiungi materia alle principali:</label>
          <select id="add-materia-cdl" name="add-materia-cdl">
            <?php foreach ($esami as $esame): ?>
              <option value="<?= (int)$esame["idesame"] ?>"><?= e($esame["nomeesame"] ?? "") ?></option>
            <?php endforeach; ?>
          </select>

          <div class="form-actions actions-inline" role="group" aria-label="Azioni aggiungi materia principale">
            <input class="btn-secondary btn-small" type="submit" name="add-submit-materia" value="Aggiungi"/>
          </div>
        </form>
      </li>
    </ul>
  </div>
</section>


  </main>

  <?php include 'footer.php'; ?>
  <script src="js/script.js"></script>
</body>
</html>
