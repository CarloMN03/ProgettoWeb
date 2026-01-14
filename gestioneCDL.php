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
        $nome = trim($_POST['nomecdl'] ?? '');
        $campus = trim($_POST['sede'] ?? '');
        $durata = intval($_POST['durata'] ?? 3);
        $img = '';
        
        // Gestione upload immagine
        if (isset($_FILES['img']) && $_FILES['img']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = 'img/cdl/';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $uploadResult = $db->uploadImage($uploadPath, $_FILES['img']);
                
                if ($uploadResult[0] === 1) {
                    $img = $uploadResult[1];
                } else {
                    $errore = 'Errore caricamento immagine: ' . $uploadResult[1];
                }
            } else {
                $errore = 'Errore durante il caricamento del file.';
            }
        }
        
        if (empty($errore) && !empty($nome) && !empty($campus)) {
            $result = $db->insertCdl($nome, $campus, $img, $durata);
            if ($result) {
                $messaggio = 'Corso aggiunto con successo!';
            } else {
                $errore = 'Errore durante l\'aggiunta del corso.';
            }
        } else if (empty($errore)) {
            $errore = 'Compila tutti i campi obbligatori per aggiungere un corso.';
        }
    }
    
    if (isset($_POST['azione']) && $_POST['azione'] === 'elimina') {
        $id = intval($_POST['idcdl'] ?? 0);
        $cdlInfo = $db->getCdlById($id); 
        
        if ($id > 0) {
            $result = $db->deleteCdl($id);
            if ($result) {
                if (!empty($cdlInfo[0]['img']) && file_exists('img/cdl/' . $cdlInfo[0]['img'])) {
                    unlink('img/cdl/' . $cdlInfo[0]['img']);
                }
                $messaggio = 'Corso eliminato con successo!';
            } else {
                $errore = 'Errore durante l\'eliminazione del corso. Potrebbe avere esami associati.';
            }
        }
    }
    
    if (isset($_POST['azione']) && $_POST['azione'] === 'modifica') {
        $id = intval($_POST['idcdl'] ?? 0);
        $nome = trim($_POST['nomecdl'] ?? '');
        $campus = trim($_POST['sede'] ?? '');

        $cdlCorrente = $db->getCdlById($id);
        $img = '';
        if (!empty($cdlCorrente)) {
            $img = $cdlCorrente[0]['img']; 
        }

        if (isset($_FILES['img']) && $_FILES['img']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = 'img/cdl/';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $uploadResult = $db->uploadImage($uploadPath, $_FILES['img']);

                if ($uploadResult[0] === 1) {
                    // Nuova immagine caricata con successo
                    $nuovaImmagine = $uploadResult[1];

                    // Cancella la vecchia immagine se esisteva ed era diversa
                    if (!empty($img) && file_exists($uploadPath . $img) && $img !== $nuovaImmagine) {
                        unlink($uploadPath . $img);
                    }
                    
                    // Aggiorna la variabile $img con il nuovo nome
                    $img = $nuovaImmagine; 
                } else {
                    $errore = 'Errore caricamento immagine: ' . $uploadResult[1];
                }
            } else {
                $errore = 'Errore tecnico durante l\'upload del file.';
            }
        }

        if (empty($errore) && $id > 0 && !empty($nome) && !empty($campus)) {
            
            $result = $db->updateCdl($id, $nome, $campus, $img);
            
            if ($result) {
                $messaggio = 'Corso modificato con successo!';
                $editing = null;
                header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=' . urlencode($messaggio));
                exit;
            } else {
                $errore = 'Errore durante la modifica del corso nel database.';
            }
        } else if (empty($errore)) {
            $errore = 'Dati mancanti per la modifica.';
        }
    }
    
    if (!empty($messaggio) || !empty($errore)) {
        $qs = ($messaggio ? 'msg=' . urlencode($messaggio) : '') . ($errore ? '&err=' . urlencode($errore) : '');
        if ($errore && isset($_POST['idcdl'])) {
            $qs .= '&edit=' . intval($_POST['idcdl']);
        }
        header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $qs);
        exit;
    }
}

if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $cdl = $db->getCdlById($edit_id);
    if (!empty($cdl)) {
        $editing = $cdl[0];
    }
}

if (isset($_GET['msg'])) {
    $messaggio = htmlspecialchars($_GET['msg']);
}
if (isset($_GET['err'])) {
    $errore = htmlspecialchars($_GET['err']);
}

$corsiLaurea = $db->getAllCdl();

$corsiPerCampus = [];
foreach ($corsiLaurea as $corso) {
    $campus = $corso['sede'];
    if (!isset($corsiPerCampus[$campus])) {
        $corsiPerCampus[$campus] = [];
    }
    $corsiPerCampus[$campus][] = $corso;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="css/style.css">
    <title>StudyBo - Gestione Corsi di Laurea</title>
    <style>
        .card-image {
            width: 100%;
            max-width: 200px;
            height: auto;
            margin: 10px 0;
            border-radius: 8px;
            object-fit: cover;
        }
        .card-info {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        .card-text {
            flex: 1;
        }
        .no-image {
            color: #999;
            font-style: italic;
            font-size: 0.9em;
        }
        /* Stile aggiuntivo per l'anteprima in edit */
        .current-img-preview {
            max-width: 100px;
            border: 1px solid #ddd;
            padding: 2px;
            margin-bottom: 5px;
            display: block;
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
                <?php foreach ($corsiPerCampus as $campus => $corsi): ?>
                    <h3>Campus: <?php echo htmlspecialchars($campus); ?></h3>
                    <?php foreach ($corsi as $corso): ?>
                        <div class="card <?php echo ($editing && $editing['idcdl'] === $corso['idcdl']) ? 'editing' : ''; ?>">
                            <div class="card-info">
                                <?php if (!empty($corso['img'])): ?>
                                    <img src="img/cdl/<?php echo htmlspecialchars($corso['img']); ?>" 
                                         alt="<?php echo htmlspecialchars($corso['nomecdl']); ?>" 
                                         class="card-image"
                                         onerror="this.style.display='none'">
                                <?php endif; ?>
                                
                                <div class="card-text">
                                    <h3><?php echo htmlspecialchars($corso['nomecdl']); ?></h3>
                                    <p><strong>Campus:</strong> <?php echo htmlspecialchars($corso['sede']); ?></p>
                                    <p><strong>Durata:</strong> <?php echo intval($corso['durata']); ?> anni</p>
                                    <?php if (empty($corso['img'])): ?>
                                        <p class="no-image">Nessuna immagine disponibile</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="card-buttons">
                                <a href="?edit=<?php echo $corso['idcdl']; ?>">
                                    <button class="btn-edit" type="button">Modifica</button>
                                </a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo corso? Verranno eliminati anche tutti gli esami associati!');">
                                    <input type="hidden" name="azione" value="elimina">
                                    <input type="hidden" name="idcdl" value="<?php echo $corso['idcdl']; ?>">
                                    <button class="btn-delete" type="submit">Elimina</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <aside class="card-add">
            <h2><?php echo $editing ? 'Modifica Corso di Laurea' : 'Aggiungi nuovo Corso di Laurea'; ?></h2>
            <div class="card">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="azione" value="<?php echo $editing ? 'modifica' : 'aggiungi'; ?>">
                    <?php if ($editing): ?>
                        <input type="hidden" name="idcdl" value="<?php echo $editing['idcdl']; ?>">
                    <?php endif; ?>
                    
                    <label for="nomecdl">Nome del Corso di Laurea *</label>
                    <input type="text" id="nomecdl" name="nomecdl" 
                           placeholder="Es: Laurea in Ingegneria Informatica" 
                           value="<?php echo $editing ? htmlspecialchars($editing['nomecdl']) : ''; ?>" 
                           required>

                    <label for="sede">Campus *</label>
                    <select id="sede" name="sede" required>
                        <option value="">-- Seleziona Campus --</option>
                        <option value="Cesena" <?php echo ($editing && $editing['sede'] === 'Cesena') ? 'selected' : ''; ?>>Cesena</option>
                        <option value="Bologna" <?php echo ($editing && $editing['sede'] === 'Bologna') ? 'selected' : ''; ?>>Bologna</option>
                        <option value="Forlì" <?php echo ($editing && $editing['sede'] === 'Forlì') ? 'selected' : ''; ?>>Forlì</option>
                        <option value="Ravenna" <?php echo ($editing && $editing['sede'] === 'Ravenna') ? 'selected' : ''; ?>>Ravenna</option>
                        <option value="Rimini" <?php echo ($editing && $editing['sede'] === 'Rimini') ? 'selected' : ''; ?>>Rimini</option>
                    </select>

                    <?php if (!$editing): ?>
                        <label for="durata">Durata (anni) *</label>
                        <select id="durata" name="durata" required>
                            <option value="3">3 anni (Laurea Triennale)</option>
                            <option value="2">2 anni (Laurea Magistrale)</option>
                            <option value="5">5 anni (Laurea Magistrale a Ciclo Unico)</option>
                        </select>
                    <?php endif; ?>

                    <label for="img">
                        <?php echo $editing ? 'Cambia Immagine (Opzionale)' : 'Immagine (Opzionale)'; ?>
                    </label>
                    
                    <?php if ($editing && !empty($editing['img'])): ?>
                        <div>
                            <small>Attuale:</small><br>
                            <img src="img/cdl/<?php echo htmlspecialchars($editing['img']); ?>" class="current-img-preview" alt="Anteprima">
                        </div>
                    <?php endif; ?>

                    <input type="file" id="img" name="img" accept="image/jpeg,image/png,image/gif,image/jpg">
                    <p style="font-size: 0.85em; color: var(--text-secondary); margin-top: 5px;">
                        Formati accettati: JPG, PNG, GIF (max 500KB).
                        <?php if ($editing): ?>
                            <br><strong>Nota:</strong> Se non carichi nulla, verrà mantenuta l'immagine attuale.
                        <?php endif; ?>
                    </p>
                    
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