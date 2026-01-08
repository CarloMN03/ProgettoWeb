<?php
require_once 'db/DatabaseHelper.php';
require_once 'db/config.php';

$db = new DatabaseHelper(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

session_start();

$messaggio = '';
$errore = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['azione']) && $_POST['azione'] === 'elimina') {
        $id = intval($_POST['id'] ?? 0);
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

function formattaData($data) {
    $dataObj = DateTime::createFromFormat('Y-m-d', $data);
    return $dataObj ? $dataObj->format('d/m/Y') : $data;
}

$immaginiEsami = [
    'Programmazione ad oggetti' => 'po.png',
    'Chimica' => 'chimica.png',
    'Base di dati' => 'database.png',
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="css/style.css">
    <title>StudyBo - Gestione Study Group</title>
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

    <section class="container">
        <div class="list">
            <h2>Study Group (<?php echo count($studyGroups); ?>)</h2>

            <?php if (empty($studyGroups)): ?>
                <div class="card">
                    <p>Nessuno Study Group presente.</p>
                </div>
            <?php else: ?>
                <?php foreach ($studyGroups as $sg): ?>
                    <div class="card">
                        <?php 
                        $nomeEsame = $sg['NomeEsame'];
                        $immagine = $immaginiEsami[$nomeEsame] ?? 'default.png';
                        ?>
                        <div class="card-img">
                            <img src="<?php echo htmlspecialchars($immagine); ?>" alt="<?php echo htmlspecialchars($nomeEsame); ?>">
                        </div>
                        <h3><?php echo htmlspecialchars($sg['NomeEsame']); ?></h3>
                        <p><strong>Tema:</strong> <?php echo htmlspecialchars($sg['Tema']); ?></p>
                        <p><strong>Luogo:</strong> <?php echo htmlspecialchars($sg['Luogo']); ?></p>
                        <p><strong>Data:</strong> <?php echo formattaData($sg['Data']); ?></p>
                        <p><strong>Ora:</strong> <?php echo htmlspecialchars($sg['Ora']); ?></p>
                        <p><strong>Lingua:</strong> <?php echo htmlspecialchars($sg['NomeLingua']); ?></p>
                        <p><strong>Partecipanti:</strong> <?php echo intval($sg['Partecipanti']); ?></p>
                        <p><strong>CDL:</strong> <?php echo htmlspecialchars($sg['NomeCdl']); ?></p>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo Study Group?');">
                            <input type="hidden" name="azione" value="elimina">
                            <input type="hidden" name="id" value="<?php echo $sg['ID']; ?>">
                            <button class="btn-delete" type="submit">Elimina</button>
                        </form>
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