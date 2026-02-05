<?php
require_once 'bootstrap.php';

if(isset($_SESSION["username"])){
    if(isset($_POST["autorizza"]) && isset($_POST["note"]) && isset($_POST["cdl"]) && isset($_POST["esame"]) && isset($_POST["studygroup"]) && isset($_POST["risorsa"]) && isset($_POST["notifica"]) && isset($_POST["submitris"])){
        $templateParams["aggiorna-notifica"] = $dbh->updateNotificaRis($_POST["autorizza"], $_POST["cdl"], $_POST["esame"], $_POST["studygroup"], $_POST["risorsa"], $_POST["notifica"], $_POST["note"]);
        $templateParams["aggiorna-destnotifica"] = $dbh->updateDestNotificaRis($_POST["cdl"], $_POST["esame"], $_POST["studygroup"], $_POST["risorsa"], $_POST["notifica"], $_SESSION["username"]);
        $templateParams["risposta-notificaris"] = $dbh->rispostaNotificaRis($_POST["cdl"], $_POST["esame"], $_POST["studygroup"], $_POST["risorsa"], $_POST["notifica"], $_POST["mittente"]);
        if($_POST["autorizza"] == 1){
            $templateParams["aggiorna-risorsa"] = $dbh->updateRisorsa($_POST["cdl"], $_POST["esame"], $_POST["studygroup"], $_POST["risorsa"]);
        }
    } else if(isset($_POST["cdl"]) && isset($_POST["esame"]) && isset($_POST["studygroup"]) && isset($_POST["risorsa"]) && isset($_POST["notifica"]) && isset($_POST["submitris"])){
        $templateParams["aggiorna-destnotifica"] = $dbh->updateDestNotificaRis($_POST["cdl"], $_POST["esame"], $_POST["studygroup"], $_POST["risorsa"], $_POST["notifica"], $_SESSION["username"]);
    } else if(isset($_POST["cdl"]) && isset($_POST["esame"]) && isset($_POST["studygroup"]) && isset($_POST["notifica"]) && isset($_POST["submitvarsg"])){
        $templateParams["aggiorna-destnotificavarsg"] = $dbh->updateDestNotificaVarSg($_POST["cdl"], $_POST["esame"], $_POST["studygroup"], $_POST["notifica"], $_SESSION["username"]);
    } else if(isset($_POST["cdl"]) && isset($_POST["esame"]) && isset($_POST["studygroup"]) && isset($_POST["notifica"]) && isset($_POST["submitpref"])){
        $templateParams["aggiorna-destnotificapref"] = $dbh->updateDestNotificaPref($_POST["cdl"], $_POST["esame"], $_POST["studygroup"], $_POST["notifica"], $_SESSION["username"]);
    }
    
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
    $templateParams["notificaris"] = $dbh->getNotificaRisToUser($_SESSION["username"], (int)!$templateParams["amministratore"], (int)!$templateParams["amministratore"]);
    $templateParams["titolo"] = "StudyBo - Notifiche";
    $templateParams["nome"] = "form-notifica.php";
    $templateParams["notificavarsg"] = $dbh->getNotificaVarSgToUser($_SESSION["username"]);
    $templateParams["notificapreferenza"] = $dbh->getNotificaPrefToUser($_SESSION["username"]);

} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
    $templateParams["titolo"] = "StudyBo - Accesso negato";
    $templateParams["nome"] = "accesso-negato.php";
}

$templateParams["studygroupscadenza"] = $dbh->getStGrScad();

require 'template/base.php';
?>