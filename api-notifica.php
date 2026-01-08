<?php
require_once 'bootstrap.php';

$templateParams["check-amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
$templateParams["verifica-notificaris"] = $dbh->getNotificaRisToUser($_SESSION["username"], (int)!$templateParams["check-amministratore"], (int)!$templateParams["check-amministratore"]);
$templateParams["verifica-notificavarsg"] = $dbh->getNotificaVarSgToUser($_SESSION["username"]);
$templateParams["verifica-notificapreferenza"] = $dbh->getNotificaPrefToUser($_SESSION["username"]);

if(!empty($templateParams["verifica-notificaris"]) || !empty($templateParams["verifica-notificavarsg"]) || !empty($templateParams["verifica-notificapreferenza"])){
    $nuovanotifica = 1;
} else {
    $nuovanotifica = 0;
}

header('Content-Type: application/json');
echo json_encode($nuovanotifica);

?>