<?php
require_once 'db/DatabaseHelper.php';
require_once 'db/config.php';

$db = new DatabaseHelper(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

session_start();

$messaggio = '';
$errore = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['azione']) && $_POST['azione'] === 'elimina') {
        $id = intval($_POST['idstudygroup'] ?? 0);
        if ($id > 0) {
            $result = $db->deleteStudyGroup($id);
            if ($result) {
                $messaggio = 'Study Group eliminato con successo!';
            } else {
                $errore = 'Errore durante l\'eliminazione dello Study Group.';
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

$studyGroups = $db->getStudyGroupsWithDetails();

// Funzione per formattare la data
function formattaData($data) {
    $dataObj = DateTime::createFromFormat('Y-m-d', $data);
    return $dataObj ? $dataObj->format('d/m/Y') : $data;
}

// Funzione per formattare l'ora
function formattaOra($ora) {
    $oraObj = DateTime::createFromFormat('H:i:s', $ora);
    return $oraObj ? $oraObj->format('H:i') : $ora;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="css/style.css">
    <title>StudyBo - Gestione Study Group</title>
    <style>
        .sg-card {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        .sg-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }
        .sg-content {
            flex: 1;
        }
        .sg-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin: 10px 0;
        }
        .sg-info-item {
            font-size: 0.9em;
        }
        .sg-info-item strong {
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: bold;
        }
        .badge-online {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-fisico {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .badge-passato {
            background-color: #f8d7da;
            color: #721c24;
        }
        .stats-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .stats-box h3 {
            margin: 0 0 10px 0;
            color: #495057;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        .stat-item {
            background: white;
            padding: 10px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
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
            <h3>Gestione Study Group</h3>
            <h4>Dipartimento di Ingegneria e Architettura</h4>
        </div>
    </section>

    <?php if ($messaggio): ?>
        <div class="alert alert-success"><?php echo $messaggio; ?></div>
    <?php endif; ?>
    
    <?php if ($errore): ?>
        <div class="alert alert-error"><?php echo $errore; ?></div>
    <?php endif; ?>

    <div class="stats-box">
        <h3>📊 Statistiche Study Group</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number"><?php echo count($studyGroups); ?></div>
                <div class="stat-label">Totali</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo array_sum(array_column($studyGroups, 'Partecipanti')); ?></div>
                <div class="stat-label">Partecipanti Totali</div>
            </div>
        </div>
    </div>

    <section class="container">
        <div class="list">
            
            <h2>📚 Tutti gli Study Group (<?php echo count($studyGroups); ?>)</h2>
            
            <?php if (empty($studyGroups)): ?>
                <div class="card">
                    <p>Nessuno Study Group presente.</p>
                </div>
            <?php else: ?>
                <?php foreach ($studyGroups as $sg): ?>
                    <div class="card">
                        <div class="sg-card">
                            <?php 
                            // L'immagine arriva già dalla query getStudyGroupsWithDetails
                            $immagineEsame = $sg['ImgEsame'] ?? '';
                            ?>
                            <?php if (!empty($immagineEsame)): ?>
                                <img src="<?php echo htmlspecialchars($immagineEsame); ?>" 
                                     alt="<?php echo htmlspecialchars($sg['NomeEsame']); ?>" 
                                     class="sg-image"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="sg-image" style="display: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.9em; text-align: center; padding: 10px;">
                                    <?php echo htmlspecialchars(substr($sg['NomeEsame'], 0, 20)); ?>
                                </div>
                            <?php else: ?>
                                <div class="sg-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.9em; text-align: center; padding: 10px;">
                                    <?php echo htmlspecialchars(substr($sg['NomeEsame'], 0, 20)); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="sg-content">
                                <h3>
                                    <?php echo htmlspecialchars($sg['tema']); ?>
                                    <?php 
                                    $isOnline = stripos($sg['luogo'], 'online') !== false;
                                    ?>
                                    <span class="badge <?php echo $isOnline ? 'badge-online' : 'badge-fisico'; ?>">
                                        <?php echo $isOnline ? '💻 Online' : '📍 Fisico'; ?>
                                    </span>
                                </h3>
                                
                                <div class="sg-info">
                                    <div class="sg-info-item">
                                        <strong>📚 Esame:</strong> <?php echo htmlspecialchars($sg['NomeEsame']); ?>
                                    </div>
                                    <div class="sg-info-item">
                                        <strong>🎓 CDL:</strong> <?php echo htmlspecialchars($sg['NomeCdl']); ?>
                                    </div>
                                    <div class="sg-info-item">
                                        <strong>📍 Luogo:</strong> <?php echo htmlspecialchars($sg['luogo']); ?>
                                    </div>
                                    <div class="sg-info-item">
                                        <strong>📅 Data:</strong> <?php echo formattaData($sg['data']); ?>
                                    </div>
                                    <div class="sg-info-item">
                                        <strong>🕐 Ora:</strong> <?php echo formattaOra($sg['ora']); ?>
                                    </div>
                                    <div class="sg-info-item">
                                        <strong>🌍 Lingua:</strong> <?php echo htmlspecialchars($sg['NomeLingua']); ?>
                                    </div>
                                    <div class="sg-info-item">
                                        <strong>👥 Partecipanti:</strong> <?php echo intval($sg['Partecipanti']); ?>
                                    </div>
                                </div>
                                
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo Study Group?');">
                                    <input type="hidden" name="azione" value="elimina">
                                    <input type="hidden" name="idstudygroup" value="<?php echo $sg['idstudygroup']; ?>">
                                    <button class="btn-delete" type="submit">Elimina Study Group</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </section>

</main>

<footer>
    <h4>Impara meglio, insieme</h4>
    <p>Tutti i diritti riservati, 2025</p>
</footer>

</body>
</html>