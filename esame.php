<?php
require_once 'bootstrap.php';
$templateParams["esame"] = $dbh->getEsameById($_GET["idcdl"], $_GET["idesame"]);

if (isset($_SESSION["username"]) && isset($_GET["idcdl"]) && isset($_GET["idesame"]) && !empty($templateParams["esame"])) {
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];

    if (
        isset($_GET["idcdl"]) && isset($_GET["idesame"]) && !empty($templateParams["esame"]) &&
        isset($_POST["luogo"]) && isset($_POST["ora-da"]) && isset($_POST["ora-a"]) && isset($_POST["idlingua"])
    ) {
        $last = $dbh->getLastIdPreferenza($_GET["idcdl"], $_GET["idesame"], $_SESSION["username"]);
        $lastpref = (!empty($last) && isset($last[0]["lastpref"])) ? (int)$last[0]["lastpref"] : 0;
        $templateParams["maxidpreferenza"] = $lastpref + 1;

        $ok = $dbh->addPreferenza(
            $_SESSION["username"],
            $_GET["idcdl"],
            $_GET["idesame"],
            $templateParams["maxidpreferenza"],
            $_POST["luogo"],
            $_POST["ora-da"],
            $_POST["ora-a"],
            $_POST["idlingua"]
        );

        if ($ok) {
            $_SESSION["flash_msg"] = "Preferenza aggiunta!";
            header(
                "Location: esame.php?idcdl=" . urlencode((string)$_GET["idcdl"]) .
                "&idesame=" . urlencode((string)$_GET["idesame"])
            );
            exit;
        } else {
            $templateParams["flash_err"] = "Errore: preferenza non aggiunta.";
        }
    }
} else {
    $templateParams["amministratore"] = 9;
    $templateParams["nomeutente"] = "";
}

if (isset($_GET["idcdl"]) && isset($_GET["idesame"]) && !empty($templateParams["esame"])) {
    $templateParams["cdlesame"] = $dbh->getNameCdlById($_GET["idcdl"]);
    $templateParams["titolo"] = "StudyBo - " . $templateParams["esame"][0]["nomeesame"];
    $templateParams["nome"] = "esame.php";
    $templateParams["studygrattivi"] = $dbh->getActiveStudyGroup($_GET["idcdl"], $_GET["idesame"]);
    $templateParams["lingua"] = $dbh->getLingua();
    $templateParams["partecipanti"] = $dbh->getPart($_GET["idcdl"], $_GET["idesame"]);
    $templateParams["stgresame"] = $dbh->getNumberSg($_GET["idcdl"], $_GET["idesame"]);
} else {
    $templateParams["nome"] = "erroreesame.php";
    $templateParams["titolo"] = "StudyBo - Esame non esistente";
}

$templateParams["studygroupscadenza"] = $dbh->getStGrScad();

/* Flash message (una volta sola) */
if (isset($_SESSION["flash_msg"])) {
    $templateParams["flash_msg"] = $_SESSION["flash_msg"];
    unset($_SESSION["flash_msg"]);
}

require 'template/base.php';
?>
