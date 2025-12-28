<?php
require_once 'bootstrap.php';

//da eliminare quando viene fatta la pagina login

$_SESSION["username"] = "samanta.rossi";

if(isset($_SESSION["username"])){
    $templateParams["nome"] = "profilo.php";
    $templateParams["nomeuser"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
    $templateParams["cdl"] = $dbh->getCdl();
    $templateParams["studygroupiscritto"] = $dbh->getStGrUtente($_SESSION["username"]);

    if(isset($_SESSION["username"]) && isset($_POST["oldPwd"])){
        $login_result = $dbh->checkPwd($_SESSION["username"], $_POST["oldPwd"]);
        if(count($login_result)==0){
            $templateParams["errorepassword"] = "Password errata!";
        }
        else{
            if(isset($_POST["newPwd"]) && isset($_POST["reNewPwd"])){
                $changepwd_result = $dbh->changePwd($_SESSION["username"], $_POST["newPwd"]);
            $templateParams["errorepassword"] = $changepwd_result;
            };
        }
    }

    if(isset($_POST["newCdl"])) {
        $templateParams["errorecdl"] = $dbh->changeCdl($_SESSION["username"], $_POST["newCdl"]);
    }

    if(isset($_POST["pwd"])) {
        if($_POST["pwd"] == $dbh->getPwd($_SESSION["username"])[0]["password"]) {
            $templateParams["erroredelete"] = $dbh->deleteAccount($_SESSION["username"], $_POST["pwd"]);
            $login_result = $dbh->checkPwd($_SESSION["username"], $_POST["pwd"]);
            if(count($login_result)==0){
                session_unset();
                $templateParams["titolo"] = "StudyBo - Cancellazione Account";
                $templateParams["nome"] = "account-eliminato.php";
            }
        } else {
            $templateParams["erroredelete"] = "La password inserita non è corretta";
        }
    }

} else {
    $templateParams["nome"] = "profilo-ko.php";
}

$templateParams["titolo"] = "StudyBo - Area Personale";
$templateParams["studygroupscadenza"] = $dbh->getStGrScad();
if(isset($_SESSION["username"])){
    $templateParams["amministratore"] = $dbh->isAdmin($_SESSION["username"])[0]["amministratore"];
    $templateParams["nomeutente"] = $dbh->getNameUser($_SESSION["username"])[0]["nome"];
} else {
    $templateParams["amministratore"] = "";
    $templateParams["nomeutente"] = "";
}

require 'template/base.php';
;
?>