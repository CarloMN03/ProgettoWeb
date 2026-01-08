<?php
require_once 'bootstrap.php';

if(isset($_POST["testomsg"])){
    $messaggi["idmessaggio"] = $dbh->getLastIdMsg($_SESSION["idcdl"], $_SESSION["idesame"], $_SESSION["idstudygroup"])[0]["lastmsg"] + 1;
    if(empty($messaggi["idmessaggio"])){
        $messaggi["idmessaggio"] = 1;
    }
    $messaggi["ritorno-creamessaggio"] = $dbh->setMsg($_SESSION["idcdl"], $_SESSION["idesame"], $_SESSION["idstudygroup"], $messaggi["idmessaggio"], $_SESSION["username"], $_POST["testomsg"]);
}

$messaggi["studygroup"] = $dbh->getEsameById($_SESSION["idcdl"], $_SESSION["idesame"]);
$messaggi["username"] = $_SESSION["username"];
$messaggi["lista"] = $dbh->getMsgsFromId($_SESSION["idcdl"], $_SESSION["idesame"], $_SESSION["idstudygroup"]);
for($i = 0; $i < count($messaggi["lista"]); $i++){
    $messaggi["lista"][$i]["imguser"] = UPLOAD_DIR.$messaggi["lista"][$i]["imguser"];
}
$messaggi["user"] = $templateParams["user"] = $dbh->getUser($_SESSION["username"]);
    $messaggi["user"][0]["imguser"] = UPLOAD_DIR.$messaggi["user"][0]["imguser"];
$messaggi["idcdl"] = $_SESSION["idcdl"];
$messaggi["idesame"] = $_SESSION["idesame"];
$messaggi["idstudygroup"] = $_SESSION["idstudygroup"];

header('Content-Type: application/json');
echo json_encode($messaggi);




?>