<?php
require_once 'bootstrap.php';

if(isset($_GET["idcdl"]) && isset($_GET["idesame"]) && isset($_GET["idstudygroup"]) && isset($_POST["testomsg"])){
    $result["idmessaggio"] = $dbh->getLastIdMsg($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"])[0]["lastmsg"] + 1;
    if(empty($result["idmessaggio"])){
        $result["idmessaggio"] = 1;
    }
    $result["ritorno-creamessaggio"] = $dbh->setMsg($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"], $result["idmessaggio"], $_SESSION["username"], $_POST["testomsg"]);
}

if(isset($_GET["idcdl"]) && isset($_GET["idesame"]) && isset($_GET["idstudygroup"])){
    $result["messaggio"] = $dbh->getMsgsFromId($_GET["idcdl"], $_GET["idesame"], $_GET["idstudygroup"]);
    $templateParams["user"] = $dbh->getUser($_SESSION["username"]);
}

header('Content-Type: application/json');
echo json_encode($result);
?>