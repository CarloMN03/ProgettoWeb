<?php
declare(strict_types=1);
require_once 'bootstrap.php';
$siteTitle = 'StudyBo - Impara meglio, insieme';

$scadenze = $dbh->getStGrScad();
$isLogged = !empty($_SESSION['username']);
$corsi = $dbh->getAllCdl();
$esamisg = $dbh->getNumberEsamiSgByCdl();

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
  <title><?php echo e($siteTitle) ?></title>

  <link rel="stylesheet" href="style.css"/>
<body onload="checkNotifica()">

<a class="skip-link" href="#contenuto">Salta al contenuto</a>

<?php include 'header.php'; ?>

<main id="contenuto" class="pagina" role="main">

  <section class="hero" aria-labelledby="hero-title">
    <div class="hero-grid">
      <div class="hero-text">
        <h1 id="hero-title">StudyBo</h1>
        <p class="hero-subtitle">Impara meglio, insieme.</p>
        <p class="hero-desc">
          Trova, crea e unisciti ai gruppi di studio del tuo corso di laurea.
        </p>

        <ul class="quick-links">
          <li><a href="studygroup-list.php" class="link-strong">Trova uno Study Group</a></li>
          <li><a href="elenco-cdl.php" class="link-strong">Elenco corsi di laurea</a></li>
          <li><a href="risorse.php" class="link-strong">Vai alle risorse</a></li>
        </ul>
      </div>

      <figure class="hero-media">
        <img src="img/scrivania.png" alt="" loading="lazy"/>
      </figure>
    </div>
  </section>

  <section class="sezione" aria-labelledby="corsi-title">
    <h2 id="corsi-title">Corsi di laurea</h2>

    <div class="griglia-corsi" role="list">
      <?php foreach ($corsi as $c): ?>
        <article class="card-corso" role="listitem">
          <img class="card-cdl-img" src="img/cdl/<?php echo e($c["img"]) ?>" alt="" loading="lazy" decoding="async"/>
          <h3 class="card-title"><a href="corso.php?idcdl=<?php echo $c["idcdl"]; ?>"><?php echo e($c["nomecdl"]); ?></a></h3>
          <p class="card-meta">Campus: <?php echo e($c["sede"]) ?></p>
          <p class="card-meta">Esami con Study Group: 
            <?php $n =0; foreach($esamisg as $e): ?>
            <?php if($e["idcdl"] == $c["idcdl"]): ?>
              <?php $n = $e["numesami"]; ?>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php echo $n; ?>
          </p>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="sezione" aria-labelledby="sg-title">
    <h2 id="sg-title">Study Group in scadenza oggi</h2>

    <?php if (empty($scadenze)): ?>
      <p>Nessuno Study Group in scadenza oggi.</p>
    <?php else: ?>
      <div class="griglia-corsi" role="list">
        <?php foreach ($scadenze as $s): ?>
          <article class="card-corso" role="listitem">
            <img src="img/esami/<?php echo e($s['imgesame'] ? $s['imgesame'] : 'logo.libro.PNG'); ?>" alt="Icona esame <?php echo e($s['nomeesame']); ?>"/>

            <h3 class="card-title"><?php echo e($s['nomeesame']); ?></h3>

            <dl class="card-dl">
              <div><dt>Tema:</dt><dd><?php echo e($s['tema']); ?></dd></div>
              <div><dt>Luogo:</dt><dd><?php echo e($s['luogo']); ?></dd></div>
              <div><dt>Ora:</dt><dd><strong><?php echo e($s['ora']); ?></strong></dd></div>
            </dl>

            <div class="card-actions" style="margin-top:10px;">
                <?php if ($isLogged): ?>
                  <a class="btn-primary btn-small" href="studygroup.php?idcdl=<?php echo (int)$s['idcdl']; ?>&idesame=<?php echo (int)$s['idesame']; ?>&idstudygroup=<?php echo (int)$s['idstudygroup']; ?>">
                    Visualizza →
                  </a>
                <?php else: ?>
                  <a class="btn-secondary btn-small" href="login.php">Accedi per visualizzare</a>
                <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

</main>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>