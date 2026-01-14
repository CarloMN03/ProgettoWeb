<?php
require_once 'bootstrap.php';

$templateParams["studygroup"] = $dbh->getEsameById($_GET["idcdl"], $_GET["idesame"]);

if(isset($_SESSION["username"])){
    $templateParams["partecipanti"] = $dbh->getPartecipantiByUser($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $_SESSION["username"]);
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
    if(empty($templateParams["partecipanti"])){
        $templateParams["titolo"] = "StudyBo - Accesso non consentito";
        $templateParams["nome"] = "accesso-negato.php";
    } else {
        $templateParams["titolo"] = "StudyBo - Bacheca Messaggi";   
    }    
} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
    $templateParams["titolo"] = "StudyBo - Accesso non consentito";
    $templateParams["nome"] = "accesso-negato.php";
}

$_SESSION["idcdl"] = $_GET["idcdl"];
$_SESSION["idesame"] = $_GET["idesame"];
$_SESSION["idstudygroup"] = $_GET["idstudygroup"];

$templateParams["studygroupscadenza"] = $dbh->getStGrScad();
$templateParams["js"] = array("js/messaggi.js");

require 'template/base.php';
?>