<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "StudyBo - Esplora i Gruppi di Studio";
$templateParams["nome"] = "studygroup-list-template.php";

// 2. Recuperiamo tutti i gruppi attivi dal database
if(isset($_SESSION["username"])){
    $templateParams["idcdl"] = $dbh->getUser($_SESSION["username"])[0]["idcdl"];
    $templateParams["esami"] = $dbh->getEsamiByIdCdl($templateParams["idcdl"]);
}

if(isset($_GET["idcdl"])){
    $templateParams["esami"] = $dbh->getEsamiByIdCdl($_GET["idcdl"]);
}

if(isset($_POST["idcdl"]) && isset($_POST["idesame"]) && isset($_POST["luogo"]) && isset($_POST["daora"]) && isset($_POST["aora"]) && isset($_POST["idlingua"]) && isset($_POST["submit"])) {
    $templateParams["gruppi"] = $dbh->getStudyGroupByCdlAndEsame($_POST["idcdl"], $_POST["idesame"], $_POST["luogo"], $_POST["daora"], $_POST["aora"], $_POST["idlingua"]);
} else if(isset($_GET["idcdl"])){
    $templateParams["gruppi"] = $dbh->getStudyGroupsWithDetailsByCdl($_GET["idcdl"]);
} else if(isset($_SESSION["username"])){
    $templateParams["gruppi"] = $dbh->getStudyGroupsWithDetailsByCdl($templateParams["idcdl"]);
} else {
    $templateParams["gruppi"] = $dbh->getStudyGroupsWithDetails();
}

$templateParams["cdl"] = $dbh->getCdl();
$templateParams["js"] = array("js/studygroup.js");
$templateParams["lingua"] = $dbh->getLingua();

// 3. Carichiamo la struttura base (Header, Footer, CSS)
require_once 'template/base.php';
?>