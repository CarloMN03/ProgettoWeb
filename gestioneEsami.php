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
        $imgesame = '';
        
        // Gestione upload immagine
        if (isset($_FILES['imgesame']) && $_FILES['imgesame']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['imgesame']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = 'img/esami/';
                
                // Crea la cartella se non esiste
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $uploadResult = $db->uploadImage($uploadPath, $_FILES['imgesame']);
                
                if ($uploadResult[0] === 1) {
                    $imgesame = $uploadResult[1];
                } else {
                    $errore = 'Errore caricamento immagine: ' . $uploadResult[1];
                }
            } else {
                $errore = 'Errore durante il caricamento del file.';
            }
        }
        
        if (empty($errore) && !empty($nome) && $anno > 0 && $cdl > 0) {
            $result = $db->insertEsame($nome, $anno, $cdl, $imgesame);
            if ($result) {
                $messaggio = 'Esame aggiunto con successo!';
            } else {
                $errore = 'Errore durante l\'aggiunta dell\'esame.';
            }
        } else if (empty($errore)) {
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
        
        // Recupera l'immagine esistente
        $esameCorrente = $db->getEsameById($id);
        $imgesame = $esameCorrente[0]['imgesame'] ?? '';
        
        // Gestione upload nuova immagine
        if (isset($_FILES['imgesame']) && $_FILES['imgesame']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['imgesame']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = 'img/esami/';
                
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $uploadResult = $db->uploadImage($uploadPath, $_FILES['imgesame']);
                
                if ($uploadResult[0] === 1) {
                    // Elimina la vecchia immagine se esiste
                    if (!empty($imgesame) && file_exists($uploadPath . $imgesame)) {
                        unlink($uploadPath . $imgesame);
                    }
                    $imgesame = $uploadResult[1];
                } else {
                    $errore = 'Errore caricamento immagine: ' . $uploadResult[1];
                }
            }
        }
        
        if (empty($errore) && $id > 0 && !empty($nome) && $anno > 0 && $cdl > 0) {
            $result = $db->updateEsame($id, $nome, $anno, $cdl, $imgesame);
            if ($result) {
                $messaggio = 'Esame modificato con successo!';
            } else {
                $errore = 'Errore durante la modifica dell\'esame.';
            }
        } else if (empty($errore)) {
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
                                <?php if (!empty($esame['imgesame'])): ?>
                                    <div class="card-img">
                                        <img src="img/esami/<?php echo htmlspecialchars($esame['imgesame']); ?>" 
                                             alt="<?php echo htmlspecialchars($esame['nomeesame']); ?>">
                                    </div>
                                <?php endif; ?>
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
                <form method="POST" enctype="multipart/form-data">
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
                    <?php if ($editing && !empty($editing['imgesame'])): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="img/esami/<?php echo htmlspecialchars($editing['imgesame']); ?>" 
                                 alt="Immagine corrente" 
                                 style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                            <p style="font-size: 0.9em; color: var(--text-secondary);">Immagine corrente: <?php echo htmlspecialchars($editing['imgesame']); ?></p>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="imgesame" name="imgesame" accept="image/jpeg,image/png,image/gif,image/jpg">
                    <p style="font-size: 0.85em; color: var(--text-secondary); margin-top: 5px;">
                        Formati accettati: JPG, JPEG, PNG, GIF (max 500KB)
                    </p>
                    
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