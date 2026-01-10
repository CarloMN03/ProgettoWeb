<?php
require_once 'db/DatabaseHelper.php';
require_once 'db/config.php';

$db = new DatabaseHelper(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

session_start();

$messaggio = '';
$errore = '';
$editing = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['azione']) && $_POST['azione'] === 'aggiungi') {
        $nome = trim($_POST['nomeesame'] ?? '');
        $anno = intval($_POST['annoesame'] ?? 0);
        $cdl = intval($_POST['idcdl'] ?? 0);
        $imgesame = trim($_POST['imgesame'] ?? '');
        
        if (!empty($nome) && $anno > 0 && $cdl > 0) {
            $result = $db->insertEsame($nome, $anno, $cdl, $imgesame);
            if ($result) {
                $messaggio = 'Esame aggiunto con successo!';
            } else {
                $errore = 'Errore durante l\'aggiunta dell\'esame.';
            }
        } else {
            $errore = 'Compila tutti i campi per aggiungere un esame.';
        }
    }
    
    if (isset($_POST['azione']) && $_POST['azione'] === 'elimina') {
        $id = intval($_POST['idesame'] ?? 0);
        if ($id > 0) {
            $result = $db->deleteEsame($id);
            if ($result) {
                $messaggio = 'Esame eliminato con successo!';
            } else {
                $errore = 'Errore durante l\'eliminazione dell\'esame.';
            }
        }
    }
    
    if (isset($_POST['azione']) && $_POST['azione'] === 'modifica') {
        $id = intval($_POST['idesame'] ?? 0);
        $nome = trim($_POST['nomeesame'] ?? '');
        $anno = intval($_POST['annoesame'] ?? 0);
        $cdl = intval($_POST['idcdl'] ?? 0);
        $imgesame = trim($_POST['imgesame'] ?? '');
        
        if ($id > 0 && !empty($nome) && $anno > 0 && $cdl > 0) {
            $result = $db->updateEsame($id, $nome, $anno, $cdl, $imgesame);
            if ($result) {
                $messaggio = 'Esame modificato con successo!';
            } else {
                $errore = 'Errore durante la modifica dell\'esame.';
            }
        } else {
            $errore = 'Errore durante la modifica dell\'esame.';
        }
    }
 
    header('Location: ' . $_SERVER['PHP_SELF'] . ($messaggio ? '?msg=' . urlencode($messaggio) : '') . ($errore ? '?err=' . urlencode($errore) : ''));
    exit;
}

if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $esame = $db->getEsameById($edit_id);
    if (!empty($esame)) {
        $editing = $esame[0];
    }
}

if (isset($_GET['msg'])) {
    $messaggio = htmlspecialchars($_GET['msg']);
}
if (isset($_GET['err'])) {
    $errore = htmlspecialchars($_GET['err']);
}

$corsiLaurea = $db->getAllCdl();
$esami = $db->getAllEsami();

$esamiPerCdl = [];
foreach ($esami as $esame) {
    $idCdl = $esame['idcdl'];
    if (!isset($esamiPerCdl[$idCdl])) {
        $esamiPerCdl[$idCdl] = [];
    }
    $esamiPerCdl[$idCdl][] = $esame;
}

$cdlMap = [];
foreach ($corsiLaurea as $cdl) {
    $cdlMap[$cdl['idcdl']] = $cdl;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="css/style.css">
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
                    <?php if (isset($esamiPerCdl[$cdl['idcdl']])): ?>
                        <h3><?php echo htmlspecialchars($cdl['nomecdl']); ?></h3>
                        <?php foreach ($esamiPerCdl[$cdl['idcdl']] as $esame): ?>
                            <div class="card <?php echo ($editing && $editing['idesame'] === $esame['idesame']) ? 'editing' : ''; ?>">
                                <h3><?php echo htmlspecialchars($esame['nomeesame']); ?></h3>
                                <p><strong>Anno:</strong> <?php echo intval($esame['annoesame']); ?></p>
                                <div class="card-buttons">
                                    <a href="?edit=<?php echo $esame['idesame']; ?>">
                                        <button class="btn-edit" type="button">Modifica</button>
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo esame?');">
                                        <input type="hidden" name="azione" value="elimina">
                                        <input type="hidden" name="idesame" value="<?php echo $esame['idesame']; ?>">
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
                        <input type="hidden" name="idesame" value="<?php echo $editing['idesame']; ?>">
                    <?php endif; ?>
                    
                    <label for="nomeesame">Nome dell'Esame</label>
                    <input type="text" id="nomeesame" name="nomeesame" 
                           placeholder="Inserisci nome esame" 
                           value="<?php echo $editing ? htmlspecialchars($editing['nomeesame']) : ''; ?>" 
                           required>

                    <label for="annoesame">Anno di Corso</label>
                    <select id="annoesame" name="annoesame" required>
                        <option value="">-- Seleziona l'anno --</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>" 
                                    <?php echo ($editing && $editing['annoesame'] == $i) ? 'selected' : ''; ?>>
                                <?php echo $i; ?>° anno
                            </option>
                        <?php endfor; ?>
                    </select>
                    
                    <label for="idcdl">Corso di Laurea</label>
                    <select id="idcdl" name="idcdl" required>
                        <option value="">-- Seleziona un Corso di Laurea --</option>
                        <?php foreach ($corsiLaurea as $cdl): ?>
                            <option value="<?php echo $cdl['idcdl']; ?>" 
                                    <?php echo ($editing && $editing['idcdl'] == $cdl['idcdl']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cdl['nomecdl']); ?> - <?php echo htmlspecialchars($cdl['sede']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="imgesame">Immagine (opzionale)</label>
                    <input type="text" id="imgesame" name="imgesame" 
                           placeholder="Nome file immagine (es: fisica.jpg)" 
                           value="<?php echo $editing ? htmlspecialchars($editing['imgesame']) : ''; ?>">
                    
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