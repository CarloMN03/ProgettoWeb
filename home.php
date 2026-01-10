<?php
session_start();

$lang = $_SESSION['lang'] ?? 'it';

if (isset($_GET['lang']) && in_array($_GET['lang'], ['it', 'en'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
}

$translations = include "lang/$lang.php";

$cookieConsent = $_COOKIE['cookie_consent'] ?? null;
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="style.css"/>
    <title>StudyBo - Impara meglio, insieme</title>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
</head>
<body>
    <!-- Cookie Consent Banner -->
    <?php if (!$cookieConsent): ?>
    <div id="cookie-banner">
        <p><?= $translations['cookie_testo'] ?></p>
        <button id="accept-cookies"><?= $translations['accetta'] ?></button>
        <button id="reject-cookies"><?= $translations['rifiuta'] ?></button>
    </div>
    <?php endif; ?>

    <header>
        <div>
            <img src="logo.libro.png.PNG" alt="logo studybo libro"/><h1>StudyBo</h1>
        </div>
        <div class="lang-switch">
            <a href="?lang=it">IT</a> |
            <a href="?lang=en">EN</a>
            <button id="theme-toggle">🌙</button>
        </div>
        <button class="hamburger" type ="button"></button>
    </header>
    <main>
        <section class="intro">
            <div class="descrizione">
                <h2>StudyBo</h2>
                <h3>Impara meglio, insieme</h3>
                <h4>Dipartimento di Ingegneria e Archiettura</h4> 
            </div><div class="img-destra">
                <img src="scrivania.png" alt="scrivania con computer">
            </div>
            <p>La piattaforma Unibo per studenti di Ingeneria ed Architettura che permette di creare, trovare e unirsi in gruppi di studio. Condividi conoscenze, migliora la tua preparazione e unisciti ad una comunità universitaria.</p>
        </section>
        <section> 
            <h2>Corsi di Laurea con Study Group attivi</h2>
            <div class="card-img"> <img src="p1.jpg" alt="computer"></div>
            <h3>Laurea Ingegneria e Scienze Informatiche</h3>
            <p>Campus : Cesena</p>
            <p>Esami con Study Group: 14</p>
            <a href="#"> Esplora il corso </a>
            <div class="card-img"> <img src="p2.png" alt="computer" height="100"> </div>
            <h3>Laurea Ingegneria Biomedica</h3>
            <p>Campus : Cesena</p>
            <p>Esami con Study Group: 12</p>
            <a href="#"> Esplora il corso </a>
        </section>
        <aside>
            <h2>Study Group in scadenza</h2>
            <div class="card-img"> <img src="po.png" alt="righe di codice"></div>
            <h3>Programmazione ad oggetti</h3>
            <p>Tema: Ereditarietà</p>
            <p>Luogo: Online</p>
            <p>Data : 01/12/2025</p>
            <p>Ora: 20:00</p>
            <div class="card-img"> <img src="chimica.png" alt="laboratorio"></div>
            <h3>Chimica</h3>
            <p>Tema: Nomenclatura</p>
            <p>Luogo: Online</p>
            <p>Data : 01/12/2025</p>
            <p>Ora: 20:10</p>
            <div class="card-img"><img src="database.png" alt="biblioteca"></div>
            <h3>Base di dati</h3>
            <p>Tema: Indici</p>
            <p>Luogo: Online</p>
            <p>Data : 01/12/2025</p>
            <p>Ora: 20:15</p>

        </aside>
    
    </main>
    <footer>
      <h4><?= $translations['sottotitolo'] ?></h4>
      <p><?= $translations['diritti_riservati'] ?></p>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const btn = document.getElementById("theme-toggle");
        if (btn) {
            const currentTheme = localStorage.getItem("theme");

            if (currentTheme === "dark") {
                document.documentElement.setAttribute("data-theme", "dark");
                btn.textContent = "☀️";
            }

            btn.addEventListener("click", function() {
                let theme = document.documentElement.getAttribute("data-theme");
                
                if (theme === "dark") {
                    document.documentElement.removeAttribute("data-theme");
                    localStorage.setItem("theme", "light");
                    btn.textContent = "🌙";
                } else {
                    document.documentElement.setAttribute("data-theme", "dark");
                    localStorage.setItem("theme", "dark");
                    btn.textContent = "☀️";
                }
            });
        }

        const acceptBtn = document.getElementById('accept-cookies');
        const rejectBtn = document.getElementById('reject-cookies');
        const banner = document.getElementById('cookie-banner');

        acceptBtn?.addEventListener('click', function () {
            document.cookie = "cookie_consent=accepted; max-age=" + 60*60*24*365 + "; path=/";
            if(banner) banner.style.display = 'none';
        });

        rejectBtn?.addEventListener('click', function () {
            document.cookie = "cookie_consent=rejected; max-age=" + 60*60*24*365 + "; path=/";
            if(banner) banner.style.display = 'none';
        });
    });
    </script>

</body>
</html>