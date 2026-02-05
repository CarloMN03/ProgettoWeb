<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "StudyBo - Elenco Study Group";
$templateParams["nome"] = "form-sg.php";

if(isset($_GET["idcdl"]) && isset($_GET["idesame"]) && isset($_GET["luogo"]) && isset($_GET["daora"]) && isset($_GET["aora"]) && isset($_GET["idlingua"]) && isset($_GET["submit"])) {
    $templateParams["elencostudygroup"] = $dbh->getStudyGroupByCdlAndEsame($_GET["idcdl"], $_GET["idesame"], $_GET["luogo"], $_GET["daora"], $_GET["aora"], $_GET["idlingua"]);
}

$templateParams["studygroupscadenza"] = $dbh->getStGrScad();
$templateParams["numstudygr"] = $dbh->getStGrAtt();
if(isset($_SESSION["username"])){
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
    $templateParams["idcdl"] = $dbh->getUser($_SESSION["username"])[0]["idcdl"];
    $templateParams["esami"] = $dbh->getEsamiByIdCdl($templateParams["idcdl"]);
} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
    $templateParams["cdl"] = $dbh->getCdl();
}
$templateParams["js"] = array("js/studygroup.js");
$templateParams["lingua"] = $dbh->getLingua();

require 'template/base.php';
?>