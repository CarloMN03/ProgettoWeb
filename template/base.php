<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    
    <link rel="stylesheet" href="style.css?v=1.1"/>
    
    <title><?php echo e($templateParams["titolo"]); ?></title>

    </head>

<body onload="typeof checkNotifica === 'function' && checkNotifica()"> 

    <a class="skip-link" href="#contenuto">Salta al contenuto</a>

    <?php include __DIR__ . '/../header.php'; ?>

    <main class="pagina" id="contenuto">
        <?php
        if (isset($templateParams["nome"])) {
            $view = __DIR__ . '/' . ltrim($templateParams["nome"], '/\\');

            if (!is_file($view)) {
                die("Template mancante: " . e($view));
            }

            require $view;
        }
        ?>

        <?php if(isset($templateParams["studygroupscadenza"]) && !empty($templateParams["studygroupscadenza"])): ?>
        <section class="sezione" aria-labelledby="scadenze-sidebar-title">
            <div class="section-head">
                <h2 id="scadenze-sidebar-title">Study Group in scadenza</h2>
            </div>
            
            <div class="griglia-corsi">
                <?php foreach($templateParams["studygroupscadenza"] as $cardstudygr): ?>
                    <article class="card-corso">
                        <div class="card-icon">
                            <img src="img/esami/<?php echo $cardstudygr["imgesame"]; ?>" alt="">
                        </div>
                        <h3 class="card-title"><?php echo e($cardstudygr["nomeesame"]); ?></h3>
                        <ul class="card-meta">
                            <li><strong>Tema:</strong> <?php echo e($cardstudygr["tema"]); ?></li>
                            <li><strong>Luogo:</strong> <?php echo e($cardstudygr["luogo"]); ?></li>
                            <li><strong>Data:</strong> <?php echo e($cardstudygr["data"]); ?> alle <?php echo e($cardstudygr["ora"]); ?></li>
                        </ul>
                        <a class="btn-primary btn-small" href="studygroup.php?idcdl=<?php echo $cardstudygr["idcdl"]; ?>&idesame=<?php echo $cardstudygr["idesame"]; ?>&idstudygroup=<?php echo $cardstudygr["idstudygroup"]; ?>">Visualizza →</a>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../footer.php'; ?>

    <script src="./js/script.js"></script>
    <?php
    if(isset($templateParams["js"])):
        foreach($templateParams["js"] as $script):
    ?>
        <script src="<?php echo $script; ?>"></script>
    <?php
        endforeach;
    endif;
    ?>
</body>
</html>