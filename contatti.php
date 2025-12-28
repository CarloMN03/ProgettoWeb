<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "StudyBo - Contatti";
$templateParams["nome"] = "contatti.php";
$templateParams["admin"] = $dbh->getAdmin();;
$templateParams["studygroupscadenza"] = $dbh->getStGrScad();
if(isset($_SESSION["username"])){
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
} else {
    $templateParams["amministratore"] = "";
    $templateParams["nomeutente"] = "";
}


require 'template/base.php';
?>