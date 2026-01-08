<?php
/* BOOT */
declare(strict_types=1);
session_start();
require_once __DIR__ . '/config.php';

/* SE GIA LOGGATA */
if (!empty($_SESSION['user_id'])) {
  header('Location: home.php');
  exit;
}

/* STATO */
$loginError = '';
$registerError = '';
$registerOk = '';

/* POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if ($action === 'login') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
      $loginError = 'Compila username e password.';
    } else {
      $pdo = db();
      $stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = :u OR email = :u LIMIT 1');
      $stmt->execute([':u' => $username]);
      $user = $stmt->fetch();

      if (!$user || !password_verify($password, (string)$user['password_hash'])) {
        $loginError = 'Credenziali non valide.';
      } else {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = (string)$user['username'];
        header('Location: home.php');
        exit;
      }
    }
  }

  if ($action === 'register') {
    $nome = trim((string)($_POST['nome'] ?? ''));
    $cognome = trim((string)($_POST['cognome'] ?? ''));
    $emailUser = trim((string)($_POST['email_user'] ?? ''));
    $cdl = trim((string)($_POST['cdl'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($nome === '' || $cognome === '' || $emailUser === '' || $cdl === '' || $password === '') {
      $registerError = 'Compila tutti i campi.';
    } elseif (!preg_match('/^[a-zA-Z0-9._-]+$/', $emailUser)) {
      $registerError = 'Email non valida (usa solo lettere, numeri, punto, underscore, trattino).';
    } elseif (mb_strlen($password) < 6) {
      $registerError = 'Password troppo corta (minimo 6 caratteri).';
    } else {
      $email = $emailUser . '@studio.unibo.it';
      $username = strtolower($emailUser);
      $hash = password_hash($password, PASSWORD_DEFAULT);

      try {
        $pdo = db();

        $stmt = $pdo->prepare('
          INSERT INTO users (username, nome, cognome, email, cdl, password_hash)
          VALUES (:username, :nome, :cognome, :email, :cdl, :ph)
        ');
        $stmt->execute([
          ':username' => $username,
          ':nome' => $nome,
          ':cognome' => $cognome,
          ':email' => $email,
          ':cdl' => $cdl,
          ':ph' => $hash,
        ]);

        $registerOk = 'Registrazione completata! Ora puoi fare login.';
      } catch (PDOException $ex) {
        $registerError = 'Registrazione non riuscita (utente o email già esistenti).';
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
  <link rel="stylesheet" href="style.css">
  <title>StudyBo - Login</title>
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
      <h2 class="titolo-sezione">Accedi</h2>

      <?php if ($loginError !== ''): ?>
        <p class="testo-semplice"><?= e($loginError) ?></p>
      <?php endif; ?>

      <form class="box-login" action="login.php" method="post">
        <input type="hidden" name="action" value="login">

        <div class="riga-input">
          <label for="username">Username:</label>
          <input id="username" name="username" type="text" required>
        </div>

        <div class="riga-input">
          <label for="password">Password:</label>
          <input id="password" name="password" type="password" required>
        </div>

        <button class="bottone-grigio" type="submit">Login</button>
      </form>
    </section>

    <section class="sezione">
      <h2 class="titolo-sezione">Registrati</h2>

      <?php if ($registerOk !== ''): ?>
        <p class="testo-semplice"><?= e($registerOk) ?></p>
      <?php endif; ?>

      <?php if ($registerError !== ''): ?>
        <p class="testo-semplice"><?= e($registerError) ?></p>
      <?php endif; ?>

      <form class="box-login" action="login.php" method="post">
        <input type="hidden" name="action" value="register">

        <div class="riga-input">
          <label for="nome">Nome:</label>
          <input id="nome" name="nome" type="text" required>
        </div>

        <div class="riga-input">
          <label for="cognome">Cognome:</label>
          <input id="cognome" name="cognome" type="text" required>
        </div>

        <div class="riga-input">
          <label for="email_user">E-mail:</label>
          <div class="input-email">
            <input id="email_user" name="email_user" type="text" required>
            <span class="email-fissa">@studio.unibo.it</span>
          </div>
        </div>

        <div class="riga-input">
          <label for="cdl">CDL:</label>
          <select id="cdl" name="cdl" required>
            <option value="">Seleziona</option>
            <option value="inginf">Ing. e Scienze Informatiche</option>
            <option value="biomed">Ingegneria Biomedica</option>
            <option value="arch">Architettura</option>
            <option value="elettronica">Ingegneria Elettronica</option>
            <option value="elettrica">Ingegneria Elettrica</option>
          </select>
        </div>

        <div class="riga-input">
          <label for="pass2">Password:</label>
          <input id="pass2" name="password" type="password" required>
        </div>

        <button class="bottone-grigio" type="submit">Registra</button>
      </form>
    </section>
  </main>

  <footer class="pie-pagina">
    <p>Impara meglio, insieme.</p>
  </footer>
</body>
</html>
