<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "StudyBo - Elenco Corsi di Laurea";
$templateParams["nome"] = "elenco-cdl.php";
$templateParams["cdl"] = $dbh->getCdl();

require 'template/base.php';
?>