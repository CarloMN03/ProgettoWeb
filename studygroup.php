<?php
require_once 'bootstrap.php';

// 1. Protezione
if(!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// 2. Recupero parametri dall'URL
$idcdl = $_GET["idcdl"];
$idesame = $_GET["idesame"];
$idsg = $_GET["idstudygroup"];
$u = $_SESSION["username"];

// Controllo se l'utente è iscritto (fondamentale per i permessi)
$templateParams["is_iscrittosg"] = $dbh->isPartecipante($idcdl, $idesame, $idsg, $u);

// --- logica modifca ---
if(isset($_POST["tema"]) && isset($_POST["luogo"]) && isset($_POST["data"]) && isset($_POST["ora"]) && isset($_POST["lingua"])){
    $tema = trim($_POST["tema"]);
    $luogo = trim($_POST["luogo"]);
    $dettaglioluogo = isset($_POST["dettaglioluogo"]) ? trim($_POST["dettaglioluogo"]) : "";
    $data = trim($_POST["data"]);
    $ora = trim($_POST["ora"]);
    $lingua = $_POST["lingua"];
    
    // Se l'utente è iscritto, può modificare 
    if($templateParams["is_iscrittosg"]){
        $result = $dbh->updateSg($idcdl, $idesame, $idsg, $tema, $luogo, $dettaglioluogo, $data, $ora, $lingua);
        
        if($result) {
            $templateParams["ritorno-variazionesg"] = "Study Group modificato con successo!";
            
            // Gestione Notifiche
            $templateParams["maxidnotifica"] = $dbh->getLastIdNotificaSg($idcdl, $idesame, $idsg)[0]["lastnotifica"] + 1;
            if(empty($templateParams["maxidnotifica"])) $templateParams["maxidnotifica"] = 1;
            
            $dbh->setNotificaVariazione($idcdl, $idesame, $idsg, $templateParams["maxidnotifica"], $u);
            $partecipanti = $dbh->getPartecipanti($idcdl, $idesame, $idsg);
            foreach($partecipanti as $partecipante){
                $dbh->sendNotificaToPart($idcdl, $idesame, $idsg, $templateParams["maxidnotifica"], $partecipante["username"]);
            }
        } else {
            $templateParams["ritorno-variazionesg"] = "Errore nella modifica dello Study Group";
        }
    } else {
        $templateParams["ritorno-variazionesg"] = "Devi essere iscritto per modificare questo Study Group";
    }
}

// 3. Gestione invio messaggio
if(isset($_POST["testo-messaggio"]) && !empty(trim($_POST["testo-messaggio"]))) {
    $dbh->setMsg($idcdl, $idesame, $idsg, $u, $_POST["testo-messaggio"]);
    header("Location: studygroup.php?idcdl=$idcdl&idesame=$idesame&idstudygroup=$idsg");
    exit;
}

// 4. Recupero dati per la pagina
$templateParams["info_gruppo"] = $dbh->getSgById($idcdl, $idesame, $idsg)[0];
$templateParams["messaggi"] = $dbh->getMsgsFromId($idcdl, $idesame, $idsg);
$templateParams["risorse"] = $dbh->getResourceSg($idcdl, $idesame, $idsg);
$templateParams["studygroupscadenza"] = $dbh->getStGrScad();

if(isset($_SESSION["username"])){
    $templateParams["partecipanti"] = $dbh->getPartecipanti($idcdl, $idesame, $idsg);
    
    // Iscrizione
    if(isset($_POST["iscrivi"])){
        $dbh->setPart($idcdl, $idesame, $idsg, $u);
        header("Location: studygroup.php?idcdl=$idcdl&idesame=$idesame&idstudygroup=$idsg");
        exit;
    }

    $templateParams["amministratore"] = $dbh->isAdmin($u)[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($u)[0]["nome"];
    $templateParams["titolo"] = "StudyBo - Study Group";
    $templateParams["nome"] = "studygroup.php";
    $templateParams["partecipante"] = $dbh->getPartecipantiByUser($idcdl, $idesame, $idsg, $u);

    // Gestione visibilità tasti
    if(empty($templateParams["partecipante"])){
        $disiscrivi = "hidden";
        $iscrivi = "submit";
        $modifica = "style='display:none'";
    } else {
        $disiscrivi = "submit";
        $iscrivi = "style='display:none'";
        $modifica = "";
    }

    $templateParams["studygroup"] = $dbh->getEsameById($idcdl, $idesame);
    $templateParams["dettaglio-sg"] = $dbh->getSgById($idcdl, $idesame, $idsg);
    $templateParams["numPartecipanti"] = $dbh->getNumPartecipanti($idcdl, $idesame, $idsg);
    $templateParams["lingua"] = $dbh->getLingua();

    // Disiscrizione
    if(isset($_POST["disiscrivi"])){
        $dbh->removePartSg($idcdl, $idesame, $idsg, $u);
        header("Location: studygroup.php?idcdl=$idcdl&idesame=$idesame&idstudygroup=$idsg");
        exit;
    }
}
require_once 'template/base.php';
?>