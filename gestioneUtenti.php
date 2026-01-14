<?php 
require_once 'db/DatabaseHelper.php';
require_once 'db/config.php';

$db = new DatabaseHelper(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

session_start();

$messaggio = '';
$errore = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['azione']) && $_POST['azione'] === 'elimina') {
        $username = trim($_POST['username'] ?? '');
        if (!empty($username)) {
            $result = $db->deleteUser($username);
            if ($result) {
                $messaggio = 'Utente eliminato con successo!';
            } else {
                $errore = 'Errore durante l\'eliminazione dell\'utente.';
            }
        }
    }
    
    if (isset($_POST['azione']) && $_POST['azione'] === 'blocca') {
        $username = trim($_POST['username'] ?? '');
        if (!empty($username)) {
            $result = $db->blockUser($username);
            if ($result) {
                $messaggio = 'Utente bloccato con successo!';
            } else {
                $errore = 'Errore durante il blocco dell\'utente.';
            }
        }
    }
    
    if (isset($_POST['azione']) && $_POST['azione'] === 'sblocca') {
        $username = trim($_POST['username'] ?? '');
        if (!empty($username)) {
            $result = $db->unblockUser($username);
            if ($result) {
                $messaggio = 'Utente sbloccato con successo!';
            } else {
                $errore = 'Errore durante lo sblocco dell\'utente.';
            }
        }
    }
    
    if (isset($_POST['azione']) && $_POST['azione'] === 'rendi_admin') {
        $username = trim($_POST['username'] ?? '');
        if (!empty($username)) {
            $result = $db->makeUserAdmin($username);
            if ($result) {
                $messaggio = 'Utente promosso ad amministratore!';
            } else {
                $errore = 'Errore durante la promozione.';
            }
        }
    }
    
    if (isset($_POST['azione']) && $_POST['azione'] === 'rimuovi_admin') {
        $username = trim($_POST['username'] ?? '');
        if (!empty($username)) {
            $result = $db->removeUserAdmin($username);
            if ($result) {
                $messaggio = 'Privilegi di amministratore rimossi!';
            } else {
                $errore = 'Errore durante la rimozione dei privilegi.';
            }
        }
    }
    
    header('Location: ' . $_SERVER['PHP_SELF'] . ($messaggio ? '?msg=' . urlencode($messaggio) : '') . ($errore ? '?err=' . urlencode($errore) : ''));
    exit;
}

if (isset($_GET['msg'])) {
    $messaggio = htmlspecialchars($_GET['msg']);
}
if (isset($_GET['err'])) {
    $errore = htmlspecialchars($_GET['err']);
}

$utentiNormali = $db->getNormalUsers();
$utentiBloccati = $db->getBlockedUsers();
$amministratori = $db->getAdminUsers();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="css/style.css">
    <title>StudyBo - Gestione Utenti</title>
    <style>
        .user-stats {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .user-stats p {
            margin: 5px 0;
            font-weight: bold;
        }
    </style>
</head>

<body>

<header>
    <img src="logo.libro.png.PNG" alt="logo studybo libro" width="100" height="100"/>
    <h1>StudyBo</h1>
    <button class="lang-switch" type="button">IT</button>
    <div class="hamburger-menu">
        <button class="hamburger" type="button" id="hamburgerBtn">☰</button>
        <div class="dropdown-menu" id="dropdownMenu">
            <button type="button">Admin</button>
            <div class="separator"></div>
            <a href="gestioneCDL.php">Gestione CDL</a>
            <a href="gestioneEsami.php">Gestione Esami</a>
            <a href="gestioneStudyGroup.php">Gestione Study Group</a>
            <a href="gestioneUtenti.php">Gestione Utenti</a>
            <div class="separator"></div>
            <button type="button">Log Out</button>
        </div>
    </div>
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

    <div class="user-stats">
        <p>📊 Statistiche Sistema</p>
        <p>Utenti Attivi: <?php echo count($utentiNormali); ?> | 
           Utenti Bloccati: <?php echo count($utentiBloccati); ?> | 
           Amministratori: <?php echo count($amministratori); ?></p>
    </div>

    <section class="container">
        <div class="list">
            <h2>Utenti Attivi (<?php echo count($utentiNormali); ?>)</h2>

            <?php if (empty($utentiNormali)): ?>
                <div class="card">
                    <p>Nessun utente presente nel sistema.</p>
                </div>
            <?php else: ?>
                <?php foreach ($utentiNormali as $utente): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($utente['nome'] . ' ' . $utente['cognome']); ?></h3>
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($utente['username']); ?></p>
                        <div class="card-buttons">
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler bloccare questo utente?');">
                                <input type="hidden" name="azione" value="blocca">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($utente['username']); ?>">
                                <button class="btn-block" type="submit">Blocca</button>
                            </form>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo utente? Questa azione è irreversibile!');">
                                <input type="hidden" name="azione" value="elimina">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($utente['username']); ?>">
                                <button class="btn-delete" type="submit">Elimina</button>
                            </form>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Confermi di voler rendere questo utente amministratore?');">
                                <input type="hidden" name="azione" value="rendi_admin">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($utente['username']); ?>">
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
                    <div class="card" style="background-color: #fff3cd; border-left: 4px solid #ffc107;">
                        <h3><?php echo htmlspecialchars($utente['nome'] . ' ' . $utente['cognome']); ?> <span style="color: #856404;">🔒 BLOCCATO</span></h3>
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($utente['username']); ?></p>
                        <div class="card-buttons">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="azione" value="sblocca">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($utente['username']); ?>">
                                <button class="btn-unblock" type="submit">Sblocca</button>
                            </form>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo utente? Questa azione è irreversibile!');">
                                <input type="hidden" name="azione" value="elimina">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($utente['username']); ?>">
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
                    <div class="card" style="background-color: #d1ecf1; border-left: 4px solid #0c5460;">
                        <h3><?php echo htmlspecialchars($admin['nome'] . ' ' . $admin['cognome']); ?> <span style="color: #0c5460;">👑 ADMIN</span></h3>
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($admin['username']); ?></p>
                        <?php if ($admin['Bloccato']): ?>
                            <p style="color: #856404;"><strong>⚠️ ACCOUNT BLOCCATO</strong></p>
                        <?php endif; ?>
                        <div class="card-buttons">
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler rimuovere i privilegi di amministratore a questo utente?');">
                                <input type="hidden" name="azione" value="rimuovi_admin">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>">
                                <button class="btn-block" type="submit">Rimuovi Admin</button>
                            </form>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo amministratore? Questa azione è irreversibile!');">
                                <input type="hidden" name="azione" value="elimina">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');
        
        hamburgerBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });
        
        document.addEventListener('click', function(e) {
            if (!hamburgerBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    });
</script>
</body>
</html>