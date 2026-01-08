<?php
require_once 'bootstrap.php';
$templateParams["esame"] = $dbh->getEsameById($_GET["idcdl"], $_GET["idesame"]);

if(isset($_SESSION["username"]) && isset($_GET["idcdl"]) && isset($_GET["idesame"]) && !empty($templateParams["esame"])){
    $templateParams["titolo"] = "StudyBo - Crea Studygroup";
    $templateParams["nome"] = "crea-sg.php";
    $templateParams["lingua"] = $dbh->getLingua();
    $templateParams["idultimosg"] = $dbh->getIdLastSg($_GET["idcdl"], $_GET["idesame"])[0]["maxid"] + 1;
    if(empty($templateParams["idultimosg"])){
        $templateParams["idultimosg"] = 1;
    }
    if(isset($_GET["idcdl"]) && isset($_GET["idesame"]) && isset($_POST["lingua"]) && isset($_POST["tema"]) && isset($_POST["luogo"]) && isset($_POST["dettaglioluogo"])&& isset($_POST["data"]) && isset($_POST["ora"]) && isset($_SESSION["username"])){
        $templateParams["ritorno-creasg"] = $dbh->setSg($_GET["idcdl"], $_GET["idesame"], $templateParams["idultimosg"], $_POST["lingua"], $_POST["tema"], $_POST["luogo"], $_POST["dettaglioluogo"], $_POST["data"], $_POST["ora"]);
        $templateParams["ritorno-adesione"] = $dbh->setAdesione($_GET["idcdl"], $_GET["idesame"], $templateParams["idultimosg"], $_SESSION["username"]);
        $templateParams["preferenze"] = $dbh->getPreferenze($_GET["idcdl"], $_GET["idesame"], $_POST["luogo"], $_POST["ora"], $_POST["lingua"]);
        if(!empty($templateParams["preferenze"])){
            $templateParams["idultimanot"] = $dbh->getLastIdNotificaPreferenza($_GET["idcdl"], $_GET["idesame"], $templateParams["idultimosg"])[0]["idultnot"] + 1;
                if(empty($templateParams["idultimanot"])){
            $templateParams["idultimanot"] = 1;
            }
            $templateParams["notifica-pref"] = $dbh->setNotificaPreferenza($_GET["idcdl"], $_GET["idesame"], $templateParams["idultimosg"], $templateParams["idultimanot"], $_SESSION["username"]);
            foreach($templateParams["preferenze"] as $userpref){
                $templateParams["invio-not"][$userpref["username"]] = $dbh->sendNotificaPreferenza($_GET["idcdl"], $_GET["idesame"], $templateParams["idultimosg"], $templateParams["idultimanot"], $userpref["username"]);
            }
        
        }
    }
    
} else {
    $templateParams["titolo"] = "StudyBo - Errore login";
    $templateParams["nome"] = "profilo-ko.php";
}
if(isset($_SESSION["username"])){
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
}
$templateParams["studygroupscadenza"] = $dbh->getStGrScad();


require 'template/base.php';
?>