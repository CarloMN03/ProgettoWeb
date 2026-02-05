<?php
/*** Controlla se la pagina attuale è quella passata come parametro*/
function isActive($pagename){
    if(basename($_SERVER['PHP_SELF']) == $pagename){
        echo " class='active' ";
    }
}

 /* Trasforma una stringa in un ID pulito (minuscolo, solo lettere).*/
function getIdFromName($name){
    return preg_replace("/[^a-z]/", '', strtolower($name));
}

/** * Verifica se l'utente ha effettuato il login controllando la sessione.*/
function isUserLoggedIn(){
    return !empty($_SESSION['idautore']);
}

/**
 * Salva i dati dell'utente nella sessione dopo un login riuscito.
 * Modificata per includere il ruolo di amministratore.*/
function registerLoggedUser($user){
    $_SESSION["idautore"] = $user["idautore"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["nome"] = $user["nome"];
    // Salviamo il flag amministratore (1 = admin, 0 = utente)
    $_SESSION["amministratore"] = $user["amministratore"];
}

/**
 * Funzione per la stampa sicura di testi nell'HTML (prevenzione XSS).
 */
function e($string) {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

/**
 * Gestisce l'upload di documenti (es. PDF) sul server.
 */
function uploadDoc($path, $doc){
    $docName = basename($doc["name"]);
    $fullPath = $path.$docName;
    
    $maxKB = 5000;
    $acceptedExtensions = array("pdf");
    $result = 0;
    $msg = "";
    
    if ($doc["size"] > $maxKB * 1024) {
        $msg .= "File caricato pesa troppo! ";
    }

    $docFileType = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    if(!in_array($docFileType, $acceptedExtensions)){
        $msg .= "Accettate solo estensioni: ".implode(",", $acceptedExtensions);
    }

    if (file_exists($fullPath)) {
        $i = 1;
        do {
            $i++;
            $docName = pathinfo(basename($doc["name"]), PATHINFO_FILENAME)."_$i.".$docFileType;
        } while(file_exists($path.$docName));
        $fullPath = $path.$docName;
    }

    if(strlen($msg) == 0){
        if(!move_uploaded_file($doc["tmp_name"], $fullPath)){
            $msg .= "Errore nel caricamento della risorsa.";
        } else {
            $result = 1;
            $msg = $docName;
        }
    }
    return array($result, $msg);
}

/**
 * Gestisce l'upload di immagini (profilo o esami).
 */
function uploadImage($path, $image){
    $imageName = basename($image["name"]);
    $fullPath = $path.$imageName;
    
    $maxKB = 500;
    $acceptedExtensions = array("jpg", "jpeg", "png", "gif");
    $result = 0;
    $msg = "";

    $imageSize = getimagesize($image["tmp_name"]);
    if($imageSize === false) {
        $msg .= "File caricato non è un'immagine! ";
    }

    if ($image["size"] > $maxKB * 1024) {
        $msg .= "File caricato pesa troppo! ";
    }

    $imageFileType = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    if(!in_array($imageFileType, $acceptedExtensions)){
        $msg .= "Accettate solo estensioni: ".implode(",", $acceptedExtensions);
    }

    if (file_exists($fullPath)) {
        $i = 1;
        do {
            $i++;
            $imageName = pathinfo(basename($image["name"]), PATHINFO_FILENAME)."_$i.".$imageFileType;
        } while(file_exists($path.$imageName));
        $fullPath = $path.$imageName;
    }

    if(strlen($msg) == 0){
        if(!move_uploaded_file($image["tmp_name"], $fullPath)){
            $msg .= "Errore nel caricamento dell'immagine.";
        } else {
            $result = 1;
            $msg = $imageName;
        }
    }
    return array($result, $msg);
}

function iniziali(string $nome, string $cognome): string {
    $n = trim($nome);
    $c = trim($cognome);
    $a = $n !== '' ? mb_substr($n, 0, 1, 'UTF-8') : '';
    $b = $c !== '' ? mb_substr($c, 0, 1, 'UTF-8') : '';
    $out = strtoupper($a . $b);
    return $out !== '' ? $out : 'U';
}


?>