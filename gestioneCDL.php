<?php
// gestioneCDL.php - Sistema di gestione corsi di laurea

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
$editing = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Aggiungi nuovo corso
    if (isset($_POST['azione']) && $_POST['azione'] === 'aggiungi') {
        $nome = trim($_POST['nome'] ?? '');
        $campus = trim($_POST['campus'] ?? '');
        
        if (!empty($nome) && !empty($campus)) {
            $result = $db->insertCdl($nome, $campus);
            if ($result) {
                $messaggio = 'Corso aggiunto con successo!';
            } else {
                $errore = 'Errore durante l\'aggiunta del corso.';
            }
        } else {
            $errore = 'Compila tutti i campi per aggiungere un corso.';
        }
    }
    
    // Elimina corso
    if (isset($_POST['azione']) && $_POST['azione'] === 'elimina') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            $result = $db->deleteCdl($id);
            if ($result) {
                $messaggio = 'Corso eliminato con successo!';
            } else {
                $errore = 'Errore durante l\'eliminazione del corso.';
            }
        }
    }
    
    // Modifica corso
    if (isset($_POST['azione']) && $_POST['azione'] === 'modifica') {
        $id = intval($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $campus = trim($_POST['campus'] ?? '');
        
        if ($id > 0 && !empty($nome) && !empty($campus)) {
            $result = $db->updateCdl($id, $nome, $campus);
            if ($result) {
                $messaggio = 'Corso modificato con successo!';
            } else {
                $errore = 'Errore durante la modifica del corso.';
            }
        } else {
            $errore = 'Errore durante la modifica del corso.';
        }
    }
    
    // Redirect per evitare re-submit del form
    header('Location: ' . $_SERVER['PHP_SELF'] . ($messaggio ? '?msg=' . urlencode($messaggio) : '') . ($errore ? '?err=' . urlencode($errore) : ''));
    exit;
}

// Gestione modalità modifica
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $cdl = $db->getCdlById($edit_id);
    if (!empty($cdl)) {
        $editing = $cdl[0];
    }
}

// Recupera messaggi dalla query string
if (isset($_GET['msg'])) {
    $messaggio = htmlspecialchars($_GET['msg']);
}
if (isset($_GET['err'])) {
    $errore = htmlspecialchars($_GET['err']);
}

// Recupera tutti i CDL dal database
$corsiLaurea = $db->getAllCdl();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="style.css">
    <title>StudyBo - Gestione Corsi di Laurea</title>
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
            <h3>Gestione Corsi di Laurea</h3>
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
            <h2>Corsi di Laurea presenti (<?php echo count($corsiLaurea); ?>)</h2>

            <?php if (empty($corsiLaurea)): ?>
                <div class="card">
                    <p>Nessun corso di laurea presente. Aggiungine uno!</p>
                </div>
            <?php else: ?>
                <?php foreach ($corsiLaurea as $corso): ?>
                    <div class="card <?php echo ($editing && $editing['ID'] === $corso['ID']) ? 'editing' : ''; ?>">
                        <h3><?php echo htmlspecialchars($corso['Nome']); ?></h3>
                        <p>Campus: <?php echo htmlspecialchars($corso['Campus']); ?></p>
                        <div class="card-buttons">
                            <a href="?edit=<?php echo $corso['ID']; ?>">
                                <button class="btn-edit" type="button">Modifica</button>
                            </a>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo corso?');">
                                <input type="hidden" name="azione" value="elimina">
                                <input type="hidden" name="id" value="<?php echo $corso['ID']; ?>">
                                <button class="btn-delete" type="submit">Elimina</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <aside class="card-add">
            <h2><?php echo $editing ? 'Modifica Corso di Laurea' : 'Aggiungi nuovo Corso di Laurea'; ?></h2>
            <div class="card">
                <form method="POST">
                    <input type="hidden" name="azione" value="<?php echo $editing ? 'modifica' : 'aggiungi'; ?>">
                    <?php if ($editing): ?>
                        <input type="hidden" name="id" value="<?php echo $editing['ID']; ?>">
                    <?php endif; ?>
                    
                    <label for="nome">Nome del Corso di Laurea</label>
                    <input type="text" id="nome" name="nome" 
                           placeholder="Inserisci nome CDL" 
                           value="<?php echo $editing ? htmlspecialchars($editing['Nome']) : ''; ?>" 
                           required>

                    <label for="campus">Campus</label>
                    <input type="text" id="campus" name="campus" 
                           placeholder="Inserisci campus" 
                           value="<?php echo $editing ? htmlspecialchars($editing['Campus']) : ''; ?>" 
                           required>
                    
                    <button class="<?php echo $editing ? 'btn-save' : 'btn-add'; ?>" type="submit">
                        <?php echo $editing ? 'Salva Modifiche' : 'Aggiungi Corso'; ?>
                    </button>
                    
                    <?php if ($editing): ?>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <button class="btn-cancel" type="button">Annulla</button>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </aside>
    </section>

</main>

<footer>
    <h4>Impara meglio, insieme</h4>
    <p>Tutti i diritti riservati, 2025</p>
</footer>

</body>
</html>