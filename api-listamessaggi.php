<?php
require_once 'bootstrap.php';

if(isset($_POST["testomsg"])){
    
$messaggi["ritorno-creamessaggio"] = $dbh->setMsg($_SESSION["idcdl"], $_SESSION["idesame"], $_SESSION["idstudygroup"], $_SESSION["username"], $_POST["testomsg"]);
}

$messaggi["studygroup"] = $dbh->getEsameById($_SESSION["idcdl"], $_SESSION["idesame"]);
$messaggi["username"] = $_SESSION["username"];
$messaggi["lista"] = $dbh->getMsgsFromId($_SESSION["idcdl"], $_SESSION["idesame"], $_SESSION["idstudygroup"]);

$messaggi["user"] = $templateParams["user"] = $dbh->getUser($_SESSION["username"]);
$messaggi["idcdl"] = $_SESSION["idcdl"];
$messaggi["idesame"] = $_SESSION["idesame"];
$messaggi["idstudygroup"] = $_SESSION["idstudygroup"];

header('Content-Type: application/json');
echo json_encode($messaggi);

?>