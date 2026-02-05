<?php
declare(strict_types=1);

require_once 'db/DatabaseHelper.php';
require_once 'db/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('e')) {
  function e(string $s): string { 
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); 
  }
}

$db = new DatabaseHelper(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

$idcdl = $_GET["idcdl"] ?? '';
$corso = $db->getCdlById($idcdl) ?? null;
$studia = $db->getStudi($idcdl);
$adatto = $db->getAdatto($idcdl);
$sbocchi = $db->getSbocchi($idcdl);
$materie = $db->getMaterie($idcdl);
$argomenti = $db->getArgomenti($idcdl);

$siteTitle = !empty($corso) ? "StudyBo - " . e($corso[0]["nomecdl"]) : 'StudyBo - Corso non trovato';
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="style.css"/>
  <title><?= $siteTitle ?></title>
</head>

<body onload="checkNotifica()">
  <a class="skip-link" href="#contenuto">Salta al contenuto</a>

  <?php include 'header.php'; ?>

  <main class="pagina" id="contenuto">
    <?php if (empty($corso)): ?>
      <section class="hero">
        <h1>Corso non trovato</h1>
        <p>Torna alla lista e riprova.</p>
        <a class="btn-primary" href="corsi.php">Torna ai corsi</a>
      </section>
    <?php else: ?>
      <section class="hero">
        <div class="hero-grid">
          <div class="hero-text">
            <h1><?php echo e($corso[0]["nomecdl"]); ?></h1>
            <p class="hero-subtitle">Campus: <?php echo e($corso[0]["sede"]); ?></p>
            <p class="hero-meta">Dipartimento di Ingegneria<br>e Architettura</p>
            <p class="hero-desc"><?php echo e($corso[0]["descrizionecdl"]); ?></p>
            
            <div class="corso-hero-actions">
              <a class="btn-primary" href="risorse.php">Vedi Risorse</a>
              <a class="btn-primary" href="studygroup-list.php?idcdl=<?php echo $idcdl; ?>">Study Group</a>
            </div>
          </div>
          <figure class="hero-media">
            <img src="img/cdl/<?php echo e($corso[0]["img"]); ?>" alt=""/>
          </figure>
        </div>
      </section>

      <section class="sezione">
        <h2>Panoramica</h2>
        <div class="griglia-corsi">
            <article class="card-corso">
              <h3>Cosa si studia</h3>
              <ul class="corso-lista">
                <?php foreach ($studia as $studio): ?>
                  <li><?php echo e($studio["descrizionestudia"]); ?></li>
                <?php endforeach; ?>
              </ul>
            </article>
            <article class="card-corso">
              <h3>A chi è adatto</h3>
              <ul class="corso-lista">
                <?php foreach ($adatto as $a): ?>
                  <li><?php echo e($a["descrizioneadatto"]); ?></li>
                <?php endforeach; ?>
              </ul>
            </article>
            <article class="card-corso">
              <h3>Sbocchi tipici</h3>
              <ul class="corso-lista">
                <?php foreach ($sbocchi as $sbocco): ?>
                  <li><?php echo e($sbocco["descrizionesbocchi"]); ?></li>
                <?php endforeach; ?>
              </ul>
            </article>
        </div>
      </section>

      <section class="sezione">
        <h2>Materie principali</h2>
        <div class="griglia-corsi">
          <?php foreach ($materie as $m): ?>
            <article class="card-corso">
              <h3><?php echo e($m["nomeesame"]); ?></h3>
              <ul class="corso-lista">
                <?php foreach ($argomenti as $a): ?>
                  <?php if($a["idcdl"] == $m["idcdl"] && $a["idesame"] == $m["idesame"]): ?>
                    <li><?php echo e($a["descrizioneargomento"]); ?></li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>
  </main>

 <?php include 'footer.php'; ?>


<script src="js/script.js"></script>
</body>
</html>
