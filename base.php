<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/style.css"/>
    <title><?php echo $templateParams["titolo"]; ?></title>
</head>
<body onload="checkNotifica()"> 
    <header>
        <div>
            <img src="./upload/logo.libro.png.PNG" alt="logo studybo libro"/><h1>StudyBo</h1>
        </div><div class="ico-notifica"></div><button class="lang-switch" type="button">IT</button><img src="./upload/hamburger icon.png" alt="apri hamburger menu" id="hamburger" onclick="openMenu('x', 'nav-principale', 'hamburger', <?php echo $templateParams['amministratore']; ?>, '<?php echo $templateParams['nomeutente']; ?>')"/><img src="./upload/x icon.png" alt="chiudi hamburger menu" id="x" onclick="closeForm('x', 'nav-principale', 'hamburger')"/>
    </header>
    <nav id="nav-principale">
    </nav>
    <main>
    <?php
    if(isset($templateParams["nome"])){
        require($templateParams["nome"]);
    }
    ?>
    </main><aside>
            <h2>Study Group in scadenza</h2>
            <ul>
            <?php foreach($templateParams["studygroupscadenza"] as $cardstudygr): ?>
                <li>
                    <div class="card-img"> <img src="<?php echo UPLOAD_DIR.$cardstudygr["imgesame"]; ?>" alt=""></div>
                    <h3><?php echo $cardstudygr["nomeesame"]; ?></h3>
                    <ul>
                        <li>
                            Tema: <?php echo $cardstudygr["tema"]; ?>
                        </li>
                        <li>
                            Luogo: <?php echo $cardstudygr["luogo"]; ?>
                        </li>
                        <li>
                            Data: <?php echo $cardstudygr["data"]; ?>
                        </li>
                        <li>
                            Ora: <?php echo $cardstudygr["ora"]; ?>
                        </li>
                    </ul>
                </li>
            <?php endforeach; ?>
            </ul>
    </aside>
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
    <footer>
      <h4>Imapara meglio, insieme</h4>
      <p>Tutti diritti riservati, 2025</p>
    </footer>
</body>
</html>
