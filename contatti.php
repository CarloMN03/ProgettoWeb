<?php
require_once 'bootstrap.php';

/* 1. CONFIGURAZIONE DEI PARAMETRI PER IL TEMPLATE */
$templateParams["titolo"] = "StudyBo - Contatti";
$templateParams["nome"] = "contatti.php"; // Caricherà template/contatti.php

/* 2. RECUPERO DATI DAL DATABASE */
// Prendiamo gli amministratori per la tabella dei contatti
$templateParams["admin"] = $dbh->getAdmin(); 

// Prendiamo i gruppi in scadenza per la sidebar (gestita in base.php)
$templateParams["studygroupscadenza"] = $dbh->getStGrScad();

/* 3. GESTIONE SESSIONE E PERMESSI */
if(isset($_SESSION["username"])){
    // Se l'utente è loggato, recuperiamo il suo ruolo e il suo nome
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
} else {
    // Se è un ospite (guest), impostiamo valori di default
    $templateParams["amministratore"] = 9; // '9' identifica l'utente non loggato
    $templateParams["nomeutente"] = "";
}

require 'template/base.php';
?>