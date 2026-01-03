<?php
require_once 'bootstrap.php';

if(isset($_POST["tema"]) && isset($_POST["luogo"]) && isset($_POST["data"]) && isset($_POST["ora"]) && isset($_POST["lingua"])){
    $templateParams["ritorno-variazionesg"] = $dbh->updateSg($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $_POST["tema"], $_POST["luogo"], $_POST["data"], $_POST["ora"], $_POST["lingua"]);
}

if(isset($_POST["iscrivi"])){
    $templateParams["ritorno-iscrivi"] = $dbh->setPart($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $_SESSION["username"]);
}

if(isset($_SESSION["username"])){
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
}
$templateParams["studygroupscadenza"] = $dbh->getStGrScad();

if(isset($_SESSION["username"])){
    $templateParams["titolo"] = "StudyBo - Study Group";
    $templateParams["nome"] = "studygroup.php";
    $templateParams["partecipante"] = $dbh->getPartecipantiByUser($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $_SESSION["username"]);
    if(empty($templateParams["partecipante"])){
        $disiscrivi = "hidden";
        $iscrivi = "submit";
        $modifica = "style='display:none'";
    } else {
        $disiscrivi = "submit";
        $iscrivi = "style='display:none'";
        $modifica = "";
    }
    $templateParams["studygroup"] = $dbh->getEsameById($_GET["idcdl"], $_GET["idesame"]);
    $templateParams["dettaglio-sg"] = $dbh->getSgById($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"]);
    $templateParams["numPartecipanti"] = $dbh->getNumPartecipanti($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"]);
    $templateParams["partecipanti"] = $dbh->getPartecipanti($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"]);
    $templateParams["lingua"] = $dbh->getLingua();

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
                $msg = "Inserimento completato correttamente! Attendi l'autorizzazione dell'Amministratore prima di vederlo caricato nelle risorse dello Study Group.";
            } else {
                $msg = "Errore in inserimento!";
            } 
        }
    }

    if(isset($_POST["disiscrivi"])){
        $templateParams["ritorno-disiscrivi"] = $dbh->removePartSg($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $_SESSION["username"]);
        $templateParams["nome"] = "disiscrizione.php";
        $templateParams["titolo"] = "StudyBo - disiscrizione";
    }
} else {
    $templateParams["titolo"] = "StudyBo - Accesso negato";
    $templateParams["nome"] = "accesso-negato.php";
}
require 'template/base.php';
?>