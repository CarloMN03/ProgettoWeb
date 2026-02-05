<?php
require_once 'bootstrap.php';
$templateParams["esame"] = $dbh->getEsameById($_GET["idcdl"], $_GET["idesame"]);

if(isset($_SESSION["username"]) && isset($_GET["idcdl"]) && isset($_GET["idesame"]) && !empty($templateParams["esame"])){
    $templateParams["titolo"] = "StudyBo - Crea Studygroup";
    $templateParams["nome"] = "crea-sg.php";
    $templateParams["lingua"] = $dbh->getLingua();
    $u = $_SESSION["username"];

    // Calcolo ID per il nuovo Study Group
    $lastSg = $dbh->getIdLastSg();
    $templateParams["idultimosg"] = (!empty($lastSg) ? $lastSg[0]["maxid"] : 0) + 1;

    // LOGICA DI CREAZIONE
    if(isset($_POST["tema"]) && isset($_POST["luogo"]) && isset($_POST["data"]) && isset($_POST["ora"])){
        $lingua = $_POST["lingua"];
        $tema = $_POST["tema"];
        $luogo = $_POST["luogo"];
        $dettaglioluogo = isset($_POST["dettaglioluogo"]) ? $_POST["dettaglioluogo"] : "";
        $data = $_POST["data"];
        $ora = $_POST["ora"];

        // 1. Creazione del gruppo
        $creazioneOk = $dbh->setSg($_GET["idcdl"], $_GET["idesame"], $templateParams["idultimosg"], $lingua, $tema, $luogo, $dettaglioluogo, $data, $ora);
        
        if($creazioneOk){
            $templateParams["ritorno-creasg"] = "Study Group creato con successo!";
            
            // 2. Adesione automatica del creatore
            $dbh->setAdesione($_GET["idcdl"], $_GET["idesame"], $templateParams["idultimosg"], $u);
            
            // 3. Gestione Preferenze e Notifiche agli altri utenti
            $templateParams["preferenze"] = $dbh->getPreferenze($_GET["idcdl"], $_GET["idesame"], $luogo, $ora, $lingua);
            
            if(!empty($templateParams["preferenze"])){
                $lastNot = $dbh->getLastIdNotificaPreferenza($_GET["idcdl"], $_GET["idesame"], $templateParams["idultimosg"]);
                $idNot = (!empty($lastNot) ? $lastNot[0]["idultnot"] : 0) + 1;
                
                // Registriamo la notifica nel sistema
                $dbh->setNotificaPreferenza($_GET["idcdl"], $_GET["idesame"], $templateParams["idultimosg"], $idNot, $u);
                
                // Inviamo la notifica a chi ha l'esame tra i preferiti
                foreach($templateParams["preferenze"] as $userpref){
                    if($userpref["username"] !== $u){ // Non inviarla a se stessi
                        $dbh->sendNotificaPreferenza($_GET["idcdl"], $_GET["idesame"], $templateParams["idultimosg"], $idNot, $userpref["username"]);
                    }
                }
                // Messaggio aggiuntivo opzionale
                $templateParams["info-notifica"] = "I colleghi interessati sono stati avvisati.";
            }
        } else {
            $templateParams["ritorno-creasg-errore"] = "Errore durante la creazione dello Study Group.";
        }
    }
    
} else {
    $templateParams["titolo"] = "StudyBo - Errore sessione";
    $templateParams["nome"] = "profilo-ko.php";
}

// Parametri header e sidebar
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