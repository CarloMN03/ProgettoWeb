<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "StudyBo - Elenco Corsi di Laurea";
$templateParams["nome"] = "elenco-cdl.php";
$templateParams["cdl"] = $dbh->getCdl();
$templateParams["esami"] = $dbh->getEsami();
$templateParams["studygroupscadenza"] = $dbh->getStGrScad();
$templateParams["numstudygr"] = $dbh->getStGrAtt();
if(isset($_SESSION["username"])){
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
}

require 'template/base.php';
?>