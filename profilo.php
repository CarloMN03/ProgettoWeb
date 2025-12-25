<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "StudyBo - Area Personale";
$templateParams["nome"] = "profilo.php";
$templateParams["cdl"] = $db->getCdl();

if(isset($_SESSION["username"]) && isset($_POST["oldPwd"])){
    $login_result = $dbh->checkPwd($_SESSION["username"], $_POST["password"]);
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
    $templateParams["erroredelete"] = $dbh->deleteAccount($_SESSION["username"], $_POST["newCdl"]);
    $login_result = $dbh->checkPwd($_SESSION["username"], $_POST["pwd"]);
    if(count($login_result)==0){
        session_unset();
        $templateParams["titolo"] = "StudyBo - Login";
        $templateParams["nome"] = "login-form.php";
        $templateParams["errorelogin"] = $templateParams["erroredelete"][0];
    }
}

require 'template/base.php';
;
?>