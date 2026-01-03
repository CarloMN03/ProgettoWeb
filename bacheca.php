<?php
require_once 'bootstrap.php';

$templateParams["partecipanti"] = $dbh->getPartecipantiByUser($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $_SESSION["username"]);
$templateParams["studygroup"] = $dbh->getEsameById($_GET["idcdl"], $_GET["idesame"]);

if(isset($_SESSION["username"])){
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
}

if(empty($templateParams["partecipanti"])){
    $templateParams["titolo"] = "StudyBo - Accesso non consentito";
    $templateParams["nome"] = "accesso-negato.php";
} else {
    if(isset($_GET["idcdl"]) && isset($_GET["idesame"]) && isset($_GET["idstudygroup"]) && isset($_POST["testomsg"])){
        $templateParams["idmessaggio"] = $dbh->getLastIdMsg($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"])[0]["lastmsg"] + 1;
        if(empty($templateParams["idmessaggio"])){
        $templateParams["idmessaggio"] = 1;
        }
        $templateParams["ritorno-creamessaggio"] = $dbh->setMsg($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $templateParams["idmessaggio"], $_SESSION["username"], $_POST["testomsg"]);
    }
    if(isset($_GET["idcdl"]) && isset($_GET["idesame"]) && isset($_GET["idstudygroup"])){
        $templateParams["titolo"] = "StudyBo - Bacheca Messaggi";
        $templateParams["nome"] = "messaggi.php";
        $templateParams["messaggio"] = $dbh->getMsgsFromId($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"]);
        $templateParams["user"] = $dbh->getUser($_SESSION["username"]);
    }
}



$templateParams["studygroupscadenza"] = $dbh->getStGrScad();

require 'template/base.php';
?>