<?php
session_start();

require_once 'db/config.php';
require_once 'db/DatabaseHelper.php';
require_once("utils/functions.php");

// Funzione globale per la sicurezza - Risolve il "Fatal Error"
if (!function_exists('e')) {
    function e($s) {
        return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// Inizializzazione Database
$dbh = new DatabaseHelper(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

define("UPLOAD_DIR", "./upload/");
?>