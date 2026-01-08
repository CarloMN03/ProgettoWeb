<?php
require_once 'bootstrap.php';

$templateParams["partecipanti"] = $dbh->getPartecipantiByUser($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $_SESSION["username"]);
$templateParams["studygroup"] = $dbh->getEsameById($_GET["idcdl"], $_GET["idesame"]);

if(isset($_SESSION["username"])){
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
}

if(empty($templateParams["partecipanti"])){
    $templateParams["titolo"] = "StudyBo - Accesso non consentito";
    $templateParams["nome"] = "accesso-negato.php";
} else {
    if(isset($_GET["idcdl"]) && isset($_GET["idesame"]) && isset($_GET["idstudygroup"])){
        $templateParams["titolo"] = "StudyBo - Risorse Study Group";
        $templateParams["nome"] = "form-risorse.php";
        if(isset($_POST["private"]) && isset($_POST["nomeris"]) && isset($_FILES["risorsa"])){
            list($result, $msg) = uploadDoc(UPLOAD_DIR, $_FILES["risorsa"]);
            $templateParams["maxidrisorsa"] = $dbh->getLastIdRisorsa($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"])[0]["lastrisorsa"] + 1;
            if(empty($templateParams["maxidrisorsa"])){
                $templateParams["maxidrisorsa"] = 1;
            }
            if($result != 0){
                $risorsa = $msg;
                $id = $dbh->insertResource($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $_POST["private"], $_POST["nomeris"], $_SESSION["username"], $risorsa, $templateParams["maxidrisorsa"]);
                if($id!=false){
                    $templateParams["idnotifica"] = $dbh->getLastIdNotifica($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $templateParams["maxidrisorsa"])[0]["lastnotifica"] + 1;
                    if(empty($templateParams["idnotifica"])){
                        $templateParams["idnotifica"] = 1;
                    }
                    $notifica = $dbh->notificaRis($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $templateParams["maxidrisorsa"], $templateParams["idnotifica"]);
                    $templateParams["amministratori"] = $dbh->getAdmin();
                    foreach($templateParams["amministratori"] as $amministratore){
                        $adminnotifica = $dbh->sendNotificaToAdmin($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $templateParams["maxidrisorsa"], $templateParams["idnotifica"], $amministratore["username"]);
                    }
                    
                    $msg = "Inserimento completato correttamente! Attendi l'autorizzazione dell'Amministratore prima di vederlo caricato nelle risorse dello Study Group.";
                } else {
                    $msg = "Errore in inserimento!";
                }
            }
        }
    }
    if(isset($_POST["rimuovi-ris"])){
        $templateParams["ritorno-rimuovi-ris"] = $dbh->removeRisorsa($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $_POST["idrisorsa"]);
    }
    $templateParams["risorsa"] = $dbh->getResourceSg($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"]);
    if(empty($templateParams["risorsa"])){
        $tabella="style='display:none'";
    } else {
        $tabella="";
    }
}

$templateParams["studygroupscadenza"] = $dbh->getStGrScad();

require 'template/base.php';
?>