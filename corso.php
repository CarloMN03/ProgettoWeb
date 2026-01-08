<?php
declare(strict_types=1);

function e(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$corsi = [
  'architettura' => [
    'siteTitle' => 'StudyBo - Architettura',
    'h2' => 'Architettura',
    'campus' => 'Campus: Cesena',
    'dipartimento' => "Dipartimento di Ingegneria<br>e Architettura",
    'img' => ['src' => 'muri.png', 'alt' => 'muri movimento brutale'],
    'descrizione' => "Il corso di laurea in Architettura unisce progettazione, cultura del progetto e competenze tecniche.
        Si lavora su disegno, storia dell’architettura, urbanistica e laboratori progettuali, con un percorso
        che combina creatività e metodo. È adatto a chi vuole progettare spazi, edifici e soluzioni che migliorano
        la vita quotidiana delle persone.",
    'panoramica' => [
      [
        'titolo' => 'Cosa si studia',
        'voci' => [
          'Progettazione architettonica (laboratori)',
          'Disegno tecnico e rappresentazione',
          'Storia dell’architettura',
          'Urbanistica e pianificazione',
          'Tecnologia dei materiali e del costruire',
        ],
      ],
      [
        'titolo' => 'A chi è adatto',
        'voci' => [
          'A chi ama progettare e ragionare sugli spazi',
          'A chi vuole un percorso creativo ma strutturato',
          'A chi è interessato a città, edifici e territorio',
          'A chi vuole imparare metodo e comunicazione del progetto',
        ],
      ],
      [
        'titolo' => 'Sbocchi tipici',
        'voci' => [
          'Studi di progettazione e collaborazione professionale',
          'Disegno e modellazione (CAD/3D)',
          'Supporto tecnico in ambito edilizio',
          'Urbanistica e analisi del territorio',
          'Progettazione e gestione di interventi (in team)',
        ],
      ],
    ],
    'materie' => [
      ['titolo' => 'Progettazione 1', 'voci' => ['Concept e idea progettuale', 'Spazi e funzioni', 'Revisioni e consegne']],
      ['titolo' => 'Disegno Tecnico', 'voci' => ['Norme grafiche', 'Proiezioni e sezioni', 'Esempi ed esercizi']],
      ['titolo' => 'Storia dell’Architettura', 'voci' => ['Periodi e stili', 'Opere e autori', 'Schemi e riassunti']],
      ['titolo' => 'Urbanistica', 'voci' => ['Città e territorio', 'Piani e strumenti', 'Analisi e casi studio']],
      ['titolo' => 'Tecnologia dei Materiali', 'voci' => ['Materiali e prestazioni', 'Stratigrafie e dettagli', 'Scelte progettuali']],
      ['titolo' => 'Rappresentazione (CAD/3D)', 'voci' => ['Modellazione di base', 'Tavole e impaginazione', 'Presentazione del progetto']],
    ],
  ],

  'ing-biomedica' => [
    'siteTitle' => 'StudyBo - Ingegneria Biomedica',
    'h2' => 'Ingegneria Biomedica',
    'campus' => 'Campus: Cesena',
    'dipartimento' => "Dipartimento di Ingegneria<br>e Architettura",
    'img' => ['src' => 'laboratorio.png', 'alt' => 'laboratorio e strumenti'],
    'descrizione' => "Il corso di laurea in Ingegneria Biomedica unisce basi di ingegneria con scienze della vita, per progettare
        tecnologie utili alla salute: dispositivi medici, sensori, analisi di segnali, biomateriali e strumenti per la diagnosi.
        È un percorso adatto a chi vuole applicare la tecnologia al mondo medico e al benessere delle persone.",
    'panoramica' => [
      [
        'titolo' => 'Cosa si studia',
        'voci' => [
          'Basi di matematica, fisica e informatica',
          'Chimica e biomateriali',
          'Anatomia e fisiologia (fondamenti)',
          'Segnali biomedici e strumentazione',
          'Progettazione di sistemi e dispositivi',
        ],
      ],
      [
        'titolo' => 'A chi è adatto',
        'voci' => [
          'A chi è curioso del mondo medico e scientifico',
          'A chi vuole capire come funzionano strumenti e sensori',
          'A chi ama unire teoria e applicazioni pratiche',
          'A chi vuole lavorare tra tecnologia e salute',
        ],
      ],
      [
        'titolo' => 'Sbocchi tipici',
        'voci' => [
          'Industria di dispositivi medici',
          'Laboratori e aziende biomedicali',
          'Ricerca e sviluppo (R&D)',
          'Analisi segnali e dati in ambito clinico',
          'Supporto tecnico e gestione tecnologie sanitarie',
        ],
      ],
    ],
    'materie' => [
      ['titolo' => 'Fisica', 'voci' => ['Meccanica e dinamica', 'Lavoro ed energia', 'Esercizi svolti']],
      ['titolo' => 'Chimica', 'voci' => ['Nomenclatura e reazioni', 'Soluzioni e concentrazioni', 'Ripasso veloce']],
      ['titolo' => 'Anatomia', 'voci' => ['Apparati principali', 'Terminologia', 'Schemi e mappe']],
      ['titolo' => 'Fisiologia', 'voci' => ['Funzionamento dei sistemi', 'Regolazione e feedback', 'Appunti e riassunti']],
      ['titolo' => 'Biomateriali', 'voci' => ['Materiali e proprietà', 'Compatibilità e applicazioni', 'Casi d’uso']],
      ['titolo' => 'Segnali Biomedici', 'voci' => ['Acquisizione e filtraggio', 'Analisi di segnali (ECG, ecc.)', 'Strumentazione di base']],
    ],
  ],

  'lm-biomedica' => [
    'siteTitle' => 'StudyBo - Magistrale Ingegneria Biomedica',
    'h2' => 'Magistrale in Ingegneria Biomedica',
    'campus' => 'Campus: Cesena',
    'dipartimento' => "Dipartimento di Ingegneria<br>e Architettura",
    'img' => ['src' => 'bio.png', 'alt' => 'strumentazione medica e tecnologia'],
    'descrizione' => "La Laurea Magistrale in Ingegneria Biomedica approfondisce la progettazione e l’analisi di tecnologie per la salute:
        dispositivi medici, strumentazione, elaborazione di segnali e immagini, biomateriali e sistemi per il supporto clinico.
        È pensata per chi vuole lavorare su progetti più complessi, con un taglio più avanzato e orientato a ricerca e R&D.",
    'panoramica' => [
      [
        'titolo' => 'Cosa si approfondisce',
        'voci' => [
          'Progettazione avanzata di dispositivi e sistemi biomedicali',
          'Segnali e immagini biomediche (analisi e metodi)',
          'Biomateriali e tecnologie per impianti',
          'Strumentazione e sensoristica',
          'Metodi per validazione e sperimentazione',
        ],
      ],
      [
        'titolo' => 'A chi è adatto',
        'voci' => [
          'A chi vuole lavorare su progetti “clinici” e tecnologici',
          'A chi è interessato a R&D e innovazione',
          'A chi vuole approfondire segnali, immagini e dispositivi',
          'A chi punta a una tesi più tecnica o sperimentale',
        ],
      ],
      [
        'titolo' => 'Sbocchi tipici',
        'voci' => [
          'R&D in aziende di dispositivi medicali',
          'Ingegneria clinica e tecnologie sanitarie',
          'Elaborazione segnali/immagini per applicazioni mediche',
          'Biomateriali e progettazione protesi',
          'Ricerca in laboratorio e sviluppo prototipi',
        ],
      ],
    ],
    'materie' => [
      ['titolo' => 'Immagini Biomediche', 'voci' => ['Filtri e trasformazioni', 'Segmentazione (basi)', 'Valutazione e metriche']],
      ['titolo' => 'Segnali Biomedici Avanzati', 'voci' => ['Filtraggio e analisi nel tempo/frequenza', 'Rumore e artefatti', 'Applicazioni (ECG/EEG, ecc.)']],
      ['titolo' => 'Dispositivi Medici', 'voci' => ['Progettazione e requisiti', 'Affidabilità e sicurezza', 'Validazione e test']],
      ['titolo' => 'Biomateriali Avanzati', 'voci' => ['Materiali per impianti', 'Interazione con tessuti', 'Applicazioni e casi studio']],
      ['titolo' => 'Sensoristica e Strumentazione', 'voci' => ['Sensori e acquisizione dati', 'Calibrazione e misure', 'Prototipi e laboratorio']],
      ['titolo' => 'Tesi e Progetto Finale', 'voci' => ['Scelta area e obiettivi', 'Metodi e risultati', 'Presentazione e discussione']],
    ],
  ],

  'ing-elettronica' => [
    'siteTitle' => 'StudyBo - Ingegneria Elettronica',
    'h2' => 'Ingegneria Elettronica',
    'campus' => 'Campus: Cesena',
    'dipartimento' => "Dipartimento di Ingegneria<br>e Architettura",
    'img' => ['src' => 'chip.jpg', 'alt' => 'circuito elettronico e componenti'],
    'descrizione' => "Il corso di laurea in Ingegneria Elettronica unisce matematica e fisica con lo studio di circuiti, segnali e sistemi,
        elettronica analogica e digitale. Impari a progettare e analizzare dispositivi e sistemi elettronici, dalla teoria
        fino alle applicazioni pratiche in laboratorio.",
    'panoramica' => [
      [
        'titolo' => 'Cosa si studia',
        'voci' => [
          'Matematica e fisica per l’ingegneria',
          'Circuiti elettrici e reti',
          'Elettronica analogica e digitale',
          'Segnali e sistemi',
          'Misure e strumentazione',
        ],
      ],
      [
        'titolo' => 'A chi è adatto',
        'voci' => [
          'A chi è curioso di circuiti e componenti',
          'A chi ama la matematica applicata',
          'A chi vuole capire come funziona l’hardware',
          'A chi preferisce anche attività pratiche/laboratorio',
        ],
      ],
      [
        'titolo' => 'Sbocchi tipici',
        'voci' => [
          'Progettazione elettronica (analogica/digitale)',
          'Embedded e sistemi hardware/software',
          'Automazione e sensori',
          'Telecomunicazioni (basi e applicazioni)',
          'Misure, test e qualità',
        ],
      ],
    ],
    'materie' => [
      ['titolo' => 'Circuiti 1', 'voci' => ['Leggi di Kirchhoff', 'Reti e metodi di analisi', 'Esercizi svolti']],
      ['titolo' => 'Elettronica Analogica', 'voci' => ['Diodi e transistor (basi)', 'Amplificatori operazionali', 'Analisi circuiti']],
      ['titolo' => 'Elettronica Digitale', 'voci' => ['Porte logiche e combinatoria', 'Sequenziali e registri', 'Esercizi e progetti']],
      ['titolo' => 'Segnali e Sistemi', 'voci' => ['Trasformate (basi)', 'Risposta di sistemi', 'Esercizi e formulari']],
      ['titolo' => 'Misure Elettroniche', 'voci' => ['Strumenti e incertezze', 'Calibrazione', 'Laboratorio']],
      ['titolo' => 'Architettura degli Elaboratori', 'voci' => ['CPU, memoria e bus', 'Cache e pipeline', 'Concetti hardware']],
    ],
  ],

  'ing-inf' => [
    'siteTitle' => 'StudyBo - Ingegneria e Scienze Informatiche',
    'h2' => 'Ingegneria e Scienze Informatiche',
    'campus' => 'Campus: Cesena',
    'dipartimento' => "Dipartimento di Ingegneria<br>e Architettura",
    'img' => ['src' => 'lock.png', 'alt' => 'computer e studio'],
    'descrizione' => "Il corso di laurea in Ingegneria e Scienze Informatiche ti dà basi solide per progettare e sviluppare software,
        capire come funzionano sistemi e reti, e lavorare con dati e applicazioni reali. È un percorso tecnico, pratico e
        molto richiesto, con tante opportunità di crescita anche verso la magistrale.",
    'panoramica' => [
      [
        'titolo' => 'Cosa si studia',
        'voci' => [
          'Programmazione e strutture dati',
          'Matematica e algebra per l’ingegneria',
          'Sistemi operativi e architettura degli elaboratori',
          'Basi di dati e progettazione',
          'Reti e servizi di rete (fondamenti)',
        ],
      ],
      [
        'titolo' => 'A chi è adatto',
        'voci' => [
          'A chi vuole imparare a progettare e sviluppare software',
          'A chi ama risolvere problemi con logica e metodo',
          'A chi è curioso di reti, dati e sistemi',
          'A chi vuole un percorso tecnico con basi solide',
        ],
      ],
      [
        'titolo' => 'Sbocchi tipici',
        'voci' => [
          'Sviluppo software (web, app, backend)',
          'Sistemi e reti (supporto tecnico, networking di base)',
          'Data e basi di dati (supporto e sviluppo)',
          'Cybersecurity (basi) e ruoli junior',
          'Proseguimento con magistrale e specializzazione',
        ],
      ],
    ],
    'materie' => [
      ['titolo' => 'Programmazione (C / Java)', 'voci' => ['Fondamenti, strutture dati', 'Esercizi e progetti', 'Debug e ragionamento']],
      ['titolo' => 'Analisi 1', 'voci' => ['Limiti e derivate', 'Integrali', 'Esercizi svolti']],
      ['titolo' => 'Algebra Lineare', 'voci' => ['Spazi vettoriali', 'Matrici e diagonalizzazione', 'Formulari e schemi']],
      ['titolo' => 'Basi di Dati', 'voci' => ['Modello relazionale', 'SQL (basi)', 'Progettazione concettuale']],
      ['titolo' => 'Reti', 'voci' => ['Modello ISO/OSI e TCP/IP', 'Indirizzamento e servizi', 'Esercizi e casi']],
      ['titolo' => 'Sistemi Operativi', 'voci' => ['Processi e scheduling', 'Memoria e file system', 'Concetti chiave']],
    ],
  ],

  'lm-inf' => [
    'siteTitle' => 'StudyBo - Magistrale Ingegneria e Scienze Informatiche',
    'h2' => 'Magistrale in Ingegneria e Scienze Informatiche',
    'campus' => 'Campus: Cesena',
    'dipartimento' => "Dipartimento di Ingegneria<br>e Architettura",
    'img' => ['src' => 'cloud.jpg', 'alt' => 'computer e progetto software'],
    'descrizione' => "La Laurea Magistrale in Ingegneria e Scienze Informatiche approfondisce le competenze acquisite in triennale,
        con un focus più forte su progettazione avanzata, sistemi complessi, ricerca e specializzazioni.
        È pensata per chi vuole lavorare su architetture, dati, sicurezza, AI o ingegneria del software in modo più strutturato.",
    'panoramica' => [
      [
        'titolo' => 'Cosa si approfondisce',
        'voci' => [
          'Progettazione software avanzata e architetture',
          'Sistemi distribuiti e cloud',
          'Data engineering e analisi dei dati',
          'Sicurezza informatica e metodi di difesa',
          'AI e approcci moderni allo sviluppo',
        ],
      ],
      [
        'titolo' => 'A chi è adatto',
        'voci' => [
          'A chi vuole specializzarsi in un’area precisa',
          'A chi preferisce progetti grandi e “reali”',
          'A chi vuole fare tesi più tecnica o sperimentale',
          'A chi mira a ruoli senior o di ricerca',
        ],
      ],
      [
        'titolo' => 'Sbocchi tipici',
        'voci' => [
          'Software engineer (backend, full-stack, mobile)',
          'Cloud / DevOps engineer',
          'Data engineer / Data analyst',
          'Cybersecurity engineer',
          'R&D e ruoli tecnici avanzati',
        ],
      ],
    ],
    'materie' => [
      ['titolo' => 'Sistemi Distribuiti e Cloud', 'voci' => ['Microservizi e API', 'Scalabilità e resilienza', 'Concetti base di cloud']],
      ['titolo' => 'Cybersecurity', 'voci' => ['Minacce e difese', 'Crittografia (basi e applicazioni)', 'Security by design']],
      ['titolo' => 'Data e Machine Learning', 'voci' => ['Pipeline e preprocessing', 'Modelli e valutazione', 'Analisi e visualizzazione']],
      ['titolo' => 'Ingegneria del Software Avanzata', 'voci' => ['Pattern e architetture', 'Testing e quality', 'Progetti complessi']],
      ['titolo' => 'Sistemi e Reti Avanzate', 'voci' => ['Performance e misure', 'Protocolli e servizi', 'Affidabilità e sicurezza']],
      ['titolo' => 'Tesi e Progetto Finale', 'voci' => ['Scelta dell’argomento', 'Documentazione e risultati', 'Presentazione e discussione']],
    ],
  ],

  'lm-elettrica' => [
    'siteTitle' => 'StudyBo - Magistrale Ingegneria Elettrica',
    'h2' => 'Magistrale in Ingegneria Elettrica',
    'campus' => 'Campus: Cesena',
    'dipartimento' => "Dipartimento di Ingegneria<br>e Architettura",
    'img' => ['src' => 'lavoro.jpg', 'alt' => 'impianti elettrici e reti di energia'],
    'descrizione' => "La Laurea Magistrale in Ingegneria Elettrica approfondisce i sistemi elettrici e di energia, l’elettronica di potenza,
        gli azionamenti e il controllo, con un approccio più avanzato e orientato alla progettazione e alla simulazione.
        È indicata per chi vuole lavorare su impianti, reti, automazione e soluzioni legate alla transizione energetica.",
    'panoramica' => [
      [
        'titolo' => 'Cosa si approfondisce',
        'voci' => [
          'Sistemi elettrici di potenza e reti',
          'Convertitori, elettronica di potenza e azionamenti',
          'Controllo avanzato e automazione',
          'Macchine elettriche (modellazione e prestazioni)',
          'Energia, efficienza e integrazione di rinnovabili',
        ],
      ],
      [
        'titolo' => 'A chi è adatto',
        'voci' => [
          'A chi ama energia, impianti e sistemi complessi',
          'A chi vuole un percorso tecnico orientato all’industria',
          'A chi è interessato a smart grid e sostenibilità',
          'A chi vuole lavorare su progettazione e simulazione',
        ],
      ],
      [
        'titolo' => 'Sbocchi tipici',
        'voci' => [
          'Progettazione e gestione di impianti elettrici',
          'Power systems / Smart grid engineer',
          'Elettronica di potenza e azionamenti',
          'Automazione industriale e controllo',
          'Energy management e consulenza tecnica',
        ],
      ],
    ],
    'materie' => [
      ['titolo' => 'Sistemi Elettrici per l’Energia', 'voci' => ['Reti e flussi di potenza (basi)', 'Stabilità e affidabilità', 'Casi studio e simulazioni']],
      ['titolo' => 'Elettronica di Potenza', 'voci' => ['Convertitori AC/DC e DC/DC', 'PWM e controllo', 'Applicazioni industriali']],
      ['titolo' => 'Azionamenti Elettrici', 'voci' => ['Motori e controlli', 'Inverter e pilotaggio', 'Esempi applicativi']],
      ['titolo' => 'Controllo Avanzato', 'voci' => ['Modellazione e sistemi', 'Regolatori e stabilità', 'Laboratorio/simulazioni']],
      ['titolo' => 'Impianti e Protezioni', 'voci' => ['Norme e sicurezza', 'Protezioni e selettività', 'Progetto impianto (basi)']],
      ['titolo' => 'Tesi e Progetto Finale', 'voci' => ['Definizione obiettivi', 'Sperimentazione o simulazioni', 'Presentazione e discussione']],
    ],
  ],
];

// slug richiesto
$slug = isset($_GET['slug']) ? trim((string)$_GET['slug']) : '';
$corso = $corsi[$slug] ?? null;

// fallback: se non esiste/slug vuoto, mostro una “lista corsi”
if ($corso === null) {
  $siteTitle = 'StudyBo - Corsi';
} else {
  $siteTitle = $corso['siteTitle'];
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
    <a href="home.php" class="brand" aria-label="Torna alla homepage">
      <img src="logo.libro.PNG" alt="logo StudyBo" class="logo" width="40" height="40"/>
      <span class="brand-title">StudyBO</span>
    </a>

    <button class="bottone-lingua" type="button" aria-label="Cambia lingua">
      IT <span class="freccina" aria-hidden="true">▼</span>
    </button>

    <button class="menu-hamburger" type="button" aria-label="Apri menu"></button>
  </header>

  <main class="pagina">

    <?php if ($corso === null): ?>
      <section class="intro">
        <header class="testo-intro">
          <h2>Corsi di laurea</h2>
          <p class="sottotitolo">Seleziona un corso</p>
          <h3>StudyBO</h3>
        </header>

        <p class="descrizione-intro">
          Non hai selezionato un corso (o lo slug non esiste). Scegli qui sotto:
        </p>

        <div class="griglia-risorse">
          <?php foreach ($corsi as $s => $c): ?>
            <article class="box-risorse">
              <h3 class="titolo-piccolo"><?= e($c['h2']) ?></h3>
              <ul class="lista-semplice">
                <li><a class="link-file" href="corso.php?slug=<?= e($s) ?>">Apri pagina →</a></li>
              </ul>
            </article>
          <?php endforeach; ?>
        </div>
      </section>

    <?php else: ?>
      <section class="intro">
        <header class="testo-intro">
          <h2><?= e($corso['h2']) ?></h2>
          <p class="sottotitolo"><?= e($corso['campus']) ?></p>
          <h3><?= $corso['dipartimento'] ?></h3>
        </header>

        <figure class="foto-intro">
          <img src="<?= e($corso['img']['src']) ?>" alt="<?= e($corso['img']['alt']) ?>">
        </figure>

        <p class="descrizione-intro">
          <?= e($corso['descrizione']) ?>
        </p>
      </section>

      <section class="sezione">
        <h2 class="titolo-sezione">Panoramica del corso</h2>

        <div class="griglia-risorse">
          <?php foreach ($corso['panoramica'] as $b): ?>
            <article class="box-risorse">
              <h3 class="titolo-piccolo"><?= e($b['titolo']) ?></h3>
              <ul class="lista-semplice">
                <?php foreach ($b['voci'] as $voce): ?>
                  <li><?= e($voce) ?></li>
                <?php endforeach; ?>
              </ul>
            </article>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="sezione">
        <h2 class="titolo-sezione">Risorse per questo corso</h2>
        <p class="testo-semplice">
          Materiali utili per gli esami, i progetti e per organizzare lo studio.
        </p>

        <div class="griglia-risorse">
          <article class="box-risorse">
            <h3 class="titolo-piccolo">Link rapido</h3>
            <ul class="lista-semplice">
              <li><a class="link-file" href="risorse.php">Vai alla pagina Risorse →</a></li>
            </ul>
          </article>

          <article class="box-risorse">
            <h3 class="titolo-piccolo">Suggerimento</h3>
            <p class="testo-semplice">
              Nei Study Group potete dividere lo studio, confrontare esercizi e fare review insieme: spesso si risparmia un sacco di tempo.
            </p>
          </article>
        </div>
      </section>

      <section class="sezione">
        <h2 class="titolo-sezione">Materie più gettonate</h2>

        <div class="griglia-risorse">
          <?php foreach ($corso['materie'] as $m): ?>
            <article class="box-risorse">
              <h3 class="titolo-piccolo"><?= e($m['titolo']) ?></h3>
              <ul class="lista-semplice">
                <?php foreach ($m['voci'] as $voce): ?>
                  <li><?= e($voce) ?></li>
                <?php endforeach; ?>
              </ul>
            </article>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="sezione">
        <h2 class="titolo-sezione">Prossimi passi</h2>

        <div class="griglia-risorse">
          <article class="box-risorse">
            <h3 class="titolo-piccolo">Cerca uno Study Group</h3>
            <ul class="lista-semplice">
              <li><a class="link-file" href="#">Vedi i gruppi attivi →</a></li>
            </ul>
          </article>

          <article class="box-risorse">
            <h3 class="titolo-piccolo">Scarica materiale</h3>
            <ul class="lista-semplice">
              <li><a class="link-file" href="risorse.php">Apri le risorse →</a></li>
            </ul>
          </article>
        </div>
      </section>
    <?php endif; ?>

  </main>

  <footer class="pie-pagina">
    <p>Impara meglio, insieme.</p>
  </footer>
</body>
</html>
