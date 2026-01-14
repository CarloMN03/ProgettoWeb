<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "StudyBo - Elenco Study Group";
$templateParams["nome"] = "elenco-studygroup.php";

$templateParams["studygroupscadenza"] = $dbh->getStGrScad();
$templateParams["numstudygr"] = $dbh->getStGrAtt();
if(isset($_SESSION["username"])){
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
    $templateParams["idcdl"] = $dbh->getUser($_SESSION["username"])[0]["idcdl"];
} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
    $templateParams["cdl"] = $dbh->getCdl();
}

require 'template/base.php';
?>