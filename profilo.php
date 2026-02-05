<?php
require_once 'bootstrap.php';

// 1. Controllo Accesso: Se l'utente non è loggato, lo rimandiamo al login
if(!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$u = $_SESSION["username"];
$messaggio = "";
$errore = "";

// 2. LOGICA AZIONI (POST)

// AZIONE: Logout
if(isset($_GET["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// AZIONE: Cambio Dati Anagrafici e Immagine
if(isset($_POST["submit-anag"])) {
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];
    
    if(isset($_FILES["imguser"]) && $_FILES["imguser"]["error"] == 0) {
        // Usiamo la funzione di upload interna al Master Helper
        list($result, $res_msg) = $dbh->uploadImage(UPLOAD_DIR, $_FILES["imguser"]);
        if($result != 0) {
            $dbh->updateAnag($u, $nome, $cognome, $res_msg);
            $messaggio = "Profilo e immagine aggiornati!";
        } else {
            $errore = "Errore caricamento immagine: " . $res_msg;
        }
    } else {
        $dbh->updateAnag2($u, $nome, $cognome);
        $messaggio = "Dati aggiornati con successo!";
    }
}

// AZIONE: Cambio Password
if(isset($_POST["submit-pwd"])) {
    $old = $_POST["oldPwd"];
    $new = $_POST["newPwd"];
    $confirm = $_POST["reNewPwd"];

    $check = $dbh->checkUserLogin($u, $old);
    if(count($check) > 0) {
        if($new === $confirm) {
            $dbh->changePwd($u, $new);
            $messaggio = "Password aggiornata!";
        } else {
            $errore = "Le nuove password non coincidono.";
        }
    } else {
        $errore = "La vecchia password non è corretta.";
    }
}

// AZIONE: Cambio CDL
if(isset($_POST["submit-cdl"])) {
    $dbh->changeCdl($u, $_POST["newCdl"]);
    $_SESSION["idcdl"] = $_POST["newCdl"]; // Aggiorniamo anche la sessione
    $messaggio = "Corso di Laurea aggiornato!";
}

// AZIONE: Elimina Account
if(isset($_POST["submit-delete"])) {
    $check = $dbh->checkUserLogin($u, $_POST["pwd-del"]);
    if(count($check) > 0) {
        $dbh->deleteUser($u); // Usiamo la funzione di Carlo per eliminare
        session_destroy();
        header("Location: login.php?msg=Account eliminato correttamente");
        exit;
    } else {
        $errore = "Password errata. Impossibile eliminare l'account.";
    }
}

// AZIONE: Elimina Preferenza
if(isset($_POST["elimina-pref"])) {
    $dbh->deletePreferenza($_POST["idcdl"], $_POST["idesame"], $_POST["idpreferenza"], $u);
    $messaggio = "Preferenza rimossa.";
}

// AZIONE: Modifica Preferenza
if(isset($_POST["mod-pref"])) {
    $dbh->updatePreferenza($_POST["idcdl"], $_POST["idesame"], $_POST["idpreferenza"], $u, $_POST["luogo"], $_POST["ora-da"], $_POST["ora-a"], $_POST["idlingua"]);
    $messaggio = "Preferenza modificata con succcesso.";
}

// AZIONE: Aggiungi Preferenza
if(isset($_POST["submit-pref"])){
    $templateParams["cdluser"] = $dbh->getNameUser($u)[0]["idcdl"];
    $templateParams["maxidpreferenza"] = $dbh->getLastIdPreferenza($templateParams["cdluser"], $_POST["idesame"], $u)[0]["lastpref"] + 1;
    if(empty($templateParams["maxidpreferenza"])){
        $templateParams["maxidpreferenza"] = 1;
    }
    $dbh->addPreferenza($u, $templateParams["cdluser"], $_POST["idesame"], $templateParams["maxidpreferenza"],  $_POST["luogo"], $_POST["ora-da"], $_POST["ora-a"], $_POST["idlingua"]);
    $messaggio = "Preferenza aggiunta.";
}

// 3. RECUPERO DATI PER IL TEMPLATE
$user_data = $dbh->getUser($u)[0];

$templateParams["titolo"] = "StudyBo - Il mio Profilo";
$templateParams["nome"] = "profilo.php"; // Il nome del file template

// Dati anagrafici
$templateParams["nomeuser"] = $user_data["nome"];
$templateParams["cognomeuser"] = $user_data["cognome"];
$templateParams["imguser"] = $user_data["imguser"];
$templateParams["user_cdl"] = $user_data["idcdl"];

// Liste per i menu e le card
$templateParams["cdl"] = $dbh->getAllCdl();
$templateParams["studygroupiscritto"] = $dbh->getStGrUtente($u);
$templateParams["preferenza"] = $dbh->getPreferenzaUser($u);
$templateParams["esame"] = $dbh->getEsamiByIdCdl($dbh->getNameUser($u)[0]["idcdl"]);

// Messaggi di feedback
$templateParams["messaggio"] = $messaggio;
$templateParams["errore"] = $errore;

require 'template/base.php';
?>