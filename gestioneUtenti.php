<?php
// gestioneUtenti.php - Sistema di gestione utenti

// Includi il DatabaseHelper
require_once 'db/DatabaseHelper.php';
require_once 'db/config.php';

// Inizializza la connessione al database
$db = new DatabaseHelper(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Inizializza la sessione
session_start();

// Gestione delle operazioni
$messaggio = '';
$errore = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Elimina utente
    if (isset($_POST['azione']) && $_POST['azione'] === 'elimina') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            $result = $db->deleteUser($id);
            if ($result) {
                $messaggio = 'Utente eliminato con successo!';
            } else {
                $errore = 'Errore durante l\'eliminazione dell\'utente.';
            }
        }
    }
    
    // Blocca utente
    if (isset($_POST['azione']) && $_POST['azione'] === 'blocca') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            $result = $db->blockUser($id);
            if ($result) {
                $messaggio = 'Utente bloccato con successo!';
            } else {
                $errore = 'Errore durante il blocco dell\'utente.';
            }
        }
    }
    
    // Sblocca utente
    if (isset($_POST['azione']) && $_POST['azione'] === 'sblocca') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            $result = $db->unblockUser($id);
            if ($result) {
                $messaggio = 'Utente sbloccato con successo!';
            } else {
                $errore = 'Errore durante lo sblocco dell\'utente.';
            }
        }
    }
    
    // Rendi amministratore
    if (isset($_POST['azione']) && $_POST['azione'] === 'rendi_admin') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            $result = $db->makeUserAdmin($id);
            if ($result) {
                $messaggio = 'Utente promosso ad amministratore!';
            } else {
                $errore = 'Errore durante la promozione.';
            }
        }
    }
    
    // Rimuovi amministratore
    if (isset($_POST['azione']) && $_POST['azione'] === 'rimuovi_admin') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            $result = $db->removeUserAdmin($id);
            if ($result) {
                $messaggio = 'Privilegi di amministratore rimossi!';
            } else {
                $errore = 'Errore durante la rimozione dei privilegi.';
            }
        }
    }
    
    // Redirect per evitare re-submit del form
    header('Location: ' . $_SERVER['PHP_SELF'] . ($messaggio ? '?msg=' . urlencode($messaggio) : '') . ($errore ? '?err=' . urlencode($errore) : ''));
    exit;
}

// Recupera messaggi dalla query string
if (isset($_GET['msg'])) {
    $messaggio = htmlspecialchars($_GET['msg']);
}
if (isset($_GET['err'])) {
    $errore = htmlspecialchars($_GET['err']);
}

// Recupera gli utenti dal database divisi per categoria
$utentiNormali = $db->getNormalUsers();
$utentiBloccati = $db->getBlockedUsers();
$amministratori = $db->getAdminUsers();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="style.css">
    <title>StudyBo - Gestione Utenti</title>
</head>

<body>

<header>
    <img src="logo.libro.png.PNG" alt="logo studybo libro" width="100" height="100"/>
    <h1>StudyBo</h1>
    <button class="lang-switch" type="button">IT</button>
    <button class="hamburger" type="button">☰</button>
</header>

<main class="page">

    <section class="intro">
        <div class="descrizione">
            <h2>Area Amministratore</h2>
            <h3>Gestione Utenti</h3>
            <h4>Dipartimento di Ingegneria e Architettura</h4>
        </div>
    </section>

    <?php if ($messaggio): ?>
        <div class="alert alert-success"><?php echo $messaggio; ?></div>
    <?php endif; ?>
    
    <?php if ($errore): ?>
        <div class="alert alert-error"><?php echo $errore; ?></div>
    <?php endif; ?>

    <section class="container">
        <div class="list">
            <h2>Utenti (<?php echo count($utentiNormali); ?>)</h2>

            <?php if (empty($utentiNormali)): ?>
                <div class="card">
                    <p>Nessun utente presente nel sistema.</p>
                </div>
            <?php else: ?>
                <?php foreach ($utentiNormali as $utente): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($utente['Nome'] . ' ' . $utente['Cognome']); ?></h3>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($utente['Email']); ?></p>
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($utente['Username']); ?></p>
                        <div class="card-buttons">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="azione" value="blocca">
                                <input type="hidden" name="id" value="<?php echo $utente['ID']; ?>">
                                <button class="btn-block" type="submit">Blocca</button>
                            </form>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo utente?');">
                                <input type="hidden" name="azione" value="elimina">
                                <input type="hidden" name="id" value="<?php echo $utente['ID']; ?>">
                                <button class="btn-delete" type="submit">Elimina</button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="azione" value="rendi_admin">
                                <input type="hidden" name="id" value="<?php echo $utente['ID']; ?>">
                                <button class="btn-add" type="submit">Rendi Amministratore</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <h2>Utenti Bloccati (<?php echo count($utentiBloccati); ?>)</h2>
            <?php if (empty($utentiBloccati)): ?>
                <div class="card">
                    <p>Nessun utente bloccato.</p>
                </div>
            <?php else: ?>
                <?php foreach ($utentiBloccati as $utente): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($utente['Nome'] . ' ' . $utente['Cognome']); ?></h3>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($utente['Email']); ?></p>
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($utente['Username']); ?></p>
                        <div class="card-buttons">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="azione" value="sblocca">
                                <input type="hidden" name="id" value="<?php echo $utente['ID']; ?>">
                                <button class="btn-unblock" type="submit">Sblocca</button>
                            </form>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo utente?');">
                                <input type="hidden" name="azione" value="elimina">
                                <input type="hidden" name="id" value="<?php echo $utente['ID']; ?>">
                                <button class="btn-delete" type="submit">Elimina</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <aside class="card-add">
            <h2>Amministratori (<?php echo count($amministratori); ?>)</h2>
            <?php if (empty($amministratori)): ?>
                <div class="card">
                    <p>Nessun amministratore presente.</p>
                </div>
            <?php else: ?>
                <?php foreach ($amministratori as $admin): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($admin['Nome'] . ' ' . $admin['Cognome']); ?></h3>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['Email']); ?></p>
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($admin['Username']); ?></p>
                        <div class="card-buttons">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="azione" value="rimuovi_admin">
                                <input type="hidden" name="id" value="<?php echo $admin['ID']; ?>">
                                <button class="btn-block" type="submit">Rimuovi Amministratore</button>
                            </form>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo amministratore?');">
                                <input type="hidden" name="azione" value="elimina">
                                <input type="hidden" name="id" value="<?php echo $admin['ID']; ?>">
                                <button class="btn-delete" type="submit">Elimina</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </aside>
    </section>

</main>

<footer>
    <h4>Impara meglio, insieme</h4>
    <p>Tutti i diritti riservati, 2025</p>
</footer>

</body>
</html>