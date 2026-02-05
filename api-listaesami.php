<?php
require_once 'bootstrap.php';

if(isset($_POST["idcdl"])){
    $esami = $dbh->getEsamiByIdCdl($_POST["idcdl"]);
}

header('Content-Type: application/json');
echo json_encode($esami);
?>