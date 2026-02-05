<?php
require_once 'bootstrap.php';

if(!isset($_SESSION["username"]) || $_SESSION["amministratore"] != 1) {
    header("Location: login.php");
    exit;
}

if(isset($_POST["azione"])) {
    if($_POST["azione"] == "approva") {
        $dbh->approvaRisorsa($_POST["idrisorsa"]);
    } elseif($_POST["azione"] == "elimina") {
        $dbh->eliminaRisorsa($_POST["idrisorsa"]);
    }
}

$templateParams["risorse_pendenti"] = $dbh->getRisorseDaApprovare();
$templateParams["titolo"] = "StudyBo Admin - Approvazione Risorse";
$templateParams["nome"] = "gestione-risorse-template.php";

require_once 'template/base.php';
?>