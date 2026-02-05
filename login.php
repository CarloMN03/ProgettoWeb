<?php
require_once 'bootstrap.php';

$siteTitle = 'StudyBo - Accesso';
$errore = "";
$successo = "";

/* Se già loggato */
if (isset($_SESSION["username"])) {
    if (!empty($_SESSION["amministratore"]) && $_SESSION["amministratore"] == 1) {
        header("Location: gestioneCDL.php");
    } else {
        header("Location: profilo.php");
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* LOGIN */
    if (isset($_POST['azione']) && $_POST['azione'] === 'login') {

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $errore = "Compila username e password.";
        } else {
          $usr = $dbh->checkUserLogin($username, $password);

          if (empty($usr)) {
            $errore = "Username o password errati.";
          } else {
            $_SESSION["username"] = $usr[0]["username"];
            $_SESSION["nome"] = $usr[0]["nome"];
            $_SESSION["amministratore"] = $usr[0]["amministratore"];

            if ($_SESSION["amministratore"] == 1) {
              header("Location: gestioneCDL.php");
            } else {
              header("Location: profilo.php");
            }
              exit;
          }
        }
    }    

    /* REGISTRAZIONE */
    if (isset($_POST['azione']) && $_POST['azione'] === 'register') {

        $nome     = trim($_POST['nome'] ?? '');
        $cognome  = trim($_POST['cognome'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($nome === '' || $cognome === '' || $username === '' || $password === '') {
            $errore = "Compila tutti i campi.";
        } elseif (strlen($username) < 3) {
            $errore = "Lo username deve avere almeno 3 caratteri.";
        } elseif (strlen($password) < 6) {
            $errore = "La password deve avere almeno 6 caratteri.";
        } else {
            $pass = password_hash($password,PASSWORD_DEFAULT);
            $ok = $dbh->insertUser($nome, $cognome, $username, $pass);

            if ($ok) {
                $successo = "Account creato con successo! Ora puoi accedere.";
            } else {
                $errore = "Username già esistente. Scegline un altro.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= e($siteTitle) ?></title>

  <link rel="stylesheet" href="style.css">

  <!-- Preferenze di accessibilità -->
  <script>
  (function () {
    try {
      const raw = localStorage.getItem("sb_prefs");
      const p = raw ? JSON.parse(raw) : {};
      const root = document.documentElement;

      root.classList.remove(
        "theme-dark",
        "theme-contrast",
        "theme-cvd",
        "no-images",
        "reduce-motion"
      );

      if (p.theme === "dark") root.classList.add("theme-dark");
      if (p.theme === "contrast") root.classList.add("theme-contrast");
      if (p.theme === "cvd") root.classList.add("theme-cvd");

      const fs = Number(p.fontScale || 1);
      root.style.setProperty("--font-scale", String(fs));

      if (p.noImages) root.classList.add("no-images");
    } catch (e) {}
  })();
  </script>
</head>

<body>

<a class="skip-link" href="#contenuto">Salta al contenuto</a>

<?php include 'header.php'; ?>

<main class="pagina" id="contenuto" role="main">

  <!-- MESSAGGI -->
  <?php if ($errore): ?>
    <div class="alert alert-error" role="alert">
      <?= e($errore) ?>
    </div>
  <?php endif; ?>

  <?php if ($successo): ?>
    <div class="alert alert-success" role="status">
      <?= e($successo) ?>
    </div>
  <?php endif; ?>

  <section class="sezione">
    <div class="griglia-login">

      <!-- LOGIN -->
      <article class="card-corso">
        <h2 class="card-title">Accedi</h2>

        <form class="acc-form" method="post">
          <input type="hidden" name="azione" value="login">

          <label for="login-username">Username</label>
          <input id="login-username" name="username"
                 type="text" class="sb-input"
                 autocomplete="username" required>

          <label for="login-password">Password</label>
          <input id="login-password" name="password"
                 type="password" class="sb-input"
                 autocomplete="current-password" required>

          <button class="btn-primary" type="submit">
            Entra
          </button>
        </form>
      </article>

      <!-- REGISTRAZIONE -->
      <article class="card-corso">
        <h2 class="card-title">Nuovo utente?</h2>

        <form class="acc-form" method="post">
          <input type="hidden" name="azione" value="register">

          <label for="nome">Nome</label>
          <input id="nome" name="nome"
                 type="text" class="sb-input"
                 autocomplete="given-name" required>

          <label for="cognome">Cognome</label>
          <input id="cognome" name="cognome"
                 type="text" class="sb-input"
                 autocomplete="family-name" required>

          <label for="reg-username">Username</label>
          <input id="reg-username" name="username"
                 type="text" class="sb-input"
                 autocomplete="username"
                 placeholder="Scegli uno username"
                 required>

          <label for="reg-password">Password</label>
          <input id="reg-password" name="password"
                 type="password" class="sb-input"
                 autocomplete="new-password"
                 placeholder="Minimo 6 caratteri"
                 required>

          <button class="btn-primary" type="submit">
            Crea account
          </button>
        </form>
      </article>

    </div>
  </section>
</main>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>

