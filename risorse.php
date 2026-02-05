<?php
declare(strict_types=1);

require_once 'db/DatabaseHelper.php';
require_once 'db/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$db = new DatabaseHelper(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

$cdlRisorse = $db->getCdlRisorse();
$risorse = $db->getAllRisorse();
$countR = $db->getNumRisorse();

$siteTitle = 'StudyBo - Risorse';

if (!function_exists('e')) {
  function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
  }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="style.css">
  <title><?= e($siteTitle) ?></title>
</head>

<body onload="checkNotifiche()">
  <a class="skip-link" href="#contenuto">Salta al contenuto</a>

  <?php include 'header.php'; ?>

  <main class="pagina" id="contenuto" role="main">
    <section class="hero" aria-labelledby="ris-title">
      <div class="hero-grid">
        <div class="hero-text">
          <h1 id="ris-title">Risorse scaricabili</h1>
          <p class="hero-subtitle">Materiale utile diviso per corso di laurea.</p>
          <p class="hero-desc">
            Puoi scaricare PDF e ZIP (appunti, esercizi, formulari). Trova il materiale relativo al tuo indirizzo nei box sottostanti.
          </p>

          <ul class="quick-links" aria-label="Azioni rapide">
            <li><a class="link-strong" href="corsi.php">Vai ai corsi</a></li>
            <li><a class="link-strong" href="studygroup-list.php">Trova Study Group</a></li>
            <li><a class="link-strong" href="contatti.php">Contatti</a></li>
          </ul>
        </div>

        <figure class="hero-media">
          <img src="img/scrivania.png" alt="" loading="lazy" decoding="async">
        </figure>
      </div>
    </section>

    <section class="sezione" aria-labelledby="elenco-risorse-title">
      <div class="section-head">
        <h2 id="elenco-risorse-title">Elenco risorse</h2>
        <p class="section-hint">Tutti i link ai file sono sottolineati per essere facilmente individuabili.</p>
      </div>

      <div class="griglia-corsi" role="list">
        <?php foreach ($cdlRisorse as $c): ?>
          <article class="card-corso" role="listitem">
            <h3 class="card-title">
              <a class="card-link" href="corso.php?idcdl=<?php echo $c["idcdl"]; ?>">
                <?php echo e($c["nomecdl"]); ?>
              </a>
            </h3>

            <p class="card-meta">Download disponibili: 
              <?php foreach ($countR as $cr): ?>
                <?php if($cr["idcdl"] == $c["idcdl"]): ?>
                  <?php echo $cr["numr"]; ?>
                <?php endif; ?>
              <?php endforeach; ?>
            </p>

            <ul class="resource-list" aria-label="File disponibili per <?php echo e($c["nomecdl"]); ?>">
              <?php foreach ($risorse as $r): ?>
                <?php if($r["idcdl"] == $c["idcdl"]): ?>
                  <li class="resource-item">
                    <a href="upload/<?php echo e($r["filerisorsa"]) ?>" download class="resource-download">
                      📄 <?php echo e($r["nomeesame"]); ?> - <?php echo e($r["nomeris"]); ?>
                    </a>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="footer-actions">
        <a class="btn-secondary" href="home.php">Torna alla Home</a>
      </div>
    </section>
  </main>

  <?php include 'footer.php'; ?>

  <script src="js/script.js"></script>
</body>
</html>