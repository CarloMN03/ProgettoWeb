<?php
// esami.php - Sistema di gestione esami

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
    
    // Aggiungi nuovo esame
    if (isset($_POST['azione']) && $_POST['azione'] === 'aggiungi') {
        $nome = trim($_POST['nome'] ?? '');
        $cdl = intval($_POST['cdl'] ?? 0);
        
        if (!empty($nome) && $cdl > 0) {
            $result = $db->insertEsame($nome, $cdl);
            if ($result) {
                $messaggio = 'Esame aggiunto con successo!';
            } else {
                $errore = 'Errore durante l\'aggiunta dell\'esame.';
            }
        } else {
            $errore = 'Compila tutti i campi per aggiungere un esame.';
        }
    }
    
    // Elimina esame
    if (isset($_POST['azione']) && $_POST['azione'] === 'elimina') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            $result = $db->deleteEsame($id);
            if ($result) {
                $messaggio = 'Esame eliminato con successo!';
            } else {
                $errore = 'Errore durante l\'eliminazione dell\'esame.';
            }
        }
    }
    
    // Modifica esame
    if (isset($_POST['azione']) && $_POST['azione'] === 'modifica') {
        $id = intval($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $cdl = intval($_POST['cdl'] ?? 0);
        
        if ($id > 0 && !empty($nome) && $cdl > 0) {
            $result = $db->updateEsame($id, $nome, $cdl);
            if ($result) {
                $messaggio = 'Esame modificato con successo!';
            } else {
                $errore = 'Errore durante la modifica dell\'esame.';
            }
        } else {
            $errore = 'Errore durante la modifica dell\'esame.';
        }
    }
    
    // Redirect per evitare re-submit del form
    header('Location: ' . $_SERVER['PHP_SELF'] . ($messaggio ? '?msg=' . urlencode($messaggio) : '') . ($errore ? '?err=' . urlencode($errore) : ''));
    exit;
}

// Gestione modalità modifica
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $esame = $db->getEsameById($edit_id);
    if (!empty($esame)) {
        $editing = $esame[0];
    }
}

// Recupera messaggi dalla query string
if (isset($_GET['msg'])) {
    $messaggio = htmlspecialchars($_GET['msg']);
}
if (isset($_GET['err'])) {
    $errore = htmlspecialchars($_GET['err']);
}

// Recupera tutti i CDL e gli esami dal database
$corsiLaurea = $db->getAllCdl();
$esami = $db->getAllEsami();

// Organizza gli esami per CDL
$esamiPerCdl = [];
foreach ($esami as $esame) {
    $idCdl = $esame['cdl'];
    if (!isset($esamiPerCdl[$idCdl])) {
        $esamiPerCdl[$idCdl] = [];
    }
    $esamiPerCdl[$idCdl][] = $esame;
}

// Crea un array associativo dei CDL per accesso rapido
$cdlMap = [];
foreach ($corsiLaurea as $cdl) {
    $cdlMap[$cdl['ID']] = $cdl;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="style.css">
    <title>StudyBo - Gestione Esami</title>
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
            <h3>Gestione Esami</h3>
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
            <h2>Esami presenti (<?php echo count($esami); ?>)</h2>

            <?php if (empty($esami)): ?>
                <div class="card">
                    <p>Nessun esame presente. Aggiungine uno!</p>
                </div>
            <?php else: ?>
                <?php foreach ($corsiLaurea as $cdl): ?>
                    <?php if (isset($esamiPerCdl[$cdl['ID']])): ?>
                        <h3><?php echo htmlspecialchars($cdl['Nome']); ?></h3>
                        <?php foreach ($esamiPerCdl[$cdl['ID']] as $esame): ?>
                            <div class="card <?php echo ($editing && $editing['ID'] === $esame['ID']) ? 'editing' : ''; ?>">
                                <h3><?php echo htmlspecialchars($esame['Nome']); ?></h3>
                                <div class="card-buttons">
                                    <a href="?edit=<?php echo $esame['ID']; ?>">
                                        <button class="btn-edit" type="button">Modifica</button>
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo esame?');">
                                        <input type="hidden" name="azione" value="elimina">
                                        <input type="hidden" name="id" value="<?php echo $esame['ID']; ?>">
                                        <button class="btn-delete" type="submit">Elimina</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <aside class="card-add">
            <h2><?php echo $editing ? 'Modifica Esame' : 'Aggiungi nuovo Esame'; ?></h2>
            <div class="card">
                <form method="POST">
                    <input type="hidden" name="azione" value="<?php echo $editing ? 'modifica' : 'aggiungi'; ?>">
                    <?php if ($editing): ?>
                        <input type="hidden" name="id" value="<?php echo $editing['ID']; ?>">
                    <?php endif; ?>
                    
                    <label for="nome">Nome dell'Esame</label>
                    <input type="text" id="nome" name="nome" 
                           placeholder="Inserisci nome esame" 
                           value="<?php echo $editing ? htmlspecialchars($editing['Nome']) : ''; ?>" 
                           required>

                    <label for="cdl">Corso di Laurea</label>
                    <select id="cdl" name="cdl" required>
                        <option value="">-- Seleziona un Corso di Laurea --</option>
                        <?php foreach ($corsiLaurea as $cdl): ?>
                            <option value="<?php echo $cdl['ID']; ?>" 
                                    <?php echo ($editing && $editing['cdl'] == $cdl['ID']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cdl['Nome']); ?> - <?php echo htmlspecialchars($cdl['Campus']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <button class="<?php echo $editing ? 'btn-save' : 'btn-add'; ?>" type="submit">
                        <?php echo $editing ? 'Salva Modifiche' : 'Aggiungi Esame'; ?>
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