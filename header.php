<?php
// 1. Verifichiamo lo stato dell'utente
$isLoggedIn = isset($_SESSION["username"]);
$isAdmin = isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == 1;

// 2. Logica del Logo
$homeLink = $isAdmin ? "gestioneCDL.php" : "home.php";
?>

<script src="js/tema.js"></script>

<header class="topbar">
  <div class="topbar-inner">
    <a href="<?php echo $homeLink; ?>" class="brand" aria-label="Torna alla Home">
      <img src="img/logo.studybo.png" alt="StudyBo" class="logo">
    </a>

    <div class="topbar-actions">
      <div class="ico-notifica"></div>
      <a class="btn-ghost" href="accessibilita.php">Accessibilità</a>
      
      <button class="btn-menu" id="btn-hamburger" type="button" aria-label="Apri menu">
        <span class="hamb-icon">
          <span class="hamb-line"></span>
          <span class="hamb-line"></span>
          <span class="hamb-line"></span>
        </span>
      </button>
    </div>
  </div>

  <div class="menu-overlay" id="menu-overlay" hidden></div>

  <nav class="menu-mobile" id="menu-principale" hidden>
    <ul class="menu-list">
      
      <?php if (!$isAdmin): ?>
          <li><a href="home.php">Home</a></li>
          <li><a href="corsi.php">Corsi</a></li>
          <li><a href="studygroup-list.php">Study Group</a></li>
      <?php endif; ?>

      <?php if ($isLoggedIn): ?>
          <li><a href="profilo.php">Il mio Profilo</a></li>

          <?php if ($isAdmin): ?>
              <li><a href="home.php">Homepage</a></li> 
              <li><a href="gestioneCDL.php">Gestione</a></li>
          <?php endif; ?>
          
          <li>
            <a href="logout.php" class="link-logout">
                Logout (<?php echo e($_SESSION["nome"]); ?>)
            </a>
          </li>
      
      <?php else: ?>
          <li><a href="login.php">Accedi / Registrati</a></li>
      <?php endif; ?>

      <li><a href="contatti.php">Contatti</a></li>
      <li><a href="accessibilita.php">Accessibilità</a></li>
    </ul>
  </nav>
</header>