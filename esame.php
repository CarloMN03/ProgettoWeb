<?php
require_once 'bootstrap.php';
$templateParams["esame"] = $dbh->getEsameById($_GET["idcdl"], $_GET["idesame"]);
if(isset($_GET["idcdl"]) && isset($_GET["idesame"]) && !empty($templateParams["esame"])){
    $templateParams["cdlesame"] = $dbh->getNameCdlById($_GET["idcdl"]);
    $templateParams["titolo"] = "StudyBo - " . $templateParams["esame"][0]["nomeesame"];
    $templateParams["nome"] = "esame.php";
    $templateParams["studygrattivi"] = $dbh->getActiveStudyGroup($_GET["idcdl"], $_GET["idesame"]);
    $templateParams["lingua"] = $dbh->getLingua();
    $templateParams["partecipanti"] = $dbh->getPart($_GET["idcdl"], $_GET["idesame"]);
    $templateParams["stgresame"] = $dbh->getNumberSg($_GET["idcdl"], $_GET["idesame"]);
} else {
    $templateParams["nome"] = "erroreesame.php";
    $templateParams["titolo"] = "StudyBo - Esame non esistente";
}
$templateParams["studygroupscadenza"] = $dbh->getStGrScad();
if(isset($_SESSION["username"])){
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
}

require 'template/base.php';
?>