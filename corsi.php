<?php
declare(strict_types=1);

require_once 'db/DatabaseHelper.php';
require_once 'db/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Funzione per pulire i testi e renderli sicuri
if (!function_exists('e')) {
  function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
  }
}

$db = new DatabaseHelper(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Recupero dati reali dal Database
$corsi = $db->getAllCdl();

$siteTitle = 'StudyBo - Elenco Corsi';
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="style.css">
  <title><?= e($siteTitle) ?></title>
</head>

<body>

<a class="skip-link" href="#contenuto">Salta al contenuto</a>

<?php include 'header.php'; ?>

<main class="pagina" id="contenuto" role="main">

  <section class="hero" aria-labelledby="titolo">
    <div class="hero-grid">
      <div class="hero-text">
        <h1 id="titolo">Tutti i Corsi</h1>
        <p class="hero-subtitle">Esplora l'offerta formativa del Campus di Cesena.</p>
        <p class="hero-meta">
          Scegli il tuo indirizzo per trovare appunti, esami e gruppi di studio.
        </p>
      </div>
    </div>
  </section>

  <section class="sezione" aria-label="Elenco corsi">
    <div class="section-head">
      <h2>Elenco corsi disponibili</h2>
      <p class="section-hint">
        Clicca su "Apri Corso" per vedere i dettagli e i contenuti utili.
      </p>
    </div>

    <div class="griglia-corsi">
      <?php foreach ($corsi as $c): ?>
        <article class="card-corso">
          <div class="card-icon" aria-hidden="true">
            <img src="img/cdl/<?php echo e($c["img"]) ?>" alt="" loading="lazy" decoding="async"/>
          </div>

          <h3 class="card-title"><?php echo e($c["nomecdl"]) ?></h3>
          <p class="card-meta">Campus: <?php echo e($c["sede"]) ?></p>

          <div class="card-open-wrap">
            <a class="btn-primary btn-open-corso" href="corso.php?idcdl=<?php echo $c["idcdl"] ?>">
              Apri Corso →
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

</main>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>
