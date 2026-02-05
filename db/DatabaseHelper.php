<?php
class DatabaseHelper {
    private $db;

    //Connessione al database MySQL
    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connessione fallita: " . $this->db->connect_error);
        }        
    }

    /*******************NUOVA QUERY ******************************** */
    public function isPartecipante($idcdl, $idesame, $idstudygroup, $username) {
    $query = "
        SELECT 1
        FROM adesione
        WHERE idcdl = ?
          AND idesame = ?
          AND idstudygroup = ?
          AND username = ?
        LIMIT 1
    ";

    $stmt = $this->db->prepare($query);
    $stmt->bind_param("ssss", $idcdl, $idesame, $idstudygroup, $username);
    $stmt->execute();
    $res = $stmt->get_result();

    return $res && $res->num_rows > 0;
    }    

    public function getCreatoreStudyGroup($cdl, $esame, $sg){
    $query = "SELECT amministratoresg
              FROM studygroup
              WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param('iii', $cdl, $esame, $sg);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}












    /* =========================================================================
       SEZIONE 1: UTENTI E AUTENTICAZIONE
       ========================================================================= */

     public function getUserPassword($u){
        $stmt = $this->db->prepare('SELECT password FROM user WHERE username = ?');
        $stmt->bind_param('s',$u);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserName(){
        $stmt = $this->db->prepare('SELECT username FROM user WHERE username = ?');
        $stmt->bind_param('s',$u);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    }

    public function checkUserLogin($username, $password){
        $stmt = $this->db->prepare("SELECT * FROM user WHERE username = ? AND attivo = 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Verifica la password con password_verify
        if (!empty($result) && password_verify($password, $result[0]['password'])) {
            return $result;
        }
        return [];
    }

    public function checkAdminLogin($u, $p){
        $stmt = $this->db->prepare("SELECT * FROM user WHERE username = ? AND password = ? AND amministratore = '1' AND attivo = '1'");
        $stmt->bind_param('ss', $u, $p);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Recupera tutti gli amministratori per la pagina contatti
    public function getAdmin(){
        $query = "SELECT * FROM user WHERE amministratore = 1 AND attivo = 1";
        return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function isAdmin($u){
        $stmt = $this->db->prepare("SELECT amministratore FROM user WHERE username = ?");
        $stmt->bind_param('s', $u);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUser($u){
        $stmt = $this->db->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param('s', $u);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getNameUser($u){ return $this->getUser($u); }

    public function insertUser($n, $c, $u, $p, $idcdl = NULL){
        $stmt = $this->db->prepare("INSERT INTO user (username, cognome, nome, password, attivo, amministratore, imguser, idcdl) VALUES (?, ?, ?, ?, '1', '0', '', ?)");
        $stmt->bind_param('ssssi', $u, $c, $n, $p, $idcdl);
        return $stmt->execute();
    }

    public function updateAnag($u, $n, $c, $f){
        $stmt = $this->db->prepare("UPDATE user SET nome = ?, cognome = ?, imguser = ? WHERE username = ?");
        $stmt->bind_param('ssss', $n, $c, $f, $u);
        return $stmt->execute();
    }

    public function updateAnag2($u, $n, $c){
        $stmt = $this->db->prepare("UPDATE user SET nome = ?, cognome = ? WHERE username = ?");
        $stmt->bind_param('sss', $n, $c, $u);
        return $stmt->execute();
    }

    public function changePwd($u, $p){
        $hashedPwd = password_hash($p, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE user SET password = ? WHERE username = ?");
        $stmt->bind_param('ss', $hashedPwd, $u);
        return $stmt->execute();
    }

    public function changeCdl($u, $c){
        $stmt = $this->db->prepare("UPDATE user SET idcdl = ? WHERE username = ?");
        $stmt->bind_param('is', $c, $u);
        return $stmt->execute();
    }

    public function getAdminUsers(){ return $this->db->query("SELECT * FROM user WHERE amministratore = '1'")->fetch_all(MYSQLI_ASSOC); }
    public function getNormalUsers(){ return $this->db->query("SELECT * FROM user WHERE amministratore = '0' AND attivo = '1'")->fetch_all(MYSQLI_ASSOC); }
    public function getBlockedUsers(){ return $this->db->query("SELECT * FROM user WHERE attivo = '0'")->fetch_all(MYSQLI_ASSOC); }
    
    public function blockUser($u){ $stmt = $this->db->prepare("UPDATE user SET attivo='0' WHERE username=?"); $stmt->bind_param('s',$u); return $stmt->execute(); }
    public function unblockUser($u){ $stmt = $this->db->prepare("UPDATE user SET attivo='1' WHERE username=?"); $stmt->bind_param('s',$u); return $stmt->execute(); }
    public function makeUserAdmin($u){ $stmt = $this->db->prepare("UPDATE user SET amministratore='1' WHERE username=?"); $stmt->bind_param('s',$u); return $stmt->execute(); }
    public function deleteUser(string $u): bool {
    $this->db->begin_transaction();

    try {
        // elimina righe collegate (child)
        $stmt = $this->db->prepare("DELETE FROM preferenza WHERE username=?");
        $stmt->bind_param('s', $u);
        $stmt->execute();
        $stmt->close();

        // 2) elimina utente (parent)
        $stmt = $this->db->prepare("DELETE FROM `user` WHERE username=?");
        $stmt->bind_param('s', $u);
        $stmt->execute();
        $stmt->close();

        $this->db->commit();
        return true;

    } catch (mysqli_sql_exception $e) {
        $this->db->rollback();
        throw $e; 
    }
}

    /* =========================================================================
       SEZIONE 2: CDL ED ESAMI 
       ========================================================================= */

    public function getAllCdl(){ return $this->db->query("SELECT * FROM cdl ORDER BY nomecdl")->fetch_all(MYSQLI_ASSOC); }
    
    public function getCdlById($id){
        $stmt = $this->db->prepare("SELECT * FROM cdl WHERE idcdl = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLastIdCdl(){
        $query = "SELECT MAX(idcdl) lastidcdl FROM cdl";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertCdl($id, $n, $s, $i, $d){
        $stmt = $this->db->prepare("INSERT INTO cdl (idcdl, nomecdl, sede, img, durata) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('isssi', $id, $n, $s, $i, $d);
        return $stmt->execute();
    }

    public function updateCdl($id, $n, $s, $i){
        $stmt = $this->db->prepare("UPDATE cdl SET nomecdl=?, sede=?, img=? WHERE idcdl=?");
        $stmt->bind_param('sssi', $n, $s, $i, $id);
        return $stmt->execute();
    }

    public function deleteCdl($id){
        $stmtA1 = $this->db->prepare("DELETE FROM adattocdl WHERE idcdl = ?");
        $stmtA1->bind_param('i', $id);
        $stmtA1->execute();
        $stmtA2 = $this->db->prepare("DELETE FROM cosasistudia WHERE idcdl = ?");
        $stmtA2->bind_param('i', $id);
        $stmtA2->execute();
        $stmtA3 = $this->db->prepare("DELETE FROM sbocchicdl WHERE idcdl = ?");
        $stmtA3->bind_param('i', $id);
        $stmtA3->execute();
        $stmt = $this->db->prepare("DELETE FROM cdl WHERE idcdl = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getEsamiByIdCdl($id){
        $stmt = $this->db->prepare("SELECT * FROM esame WHERE idcdl = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getEsamiCdl($id){
        $stmt = $this->db->prepare("SELECT idesame FROM esame WHERE idcdl = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getEsameById($idcdl, $idesame = null){
        if($idesame === null){
            $stmt = $this->db->prepare("SELECT * FROM esame WHERE idesame = ?");
            $stmt->bind_param('i', $idcdl);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM esame WHERE idcdl = ? AND idesame = ?");
            $stmt->bind_param('ii', $idcdl, $idesame);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertEsame($nome, $anno, $idCdl, $imgesame){
        $stmtMax = $this->db->prepare("SELECT COALESCE(MAX(idesame), 0) + 1 as nextid FROM esame");
        $stmtMax->execute();
        $resultMax = $stmtMax->get_result();
        $rowMax = $resultMax->fetch_assoc();
        $idesame = $rowMax['nextid'];

        $query = "INSERT INTO esame (idcdl, idesame, nomeesame, annoesame, imgesame) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iisis', $idCdl, $idesame, $nome, $anno, $imgesame);
        $stmt->execute();
        return $idesame;
    }

    public function updateEsame($id, $nome, $anno, $idCdl, $imgesame){
        $query = "UPDATE esame SET nomeesame = ?, annoesame = ?, idcdl = ?, imgesame = ? WHERE idesame = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('siisi', $nome, $anno, $idCdl, $imgesame, $id);
        return $stmt->execute();
    }

    public function getStudyGroup($id){
        $stmt = $this->db->prepare("SELECT idstudygroup FROM studygroup WHERE idesame = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteEsame($id){
        $stmtA = $this->db->prepare("DELETE FROM argomento WHERE idesame = ?");
        $stmtA->bind_param('i', $id);
        $stmtA->execute();
        $stmtP = $this->db->prepare("DELETE FROM preferenza WHERE idesame = ?");
        $stmtP->bind_param('i', $id);
        $stmtP->execute();
        $query = "DELETE FROM esame WHERE idesame = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function removeUserAdmin($id){
        $query = "UPDATE user SET amministratore = '0' WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }

    public function updateDescCdl($idcdl, $desc){
        $query = "UPDATE cdl SET descrizionecdl = ? WHERE idcdl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $desc, $idcdl);
        return $stmt->execute();
    }

    public function getStudi($idcdl){
        $stmt = $this->db->prepare("SELECT * FROM cosasistudia WHERE idcdl = ?");
        $stmt->bind_param('i', $idcdl);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateCosaSiStudia($idcdl, $idstudio, $desc){
        $query = "UPDATE cosasistudia SET descrizionestudia = ? WHERE idcdl = ? AND idstudia = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sii', $desc, $idcdl, $idstudio);
        return $stmt->execute();
    }

    public function getLastIdStudia($idcdl){
        $query = "SELECT MAX(idstudia) lastidstudia FROM cosasistudia WHERE idcdl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idcdl);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addCosaSiStudia($idcdl, $idstudia, $desc){
        $stmt = $this->db->prepare("INSERT INTO cosasistudia (idcdl, idstudia, descrizionestudia) VALUES (?, ?, ?)");
        $stmt->bind_param('iis', $idcdl, $idstudia, $desc);
        return $stmt->execute();
    }

    public function removeCosaSiStudia($idcdl, $idstudia){
        $query = "DELETE FROM cosasistudia WHERE idcdl = ? AND idstudia = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idcdl, $idstudia);
        return $stmt->execute();
    }

    public function getAdatto($idcdl){
        $stmt = $this->db->prepare("SELECT * FROM adattocdl WHERE idcdl = ?");
        $stmt->bind_param('i', $idcdl);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateAdatto($idcdl, $idadatto, $desc){
        $query = "UPDATE adattocdl SET descrizioneadatto = ? WHERE idcdl = ? AND idadatto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sii', $desc, $idcdl, $idadatto);
        return $stmt->execute();
    }

    public function getLastIdAdatto($idcdl){
        $query = "SELECT MAX(idadatto) lastidadatto FROM adattocdl WHERE idcdl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idcdl);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addAdatto($idcdl, $idadatto, $desc){
        $stmt = $this->db->prepare("INSERT INTO adattocdl (idcdl, idadatto, descrizioneadatto) VALUES (?, ?, ?)");
        $stmt->bind_param('iis', $idcdl, $idadatto, $desc);
        return $stmt->execute();
    }

    public function removeAdatto($idcdl, $idadatto){
        $query = "DELETE FROM adattocdl WHERE idcdl = ? AND idadatto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idcdl, $idadatto);
        return $stmt->execute();
    }

    public function getSbocchi($idcdl){
        $stmt = $this->db->prepare("SELECT * FROM sbocchicdl WHERE idcdl = ?");
        $stmt->bind_param('i', $idcdl);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateSbocchi($idcdl, $idsbocchi, $desc){
        $query = "UPDATE sbocchicdl SET descrizionesbocchi = ? WHERE idcdl = ? AND idsbocchi = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sii', $desc, $idcdl, $idsbocchi);
        return $stmt->execute();
    }

    public function getLastIdSbocchi($idcdl){
        $query = "SELECT MAX(idsbocchi) lastidsbocchi FROM sbocchicdl WHERE idcdl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idcdl);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addSbocchi($idcdl, $idsbocchi, $desc){
        $stmt = $this->db->prepare("INSERT INTO sbocchicdl (idcdl, idsbocchi, descrizionesbocchi) VALUES (?, ?, ?)");
        $stmt->bind_param('iis', $idcdl, $idsbocchi, $desc);
        return $stmt->execute();
    }

    public function removeSbocchi($idcdl, $idsbocchi){
        $query = "DELETE FROM sbocchicdl WHERE idcdl = ? AND idsbocchi = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idcdl, $idsbocchi);
        return $stmt->execute();
    }

    public function getMaterie($idcdl){
        $stmt = $this->db->prepare("SELECT * FROM esame WHERE idcdl = ? AND principale = 1");
        $stmt->bind_param('i', $idcdl);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getArgomenti($idcdl){
        $stmt = $this->db->prepare("SELECT * FROM argomento WHERE idcdl = ?");
        $stmt->bind_param('i', $idcdl);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function removeEsame($idcdl, $idesame){
        $query = "UPDATE esame SET principale = 0 WHERE idcdl = ? AND idesame = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idcdl, $idesame);
        return $stmt->execute();
    }

    public function addEsamePrincipale($idcdl, $idesame){
        $query = "UPDATE esame SET principale = 1 WHERE idcdl = ? AND idesame = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idcdl, $idesame);
        return $stmt->execute();
    }

    public function updateArgomento($idcdl, $idesame, $idargomento, $desc){
        $query = "UPDATE argomento SET descrizioneargomento = ? WHERE idcdl = ? AND idesame = ? AND idargomento = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('siii', $desc, $idcdl, $idesame, $idargomento);
        return $stmt->execute();
    }

    public function getLastIdArgomento($idcdl, $idesame){
        $query = "SELECT MAX(idargomento) lastidargomento FROM argomento WHERE idcdl = ? AND idesame = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idcdl, $idesame);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addArgomento($idcdl, $idesame, $idargomento, $desc){
        $stmt = $this->db->prepare("INSERT INTO argomento (idcdl, idesame, idargomento, descrizioneargomento) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiis', $idcdl, $idesame, $idargomento, $desc);
        return $stmt->execute();
    }

    public function removeArgomento($idcdl, $idesame, $idargomento){
        $query = "DELETE FROM argomento WHERE idcdl = ? AND idesame = ? AND idargomento = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iii', $idcdl, $idesame, $idargomento);
        return $stmt->execute();
    }

    /* =========================================================================
       SEZIONE 3: STUDY GROUP
       ========================================================================= */
    public function getLastIdRisorsa($cdl, $esame, $sg){
        $query = "SELECT MAX(idrisorsa) lastrisorsa FROM risorsa WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iii', $cdl, $esame, $sg);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getLastIdNotifica($cdl, $esame, $sg, $risorsa){
        $query = "SELECT MAX(idnotrisorsa) lastnotifica FROM notificarisorsa WHERE idcdl = ? AND idesame = ? AND idstudygroup = ? AND idrisorsa = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iiii', $cdl, $esame, $sg, $risorsa);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function notificaRis($cdl, $esame, $sg, $idrisorsa, $notifica){
        $query = "INSERT INTO notificarisorsa (idcdl, idesame, idstudygroup, idrisorsa, idnotrisorsa, autorizzata, lavorata, risposta) VALUES ($cdl, $esame, $sg, $idrisorsa, $notifica, 0, 0, 0)";
        return $this->db->query($query);
    }

    public function sendNotificaToAdmin($cdl, $esame, $sg, $idrisorsa, $notifica, $admin){
        $query = "INSERT INTO destnotificarisorsa (idcdl, idesame, idstudygroup, idrisorsa, idnotifica, username, letta, commento) VALUES ($cdl, $esame, $sg, $idrisorsa, $notifica, '$admin', 0, '')";
        return $this->db->query($query);
    }

    public function getNotificaRisToUser($user, $lavorata, $risposta){
        $query = "SELECT D.*, N.autorizzata, N.lavorata, N.commento, R.filerisorsa, R.nomeris, R.username mittente FROM destnotificarisorsa D, notificarisorsa N, risorsa R WHERE D.idcdl = N.idcdl AND D.idesame = N.idesame AND D.idstudygroup = N.idstudygroup AND D.idrisorsa = N.idrisorsa AND D.idnotifica = N.idnotrisorsa AND D.idcdl = R.idcdl AND D.idesame = R.idesame AND D.idstudygroup = R.idstudygroup AND D.idrisorsa = R.idrisorsa AND D.username = ? AND N.lavorata = ? AND N.risposta = ? AND D.letta = 0";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sii', $user, $lavorata, $risposta);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNotificaPrefToUser($user){
        $query = "SELECT D.*, N.username mittente, S.idlingua, S.tema, S.luogo, S.dettaglioluogo, S.data, S.ora, E.nomeesame, C.nomecdl FROM destnotificapreferenza D, notificapreferenza N, studygroup S, esame E, cdl C WHERE D.idcdl = N.idcdl AND D.idesame = N.idesame AND D.idstudygroup = N.idstudygroup AND D.idnotifica = N.idnotifica AND D.idcdl = S.idcdl AND D.idesame = S.idesame AND D.idstudygroup = S.idstudygroup AND D.idcdl = E.idcdl AND D.idesame = E.idesame AND D.idcdl = C.idcdl AND D.username = ? AND D.letta = 0";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateNotificaRis($autorizza, $cdl, $esame, $sg, $risorsa, $notifica, $note){
        $query = "UPDATE `notificarisorsa` SET autorizzata = $autorizza, lavorata = 1, risposta = 1, commento = '$note' WHERE idcdl = $cdl AND idesame = $esame AND idstudygroup = $sg AND idrisorsa = $risorsa AND idnotrisorsa = $notifica";
        if ($this->db->query($query) === TRUE) {
            return "Aggiornamento notifica effettuato con successo";
        } else {
            return "Aggiornamento notifica fallito: " . $this->db->error;
        };
    }

    public function updateDestNotificaRis($cdl, $esame, $sg, $risorsa, $notifica, $user){
        $query = "UPDATE `destnotificarisorsa` SET letta = 1 WHERE idcdl = $cdl AND idesame = $esame AND idstudygroup = $sg AND idrisorsa = $risorsa AND idnotifica = $notifica AND username = '$user'";
        if ($this->db->query($query) === TRUE) {
            return "Aggiornamento destinatario notifica effettuato con successo";
        } else {
            return "Aggiornamento destinatario notifica fallito: " . $this->db->error;
        };
    }

    public function rispostaNotificaRis($cdl, $esame, $sg, $idrisorsa, $notifica, $mittente){
        $query = "INSERT INTO destnotificarisorsa (idcdl, idesame, idstudygroup, idrisorsa, idnotifica, username, letta, commento) VALUES ($cdl, $esame, $sg, $idrisorsa, $notifica, '$mittente', 0, '')";
        if ($this->db->query($query) === TRUE) {
            return "Risposta al mittente inserita con successo";
        } else {
            return "Inserimento risposta al mittente fallito: " . $this->db->error;
        };
    }

    public function updateRisorsa($cdl, $esame, $sg, $risorsa){
        $query = "UPDATE `risorsa` SET notifica = 0 WHERE idcdl = $cdl AND idesame = $esame AND idstudygroup = $sg AND idrisorsa = $risorsa";
        if ($this->db->query($query) === TRUE) {
            return "Aggiornamento risorsa effettuato con successo";
        } else {
            return "Aggiornamento risorsa fallito: " . $this->db->error;
        };
    }

    public function getLastIdNotificaSg($cdl, $esame, $sg){
        $query = "SELECT MAX(idnotifica) lastnotifica FROM notificavarsg WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iii', $cdl, $esame, $sg);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function setNotificaVariazione($cdl, $esame, $sg, $notifica, $user){
        $query = "INSERT INTO notificavarsg (idcdl, idesame, idstudygroup, idnotifica, username) VALUES ($cdl, $esame, $sg, $notifica, '$user')";
        return $this->db->query($query);
    }

    public function sendNotificaToPart($cdl, $esame, $sg, $notifica, $user){
        $query = "INSERT INTO destnotificavarsg (idcdl, idesame, idstudygroup, idnotifica, username, letta) VALUES ($cdl, $esame, $sg, $notifica, '$user', 0)";
        if ($this->db->query($query) === TRUE) {
            return "Invio notifica con successo";
        } else {
            return "Invio notifica fallito: " . $this->db->error;
        };
    }

    public function updateDestNotificaVarSg($cdl, $esame, $sg, $notifica, $user){
        $query = "UPDATE `destnotificavarsg` SET letta = 1 WHERE idcdl = $cdl AND idesame = $esame AND idstudygroup = $sg AND idnotifica = $notifica AND username = '$user'";
        if ($this->db->query($query) === TRUE) {
            return "Aggiornamento destinatario notifica effettuato con successo";
        } else {
            return "Aggiornamento destinatario notifica fallito: " . $this->db->error;
        };
    }

    public function updateSg($cdl, $esame, $sg, $tema, $luogo, $dettaglioluogo, $data, $ora, $lingua) {
        $query = "UPDATE studygroup SET tema = '$tema', luogo = '$luogo', dettaglioluogo = '$dettaglioluogo', `data` = '$data', ora = '$ora', idlingua = '$lingua' WHERE idcdl = '$cdl' AND idesame = '$esame' AND idstudygroup = '$sg'";
        if ($this->db->query($query) === TRUE) {
            return "Aggiornamento Study Group effettuato con successo";
        } else {
            return "Aggiornamento Study Group fallito: " . $this->db->error;
        };
    }
    
    public function setNotificaPreferenza($cdl, $esame, $sg, $notifica, $user){
        $query = "INSERT INTO notificapreferenza (idcdl, idesame, idstudygroup, idnotifica, username) VALUES ($cdl, $esame, $sg, $notifica, '$user')";
        return $this->db->query($query);
    }

    public function sendNotificaPreferenza($cdl, $esame, $sg, $notifica, $user){
        $query = "INSERT INTO destnotificapreferenza (idcdl, idesame, idstudygroup, idnotifica, username, letta) VALUES ($cdl, $esame, $sg, $notifica, '$user', 0)";
        if ($this->db->query($query) === TRUE) {
            return "Invio notifica con successo";
        } else {
            return "Invio notifica fallito: " . $this->db->error;
        };
    }

    public function updateDestNotificaPref($cdl, $esame, $sg, $notifica, $user){
        $query = "UPDATE `destnotificapreferenza` SET letta = 1 WHERE idcdl = $cdl AND idesame = $esame AND idstudygroup = $sg AND idnotifica = $notifica AND username = '$user'";
        if ($this->db->query($query) === TRUE) {
            return "Aggiornamento destinatario notifica effettuato con successo";
        } else {
            return "Aggiornamento destinatario notifica fallito: " . $this->db->error;
        };
    }

    public function getStudyGroupsWithDetails(){
        $query = "SELECT sg.*, e.nomeesame as NomeEsame, e.imgesame as ImgEsame, c.nomecdl as NomeCdl, l.descrizionelingua as NomeLingua 
                  FROM studygroup sg 
                  JOIN esame e ON sg.idesame = e.idesame AND sg.idcdl = e.idcdl 
                  JOIN cdl c ON e.idcdl = c.idcdl 
                  JOIN lingua l ON sg.idlingua = l.idlingua 
                  ORDER BY sg.data DESC";
        return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getStudyGroupsWithDetailsByCdl($idcdl){
        $query = "SELECT sg.*, e.nomeesame as NomeEsame, e.imgesame as ImgEsame, c.nomecdl as NomeCdl, l.descrizionelingua as NomeLingua 
                  FROM studygroup sg 
                  JOIN esame e ON sg.idesame = e.idesame AND sg.idcdl = e.idcdl 
                  JOIN cdl c ON e.idcdl = c.idcdl 
                  JOIN lingua l ON sg.idlingua = l.idlingua 
                  WHERE sg.idcdl = ?
                  ORDER BY sg.data DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idcdl);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getStudyGroupByCdlAndEsame($cdl, $esame, $luogo, $daora, $aora, $lingua){
        $query = "SELECT S.*, E.nomeesame NomeEsame, E.imgesame ImgEsame, C.nomecdl NomeCdl, L.descrizionelingua NomeLingua FROM studygroup S, lingua L, esame E, cdl C WHERE S.idcdl = E.idcdl AND S.idesame = E.idesame AND S.idlingua = L.idlingua AND C.idcdl = S.idcdl AND S.idcdl = ? AND S.idesame = ? AND S.luogo = ? AND S.ora >= ? AND S.ora <= ? AND S.idlingua = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iissss', $cdl, $esame, $luogo, $daora, $aora, $lingua);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStGrScad(){
        return $this->db->query("SELECT S.*, E.nomeesame, E.imgesame FROM studygroup S JOIN esame E ON S.idesame = E.idesame WHERE S.data = CURRENT_DATE ORDER BY S.ora")->fetch_all(MYSQLI_ASSOC);
    }

    public function getStGrAtt(){
        return $this->db->query("SELECT idcdl, idesame FROM studygroup WHERE data >= CURRENT_DATE GROUP BY idcdl, idesame")->fetch_all(MYSQLI_ASSOC);
    }

    public function setPart($c, $e, $s, $u){
        $stmt = $this->db->prepare("INSERT INTO adesione (idcdl, idesame, idstudygroup, username) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiis', $c, $e, $s, $u);
        return $stmt->execute();
    }

    public function removePartSg($c, $e, $s, $u){
        $stmt = $this->db->prepare("DELETE FROM adesione WHERE idcdl=? AND idesame=? AND idstudygroup=? AND username=?");
        $stmt->bind_param('iiis', $c, $e, $s, $u);
        return $stmt->execute();
    }

    public function getNumPartecipanti($c, $e, $s){
        $stmt = $this->db->prepare("SELECT COUNT(*) as numpart FROM adesione WHERE idcdl=? AND idesame=? AND idstudygroup=?");
        $stmt->bind_param('iii', $c, $e, $s);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getSgById($c, $e, $s) {
        $stmt = $this->db->prepare("SELECT S.*, L.descrizionelingua, E.nomeesame FROM studygroup S JOIN lingua L ON S.idlingua = L.idlingua JOIN esame E ON (E.idesame = S.idesame AND E.idcdl = S.idcdl) WHERE S.idcdl = ? AND S.idesame = ? AND S.idstudygroup = ?");
        $stmt->bind_param('iii', $c, $e, $s);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getStGrUtente($username){
        $query = "SELECT S.*, E.nomeesame, E.imgesame, L.descrizionelingua, A.username 
                  FROM studygroup S 
                  JOIN adesione A ON (S.idstudygroup = A.idstudygroup AND A.idesame = S.idesame AND A.idcdl = S.idcdl)
                  JOIN esame E ON (A.idesame = E.idesame AND A.idcdl = E.idcdl)
                  JOIN lingua L ON (L.idlingua = S.idlingua)
                  WHERE A.username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertStudyGroup($tema, $luogo, $data, $ora, $idEsame, $idLingua) {
        $stmtCdl = $this->db->prepare("SELECT idcdl FROM esame WHERE idesame = ?");
        $stmtCdl->bind_param('i', $idEsame);
        $stmtCdl->execute();
        $idcdl = $stmtCdl->get_result()->fetch_assoc()['idcdl'] ?? 1;

        $stmtMax = $this->db->prepare("SELECT COALESCE(MAX(idstudygroup), 0) + 1 as next FROM studygroup");
        $stmtMax->execute();
        $idSg = $stmtMax->get_result()->fetch_assoc()['next'];

        $stmt = $this->db->prepare("INSERT INTO studygroup (idcdl, idesame, idstudygroup, idlingua, tema, luogo, data, ora, dettaglioluogo, amministratoresg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, '', '')");
        $stmt->bind_param('iiisssss', $idcdl, $idEsame, $idSg, $idLingua, $tema, $luogo, $data, $ora);
        $stmt->execute();
        return $idSg;
    }

    public function deleteStudyGroup($id) {
        $stmtA = $this->db->prepare("DELETE FROM adesione WHERE idstudygroup = ?");
        $stmtA->bind_param('i', $id);
        $stmtA->execute();
        $stmtD1 = $this->db->prepare("DELETE FROM destnotificapreferenza WHERE idstudygroup = ?");
        $stmtD1->bind_param('i', $id);
        $stmtD1->execute();
        $stmtD2 = $this->db->prepare("DELETE FROM destnotificarisorsa WHERE idstudygroup = ?");
        $stmtD2->bind_param('i', $id);
        $stmtD2->execute();
        $stmtD3 = $this->db->prepare("DELETE FROM destnotificavarsg WHERE idstudygroup = ?");
        $stmtD3->bind_param('i', $id);
        $stmtD3->execute();
        $stmtM = $this->db->prepare("DELETE FROM messaggio WHERE idstudygroup = ?");
        $stmtM->bind_param('i', $id);
        $stmtM->execute();
        $stmtN1 = $this->db->prepare("DELETE FROM notificapreferenza WHERE idstudygroup = ?");
        $stmtN1->bind_param('i', $id);
        $stmtN1->execute();
        $stmtN2 = $this->db->prepare("DELETE FROM notificarisorsa WHERE idstudygroup = ?");
        $stmtN2->bind_param('i', $id);
        $stmtN2->execute();
        $stmtN3 = $this->db->prepare("DELETE FROM notificavarsg WHERE idstudygroup = ?");
        $stmtN3->bind_param('i', $id);
        $stmtN3->execute();
        $stmtR = $this->db->prepare("DELETE FROM risorsa WHERE idstudygroup = ?");
        $stmtR->bind_param('i', $id);
        $stmtR->execute();
        $stmt = $this->db->prepare("DELETE FROM studygroup WHERE idstudygroup = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /* =========================================================================
       SEZIONE 4: BACHECA E NOTIFICHE
       ========================================================================= */

    public function setMsg($c, $e, $s, $u, $t){
        $stmtId = $this->db->prepare("SELECT COALESCE(MAX(idmessaggio), 0) + 1 as next FROM messaggio WHERE idcdl=? AND idesame=? AND idstudygroup=?");
        $stmtId->bind_param('iii', $c, $e, $s); $stmtId->execute();
        $next = $stmtId->get_result()->fetch_assoc()['next'];
        $stmt = $this->db->prepare("INSERT INTO messaggio (idcdl, idesame, idstudygroup, username, idmessaggio, testomessaggio, msgsegnalato, datamsg, oramsg) VALUES (?, ?, ?, ?, ?, ?, 0, CURRENT_DATE, CURRENT_TIME)");
        $stmt->bind_param('iiisis', $c, $e, $s, $u, $next, $t);
        $result = $stmt->execute();
        if ($result===TRUE) {
            return "ok";
        }
        else {
            return "inserimento fallito". $this->db->error;
            
        }
    }

    public function getMsgsFromId($c, $e, $s){
        $stmt = $this->db->prepare("SELECT M.*, U.* FROM messaggio M JOIN user U ON M.username=U.username WHERE M.idcdl=? AND M.idesame=? AND M.idstudygroup=? ORDER BY datamsg, oramsg");
        $stmt->bind_param('iii', $c, $e, $s);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLastIdNotificaPreferenza($cdl, $esame, $sg){
        $query = "SELECT MAX(idnotifica) idultnot FROM notificapreferenza WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iii', $cdl, $esame, $sg);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================================================================
       SEZIONE 5: PREFERENZE E RISORSE
       ========================================================================= */

    public function getPreferenzaUser($user){
        $query = "SELECT P.*, E.nomeesame, E.imgesame, L.descrizionelingua, C.nomecdl 
                  FROM preferenza P 
                  JOIN esame E ON (P.idcdl = E.idcdl AND P.idesame = E.idesame)
                  JOIN lingua L ON (P.idlingua = L.idlingua)
                  JOIN cdl C ON (P.idcdl = C.idcdl)
                  WHERE P.username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $user);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLastIdPreferenza($cdl, $esame, $user){
        $query = "SELECT COALESCE(MAX(idpreferenza), 0) as lastpref FROM preferenza WHERE idcdl = ? AND idesame = ? AND username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iis', $cdl, $esame, $user);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addPreferenza($user, $cdl, $esame, $pref, $luogo, $daora, $aora, $lingua){
        $query = "INSERT INTO preferenza (username, idcdl, idesame, idpreferenza, luogo, daora, aora, idlingua) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('siiissss', $user, $cdl, $esame, $pref, $luogo, $daora, $aora, $lingua);
        return $stmt->execute();
    }

    public function deletePreferenza($c, $e, $p, $u) {
        $stmt = $this->db->prepare("DELETE FROM preferenza WHERE idcdl = ? AND idesame = ? AND idpreferenza = ? AND username = ?");
        $stmt->bind_param('iiis', $c, $e, $p, $u);
        return $stmt->execute();
    }
    
    public function updatePreferenza($c, $e, $p, $u, $luo, $da, $a, $lin) {
        $stmt = $this->db->prepare("UPDATE preferenza SET luogo=?, daora=?, aora=?, idlingua=? WHERE idcdl=? AND idesame=? AND idpreferenza=? AND username=?");
        $stmt->bind_param('ssssiiis', $luo, $da, $a, $lin, $c, $e, $p, $u);
        return $stmt->execute();
    }

    public function insertResource($c, $e, $s, $pr, $n, $u, $f, $idr){
        $stmt = $this->db->prepare("INSERT INTO risorsa (idcdl, idesame, idstudygroup, private, nomeris, username, filerisorsa, idrisorsa, notifica) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->bind_param('iiiisssi', $c, $e, $s, $pr, $n, $u, $f, $idr);
        return $stmt->execute();
    }

    public function getResourceSg($c, $e, $s) {
        $stmt = $this->db->prepare("SELECT * FROM risorsa WHERE idcdl = ? AND idesame = ? AND idstudygroup = ? AND notifica = 0");
        $stmt->bind_param('iii', $c, $e, $s);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function removeRisorsa($c, $e, $s, $r) {
        $stmt = $this->db->prepare("DELETE FROM risorsa WHERE idcdl=? AND idesame=? AND idstudygroup=? AND idrisorsa=?");
        $stmt->bind_param('iiii', $c, $e, $s, $r);
        if ($stmt->execute()) {
            return "Risorsa rimossa con successo!";
        } else {
        return "Errore rimozione risorsa" . $this->db->error;
        };
    }

    public function getPreferenze($cdl, $esame, $luogo, $ora, $lingua){
        $query = "SELECT DISTINCT username FROM preferenza WHERE idcdl = ? AND idesame = ? AND luogo = ? AND daora <= ? AND aora >= ? AND idlingua = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iissss', $cdl, $esame, $luogo, $ora, $ora, $lingua);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCdlRisorse(){
        $query = "SELECT R.idcdl, C.nomecdl FROM risorsa R, cdl C WHERE R.idcdl = C.idcdl AND R.private = 0 AND R.notifica = 0 GROUP BY R.idcdl, C.nomecdl";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllRisorse(){
        $query = "SELECT * FROM risorsa R, esame E WHERE R.idcdl = E.idcdl AND R.idesame = E.idesame AND R.private = 0 AND R.notifica = 0";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNumRisorse(){
        $query = "SELECT R.idcdl, COUNT(*) numr FROM risorsa R, esame E WHERE R.idcdl = E.idcdl AND R.idesame = E.idesame AND R.private = 0 AND R.notifica = 0 GROUP BY R.idcdl";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================================================================
       SEZIONE 6: UTILITIES
       ========================================================================= */

    public function getLingua(){ return $this->db->query("SELECT * FROM lingua")->fetch_all(MYSQLI_ASSOC); }
    
    public function closeConnection(){ $this->db->close(); }

    public function uploadImage($path, $image){
        $name = bin2hex(random_bytes(8)).".".strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
        return move_uploaded_file($image["tmp_name"], $path.$name) ? [1, $name] : [0, "Errore"];
    }

    /**
     * Recupera tutti gli esami presenti nel database, 
     * inclusi i dettagli del Corso di Laurea associato.
     */
    public function getAllEsami() {
        $query = "SELECT E.*, C.nomecdl 
                FROM esame E 
                JOIN cdl C ON E.idcdl = C.idcdl 
                ORDER BY C.nomecdl, E.nomeesame";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================================================================
    COMPATIBILITÀ: metodi chiamati dal vecchio progetto
    (alias + funzioni mancanti più comuni)
    ========================================================================= */

    public function getCdl(){
        return $this->getAllCdl();
    }

    public function checkPwd($username, $password){
        return $this->checkUserLogin($username, $password);
    }

    public function getPwd($username){
        $stmt = $this->db->prepare("SELECT password FROM user WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteAccount($username, $password){
        $stmt = $this->db->prepare("UPDATE user SET attivo = 0 WHERE username = ? AND password = ?");
        $stmt->bind_param('ss', $username, $password);

        return $stmt->execute();
    }

    public function getNumStudyGroup(){
        $query = "SELECT cdl.idcdl, COUNT(*) AS numstgr
                FROM cdl
                JOIN studygroup ON cdl.idcdl = studygroup.idcdl
                WHERE studygroup.attivo = 1
                GROUP BY cdl.idcdl";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getEsami(){
        $query = "SELECT
                    E.idcdl, E.idesame, E.nomeesame, E.imgesame, E.annoesame,
                    IFNULL(S.sgattivi, 0) AS sgattivi
                FROM esame E
                LEFT JOIN (
                    SELECT idcdl, idesame, COUNT(*) AS sgattivi
                    FROM studygroup
                    WHERE data >= CURRENT_DATE
                    GROUP BY idcdl, idesame
                ) S ON (E.idcdl = S.idcdl AND E.idesame = S.idesame)
                GROUP BY E.idcdl, E.idesame, E.nomeesame, E.annoesame, E.imgesame";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getNameCdlById($id){
        return $this->getCdlById($id);
    }

    public function getActiveStudyGroup($idcdl, $idesame){
        $query = "SELECT *
                FROM studygroup
                WHERE idcdl = ? AND idesame = ?
                    AND data >= CURRENT_DATE";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idcdl, $idesame);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getNumberSg($idcdl, $idesame){
        $query = "SELECT COUNT(*) AS numsg
                FROM studygroup
                WHERE idcdl = ? AND idesame = ?
                    AND data >= CURRENT_DATE";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idcdl, $idesame);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getNumberEsamiSgByCdl(){
        $query = "SELECT idcdl, COUNT(DISTINCT idesame) AS numesami FROM studygroup WHERE data >= CURRENT_DATE GROUP BY idcdl";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function setAdesione($cdl, $esame, $sg, $utente){
        return $this->setPart($cdl, $esame, $sg, $utente);
    }

    public function getPartecipanti($cdl, $esame, $sg){
        $query = "SELECT A.*, U.*
                FROM adesione A
                JOIN user U ON A.username = U.username
                WHERE A.idcdl = ? AND A.idesame = ? AND A.idstudygroup = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iii', $cdl, $esame, $sg);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPartecipantiByUser($cdl, $esame, $sg, $user){
        $query = "SELECT A.*, U.imguser, U.nome, U.cognome
                FROM adesione A
                JOIN user U ON A.username = U.username
                WHERE A.idcdl = ? AND A.idesame = ? AND A.idstudygroup = ? AND A.username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iiis', $cdl, $esame, $sg, $user);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getIdLastSg(){
        $query = "SELECT MAX(idstudygroup) AS maxid FROM studygroup";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function setSg($cdl, $esame, $sg, $lingua, $tema, $luogo, $detluogo, $data, $ora){
        $query = "INSERT INTO studygroup (idcdl, idesame, idstudygroup, idlingua, tema, luogo, dettaglioluogo, data, ora)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iiissssss', $cdl, $esame, $sg, $lingua, $tema, $luogo, $detluogo, $data, $ora);

        return $stmt->execute();
    }

    public function updateStudyGroup($cdl, $esame, $idsg, $tema, $luogo, $dettaglioluogo, $data, $ora, $idlingua){
        $query = "UPDATE studygroup SET tema = ?, luogo = ?, dettaglioluogo = ?, data = ?, ora = ?, idlingua = ? 
                WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssssiiii', $tema, $luogo, $dettaglioluogo, $data, $ora, $idlingua, $cdl, $esame, $idsg);
        return $stmt->execute();
    }

    public function getStudyGroupById($cdl, $esame, $idsg){
        $query = "SELECT * FROM adesione WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iii', $cdl, $esame, $idsg);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getNotificaVarSgToUser($user){
        $query = "SELECT D.*, N.username AS mittente, S.idlingua, S.tema, S.luogo, S.dettaglioluogo, S.data, S.ora,
                        C.nomecdl, E.nomeesame
                FROM destnotificavarsg D
                JOIN notificavarsg N
                    ON D.idcdl = N.idcdl AND D.idesame = N.idesame AND D.idstudygroup = N.idstudygroup AND D.idnotifica = N.idnotifica
                JOIN studygroup S
                    ON D.idcdl = S.idcdl AND D.idesame = S.idesame AND D.idstudygroup = S.idstudygroup
                JOIN cdl C
                    ON D.idcdl = C.idcdl
                JOIN esame E
                    ON D.idcdl = E.idcdl AND D.idesame = E.idesame
                WHERE D.username = ? AND D.letta = 0";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $user);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getRisorseDaApprovare() {
        $query = "SELECT 
                    R.*,
                    U.nome AS nomeutente,
                    U.cognome AS cognomeutente,
                    E.nomeesame,
                    C.nomecdl
                FROM risorsa R
                LEFT JOIN user U 
                    ON U.username = R.username
                LEFT JOIN esame E 
                    ON E.idcdl = R.idcdl AND E.idesame = R.idesame
                LEFT JOIN cdl C 
                    ON C.idcdl = R.idcdl
                WHERE R.notifica = 1
                ORDER BY R.idcdl, R.idesame, R.idstudygroup, R.idrisorsa DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPart($idcdl, $idesame){
        $query = "SELECT * FROM adesione WHERE idcdl = ? AND idesame = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idcdl, $idesame);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>