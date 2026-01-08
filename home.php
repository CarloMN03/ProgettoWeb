<?php
$siteTitle = 'StudyBo - Impara meglio, insieme';

$corsi = [
  [
    'titolo' => 'Laurea Ingegneria e Scienze Informatiche',
    'campus' => 'Cesena',
    'esami'  => 14,
    'img'    => 'lock.png',
    'alt'    => 'computer',
    'link'   => 'corso.php?slug=ing-inf',
  ],
  [
    'titolo' => 'Laurea Ingegneria Biomedica',
    'campus' => 'Cesena',
    'esami'  => 12,
    'img'    => 'laboratorio.png',
    'alt'    => 'laboratorio e strumenti',
    'link'   => 'corso.php?slug=ing-biomedica',
  ],
  [
    'titolo' => 'Laurea Architettura',
    'campus' => 'Cesena',
    'esami'  => 20,
    'img'    => 'muri.png',
    'alt'    => 'disegno tecnico e progetto',
    'link'   => 'corso.php?slug=architettura',
  ],
  [
    'titolo' => 'Laurea Magistrale Ingegneria e Scienze Informatiche',
    'campus' => 'Cesena',
    'esami'  => 10,
    'img'    => 'cloud.jpg',
    'alt'    => 'computer e progetto software',
    'link'   => 'corso.php?slug=lm-inf',
  ],
  [
    'titolo' => 'Laurea Magistrale Ingegneria Biomedica',
    'campus' => 'Cesena',
    'esami'  => 10,
    'img'    => 'bio.png',
    'alt'    => 'strumentazione medica e tecnologia',
    'link'   => 'corso.php?slug=lm-biomedica',
  ],
  [
    'titolo' => 'Laurea Ingegneria Elettronica',
    'campus' => 'Cesena',
    'esami'  => 15,
    'img'    => 'chip.jpg',
    'alt'    => 'circuito elettronico e componenti',
    'link'   => 'corso.php?slug=ing-elettronica',
  ],
];

$scadenze = [
  [
    'img'   => 'po.png',
    'alt'   => 'righe di codice',
    'titolo'=> "Programmazione<br>ad oggetti",
    'tema'  => 'Ereditarietà',
    'luogo' => 'online',
    'data'  => '01/12/25',
    'ora'   => '20:00',
    'lang'  => 'IT',
    'like'  => 20,
    'link'  => '#',
  ],
  [
    'img'   => 'chimica.png',
    'alt'   => 'laboratorio',
    'titolo'=> 'Chimica',
    'tema'  => 'Nomenclatura',
    'luogo' => 'online',
    'data'  => '01/12/25',
    'ora'   => '20:10',
    'lang'  => 'IT',
    'like'  => 10,
    'link'  => '#',
  ],
  [
    'img'   => 'database.png',
    'alt'   => 'biblioteca',
    'titolo'=> 'Base di dati',
    'tema'  => 'Indici',
    'luogo' => 'online',
    'data'  => '01/12/25',
    'ora'   => '20:15',
    'lang'  => 'IT',
    'like'  => 8,
    'link'  => '#',
  ],
];

function e(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
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

<body>
  <header class="barra-rossa">
    <img src="logo.libro.PNG" alt="logo StudyBo" class="logo" width="40" height="40"/>
    <h1 class="nome-sito">StudyBO</h1>

    <button class="bottone-lingua" type="button" aria-label="Cambia lingua">
      IT <span class="freccina" aria-hidden="true">▼</span>
    </button>

    <button class="menu-hamburger" type="button" aria-label="Apri menu"></button>
  </header>

  <main class="pagina">
    <section class="intro">
      <header class="testo-intro">
        <h2>StudyBo</h2>
        <p class="sottotitolo">Impara, meglio insieme</p>
        <h3>Dipartimento di Ingegneria<br>e Architettura</h3>
      </header>

      <figure class="foto-intro">
        <img src="scrivania.png" alt="scrivania con computer">
      </figure>

      <p class="descrizione-intro">
        La piattaforma unibo per studenti di Ingegneria ed Architettura che permette di creare,
        trovare e unirsi in gruppi di studio.<br>
        Condividi conoscenze, migliora la tua preparazione e unisciti ad una comunità universitaria.
      </p>
    </section>

    <div class="blocchi-desktop">
      <section class="sezione corsi">
        <h2 class="titolo-sezione">Corsi di Laurea con Study Group attivi</h2>

        <div class="griglia-corsi">
          <?php foreach ($corsi as $c): ?>
            <article class="card-corso">
              <img src="<?= e($c['img']) ?>" alt="<?= e($c['alt']) ?>" class="img-corso">
              <div class="testo-corso">
                <h4><?= e($c['titolo']) ?></h4>
                <p>Campus: <?= e($c['campus']) ?></p>
                <p><strong>Esami con Study Group: <?= (int)$c['esami'] ?></strong></p>
                <a href="<?= e($c['link']) ?>" class="link-corso">Esplora il corso →</a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <button class="bottone-altro" type="button" aria-label="Mostra altro">⌄</button>
      </section>

      <section class="sezione scadenze">
        <h2 class="titolo-sezione">Study Group in scadenza</h2>

        <div class="box-grigio">
          <div class="scadenze-wrapper">
            <button class="freccia freccia-sinistra" type="button" aria-label="Sinistra">‹</button>

            <div class="lista-scadenze" aria-label="Elenco Study Group">
              <?php foreach ($scadenze as $s): ?>
                <article class="card-scadenza">
                  <img src="<?= e($s['img']) ?>" alt="<?= e($s['alt']) ?>" class="img-scadenza">
                  <h4 class="titolo-scadenza"><?= $s['titolo'] ?></h4>

                  <p>Tema: <?= e($s['tema']) ?></p>
                  <p>Luogo: <?= e($s['luogo']) ?></p>
                  <p>Data: <?= e($s['data']) ?></p>
                  <p>Ora: <?= e($s['ora']) ?></p>

                  <div class="riga-sotto">
                    <span class="etichetta"><?= e($s['lang']) ?></span>
                    <button class="cuore" type="button" aria-label="Mi piace">♡ <?= (int)$s['like'] ?></button>
                  </div>

                  <a href="<?= e($s['link']) ?>" class="link-partecipa">Partecipa →</a>
                </article>
              <?php endforeach; ?>
            </div>

            <button class="freccia freccia-destra" type="button" aria-label="Destra">›</button>
          </div>
        </div>
      </section>
    </div>
  </main>

  <footer class="pie-pagina">
    <p>Impara meglio, insieme.</p>
  </footer>
</body>
</html>
