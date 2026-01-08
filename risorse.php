<?php
declare(strict_types=1);

function e(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$siteTitle = 'StudyBo - Risorse';

$risorse = [
  [
    'titolo' => 'Ing. e Scienze Informatiche (Cesena)',
    'file' => [
      ['href' => 'download/analisi1_appunti.pdf', 'label' => 'Analisi 1 - Appunti (PDF)'],
      ['href' => 'download/analisi1_esercizi.zip', 'label' => 'Analisi 1 - Esercizi svolti (ZIP)'],
      ['href' => 'download/algebra_lineare_formulario.pdf', 'label' => 'Algebra Lineare - Formulario (PDF)'],
      ['href' => 'download/programmazioneC_esercizi.zip', 'label' => 'Programmazione in C - Esercizi (ZIP)'],
      ['href' => 'download/po_slide.pdf', 'label' => 'Programmazione ad Oggetti - Slide (PDF)'],
      ['href' => 'download/basi_dati_riassunto.pdf', 'label' => 'Basi di Dati - Riassunto (PDF)'],
      ['href' => 'download/reti_slide.pdf', 'label' => 'Reti - Slide (PDF)'],
      ['href' => 'download/sistemi_operativi_appunti.pdf', 'label' => 'Sistemi Operativi - Appunti (PDF)'],
    ],
  ],
  [
    'titolo' => 'Ingegneria Biomedica',
    'file' => [
      ['href' => 'download/fisica_formulario.pdf', 'label' => 'Fisica - Formulario (PDF)'],
      ['href' => 'download/fisica_esercizi_svolti.pdf', 'label' => 'Fisica - Esercizi svolti (PDF)'],
      ['href' => 'download/chimica_riassunto.pdf', 'label' => 'Chimica - Riassunto (PDF)'],
      ['href' => 'download/chimica_esercizi.zip', 'label' => 'Chimica - Esercizi (ZIP)'],
      ['href' => 'download/anatomia_schemi.pdf', 'label' => 'Anatomia - Schemi (PDF)'],
      ['href' => 'download/fisiologia_appunti.pdf', 'label' => 'Fisiologia - Appunti (PDF)'],
      ['href' => 'download/biomateriali_slide.pdf', 'label' => 'Biomateriali - Slide (PDF)'],
      ['href' => 'download/segnali_biomedici_dispensa.pdf', 'label' => 'Segnali Biomedici - Dispensa (PDF)'],
    ],
  ],
  [
    'titolo' => 'Ingegneria Elettronica',
    'file' => [
      ['href' => 'download/circuiti1_appunti.pdf', 'label' => 'Circuiti 1 - Appunti (PDF)'],
      ['href' => 'download/circuiti1_esercizi.zip', 'label' => 'Circuiti 1 - Esercizi (ZIP)'],
      ['href' => 'download/elettronica_analogica_dispensa.pdf', 'label' => 'Elettronica Analogica - Dispensa (PDF)'],
      ['href' => 'download/elettronica_digitale_esercizi.zip', 'label' => 'Elettronica Digitale - Esercizi (ZIP)'],
      ['href' => 'download/segnali_sistemi_formulario.pdf', 'label' => 'Segnali e Sistemi - Formulario (PDF)'],
      ['href' => 'download/segnali_sistemi_esercizi.pdf', 'label' => 'Segnali e Sistemi - Esercizi (PDF)'],
      ['href' => 'download/misure_elettroniche_appunti.pdf', 'label' => 'Misure Elettroniche - Appunti (PDF)'],
      ['href' => 'download/architettura_calcolatori_slide.pdf', 'label' => 'Architettura degli Elaboratori - Slide (PDF)'],
    ],
  ],
  [
    'titolo' => 'Architettura',
    'file' => [
      ['href' => 'download/storia_architettura_dispensa.pdf', 'label' => 'Storia dell’Architettura - Dispensa (PDF)'],
      ['href' => 'download/storia_architettura_slide.pdf', 'label' => 'Storia dell’Architettura - Slide (PDF)'],
      ['href' => 'download/disegno_tecnico_esempi.pdf', 'label' => 'Disegno Tecnico - Esempi (PDF)'],
      ['href' => 'download/disegno_tecnico_esercizi.zip', 'label' => 'Disegno Tecnico - Esercizi (ZIP)'],
      ['href' => 'download/progettazione1_esercizi.pdf', 'label' => 'Progettazione 1 - Esercizi (PDF)'],
      ['href' => 'download/progettazione1_template.pdf', 'label' => 'Progettazione 1 - Template (PDF)'],
      ['href' => 'download/urbanistica_riassunto.pdf', 'label' => 'Urbanistica - Riassunto (PDF)'],
      ['href' => 'download/tecnologia_materiali_appunti.pdf', 'label' => 'Tecnologia dei Materiali - Appunti (PDF)'],
    ],
  ],
];
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
    <section class="sezione">
      <h2 class="titolo-sezione">Risorse scaricabili</h2>
      <p class="testo-semplice">
        Qui trovi alcune risorse utili divise per corso di laurea.
      </p>

      <div class="griglia-risorse">
        <?php foreach ($risorse as $r): ?>
          <article class="box-risorse">
            <h3 class="titolo-piccolo"><?= e($r['titolo']) ?></h3>
            <ul class="lista-semplice">
              <?php foreach ($r['file'] as $f): ?>
                <li>
                  <a class="link-file" href="<?= e($f['href']) ?>" download><?= e($f['label']) ?></a>
                </li>
              <?php endforeach; ?>
            </ul>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <footer class="pie-pagina">
    <p>Impara meglio, insieme.</p>
  </footer>
</body>
</html>
