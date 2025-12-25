<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/style.css"/>
    <title><?php echo $templateParams["titolo"]; ?></title>
</head>
<body> 
    <header>
        <div>
            <img src="./upload/logo.libro.png.PNG" alt="logo studybo libro"/><h1>StudyBo</h1>
        </div><button class="lang-switch" type="button">IT</button><button class="hamburger" type ="button"></button>
    </header>
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
                    <div class="card-img"> <img src="<?php echo UPLOAD_DIR.$cardstudygr["imgstudygr"]; ?>" alt=""></div>
                    <h3><?php echo $cardstudygr["nomecdl"]; ?></h3>
                    <ul>
                        <li>
                            Tema: <?php echo $cardstudygr["temastudygroup"]; ?>
                        </li>
                        <li>
                            Luogo: <?php echo $cardstudygr["luogostudygroup"]; ?>
                        </li>
                        <li>
                            Data: <?php echo $cardstudygr["datastudygroup"]; ?>
                        </li>
                        <li>
                            Ora: <?php echo $cardstudygr["orastudygroup"]; ?>
                        </li>
                    </ul>
                </li>
            <?php endforeach; ?>
            </ul>
    </aside>
    <footer>
      <h4>Imapara meglio, insieme</h4>
      <p>Tutti diritti riservati, 2025</p>
    </footer>
</body>
</html>