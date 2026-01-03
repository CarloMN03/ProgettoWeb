<?php
require_once 'bootstrap.php';

if(isset($_SESSION["username"])){
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
}

$templateParams["titolo"] = "StudyBo - Bacheca Messaggi";
$templateParams["js"] = array("js/messaggi.js");
$templateParams["studygroupscadenza"] = $dbh->getStGrScad();

require 'template/base.php';
?>