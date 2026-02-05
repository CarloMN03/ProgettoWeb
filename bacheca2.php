<?php
require_once 'bootstrap.php';

// Recupero parametri 
$idcdl = $_GET["idcdl"];
$idesame = $_GET["idesame"];
$idsg = $_GET["idstudygroup"];

// Info per l'header del gruppo
$info = $dbh->getSgById($idcdl, $idesame, $idsg);
$templateParams["info_gruppo"] = $info[0] ?? null;
$templateParams["studygroup"] = $dbh->getEsameById($idcdl, $idesame);

if(isset($_SESSION["username"])){
    $u = $_SESSION["username"];
    $templateParams["partecipanti"] = $dbh->getPartecipantiByUser($idcdl, $idesame, $idsg, $u);
    $templateParams["amministratore"] = $dbh->isAdmin($u)[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($u)[0]["nome"];

    if(empty($templateParams["partecipanti"])){
        $templateParams["titolo"] = "StudyBo - Accesso non consentito";
        $templateParams["nome"] = "accesso-negato.php";
    } else {
        $templateParams["titolo"] = "StudyBo - Bacheca Messaggi";
        
        //collegamento template
        $templateParams["nome"] = "messaggi.php"; 

        // recupero messaggi
        $templateParams["messaggio"] = $dbh->getMsgsFromId($idcdl, $idesame, $idsg);
    }    
} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
    $templateParams["titolo"] = "StudyBo - Accesso non consentito";
    $templateParams["nome"] = "accesso-negato.php";
}

$_SESSION["idcdl"] = $idcdl;
$_SESSION["idesame"] = $idesame;
$_SESSION["idstudygroup"] = $idsg;

$templateParams["studygroupscadenza"] = $dbh->getStGrScad();
$templateParams["js"] = array("js/messaggi.js");

require 'template/base.php';
?>