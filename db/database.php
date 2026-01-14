<?php
    class DatabaseHelper{
        private $db;

        public function __construct($servername, $username, $password, $dbname, $port){
            $this->db = new mysqli($servername, $username, $password, $dbname, $port);
            if ($this->db->connect_error) {
                die("Connection failed: " . $db->connect_error);
            }
        }

        public function checkPwd($username, $password){
            $query = "SELECT username, nome FROM `user` WHERE attivo=1 AND username = ? AND `password` = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ss',$username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function changePwd($username, $password) {
            $query = "UPDATE user SET password = '$password' WHERE username = '$username'";
            if ($this->db->query($query) === TRUE) {
                return "Password cambiata con successo";
            } else {
                return "Cambio password fallito: " . $this->db->error;
            };
        }

        public function changeCdl($username, $cdl) {
            $query = "UPDATE user SET idcdl = '$cdl' WHERE username = '$username'";
            if ($this->db->query($query) === TRUE) {
                return "Corso di Laurea con successo";
            } else {
                return "Cambio Corso di Laurea fallito: " . $this->db->error;
            };
        }

        public function deleteAccount($username, $password){
            $query = "UPDATE user SET attivo = 0 WHERE username = '$username' AND password = '$password'";
            if ($this->db->query($query) === TRUE) {
                return "Account eliminato con successo";
            } else {
                return "Eliminazione account fallita: " . $this->db->error;
            };
        }

        public function getCdl(){
            $stmt = $this->db->prepare("SELECT * FROM cdl");
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getNumStudyGroup(){
            $query = "SELECT idcdl, COUNT(*) AS numstgr FROM cdl, studygroup WHERE cdl.idcdl = studygroup.idcdl AND studygroup.attivo = 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getEsami(){
            $query = "SELECT * FROM esame";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getNameCdlById($id){
            $query = "SELECT * FROM cdl WHERE idcdl = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getEsameById($idcdl, $idesame){
            $query = "SELECT * FROM esame WHERE idcdl = ? AND idesame = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii', $idcdl, $idesame);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getActiveStudyGroup($idcdl, $idesame){
            $query = "SELECT * FROM studygroup WHERE idcdl = ? AND idesame = ? AND data >= CURRENT_DATE";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii', $idcdl, $idesame);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getLingua(){
            $query = "SELECT * FROM lingua";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getPart($idcdl, $idesame){
            $query = "SELECT * FROM adesione WHERE idcdl = ? AND idesame = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii', $idcdl, $idesame);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getStGrScad(){
            $query = "SELECT S.*, E.nomeesame, E.imgesame FROM studygroup AS S, esame AS E  WHERE S.idesame = E.idesame AND S.idcdl = E.idcdl AND data = CURRENT_DATE ORDER BY ora";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getStGrAtt(){
            $query = "SELECT idcdl, idesame FROM studygroup WHERE data >= CURRENT_DATE GROUP BY idcdl, idesame";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getNameUser($username){
            $query = "SELECT * FROM `user` WHERE username = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getPwd($username){
            $query = "SELECT `password` FROM `user` WHERE username = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getStGrUtente($username){
            $query = "SELECT S.*, E.nomeesame, E.imgesame, L.descrizionelingua, A.username FROM `studygroup` S, adesione A, esame E, lingua L WHERE S.idstudygroup = A.idstudygroup AND A.idesame = S.idesame AND A.idcdl = S.idcdl AND A.idesame = E.idesame AND A.idcdl = E.idcdl AND L.idlingua = S.idlingua AND A.username = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        public function getAdmin(){
            $query = "SELECT * FROM `user` WHERE amministratore = 1 AND attivo = 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getIdLastSg($cdl, $esame ){
            $query = "SELECT MAX(idstudygroup) maxid FROM `studygroup` WHERE idcdl = ? AND idesame = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii', $cdl, $esame);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function setSg($cdl, $esame, $sg, $lingua, $tema, $luogo, $detluogo, $data, $ora){
        $query = "INSERT INTO studygroup (idcdl, idesame, idstudygroup, idlingua, tema, luogo, dettaglioluogo, data, ora) VALUES ($cdl, $esame, $sg, '$lingua', '$tema', '$luogo', '$detluogo', '$data', '$ora')";
        
            if ($this->db->query($query) === TRUE) {
                return "Study Group creato con successo";
            } else {
                return "Creazione Study Group fallita: " . $this->db->error;
            };
        }

        public function setAdesione($cdl, $esame, $sg, $utente){
        $query = "INSERT INTO adesione (idcdl, idesame, idstudygroup, username) VALUES ($cdl, $esame, $sg, '$utente')";
        
            if ($this->db->query($query) === TRUE) {
                return "Hai aderito con successo allo Study Group";
            } else {
                return "Adesione allo Study Group fallita: " . $this->db->error;
            };
        }

        public function getNumberSg($cdl, $esame){
            $query = "SELECT COUNT(*) numsg FROM studygroup WHERE idcdl = ? AND idesame = ? AND data >= CURRENT_DATE";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii', $cdl, $esame);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function isAdmin($username){
            $query = "SELECT amministratore FROM user WHERE username = ? AND attivo = 1";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getSgById($cdl, $esame, $sg){
            $query = "SELECT S.*, L.descrizionelingua  FROM studygroup S, lingua L WHERE S.idcdl = ? AND S.idesame = ? AND S.idstudygroup = ? AND L.idlingua = S.idlingua";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('iii', $cdl, $esame, $sg);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getNumPartecipanti($cdl, $esame, $sg){
            $query = "SELECT COUNT(*) numpart FROM adesione WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('iii', $cdl, $esame, $sg);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getPartecipanti($cdl, $esame, $sg){
            $query = "SELECT A.*, U.* FROM adesione A, `user` U WHERE A.username = U.username AND A.idcdl = ? AND A.idesame = ? AND A.idstudygroup = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('iii', $cdl, $esame, $sg);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function updateSg($cdl, $esame, $sg, $tema, $luogo, $dettaglioluogo, $data, $ora, $lingua) {
            $query = "UPDATE studygroup SET tema = '$tema', luogo = '$luogo', dettaglioluogo = '$dettaglioluogo', `data` = '$data', ora = '$ora', idlingua = '$lingua' WHERE idcdl = '$cdl' AND idesame = '$esame' AND idstudygroup = '$sg'";
            if ($this->db->query($query) === TRUE) {
                return "Aggiornamento Study Group effettuato con successo";
            } else {
                return "Aggiornamento Study Group fallito: " . $this->db->error;
            };
        }

        public function getLastIdRisorsa($cdl, $esame, $sg){
            $query = "SELECT MAX(idrisorsa) lastrisorsa FROM risorsa WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('iii', $cdl, $esame, $sg);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function insertResource($cdl, $esame, $sg, $private, $nomeris, $user, $file, $idrisorsa){
        $query = "INSERT INTO risorsa (idcdl, idesame, idstudygroup, `private`, nomeris, username, filerisorsa, idrisorsa, notifica) VALUES ($cdl, $esame, $sg, $private, '$nomeris', '$user', '$file', $idrisorsa, 1)";
        return $this->db->query($query);
        }

        public function getResourceSg($cdl, $esame, $sg){
            $query = "SELECT * FROM risorsa WHERE idcdl = ? AND idesame = ? AND idstudygroup = ? AND notifica = 0";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('iii', $cdl, $esame, $sg);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getMsgsFromId($cdl, $esame, $sg){
            $query = "SELECT M.*, U.* FROM messaggio M, user U WHERE M.username = U.username AND M.idcdl = ? AND M.idesame = ? AND M.idstudygroup = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('iii', $cdl, $esame, $sg);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getUser($username){
            $query = "SELECT * FROM `user` WHERE username = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function getLastIdMsg($cdl, $esame, $sg){
            $query = "SELECT MAX(idmessaggio) lastmsg FROM messaggio WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('iii', $cdl, $esame, $sg);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function setMsg($cdl, $esame, $sg, $msg, $user, $testo){
        $query = "INSERT INTO messaggio (idcdl, idesame, idstudygroup, username, idmessaggio, testomessaggio, msgsegnalato, datamsg, oramsg) VALUES ($cdl, $esame, $sg, '$user', $msg, '$testo', 0, CURRENT_DATE , CURRENT_TIME)";
        if ($this->db->query($query) === TRUE) {
                return "Messaggio inserito con successo";
            } else {
                return "Inserimento messaggio fallito: " . $this->db->error;
            };
        }

        public function updateAnag($username, $nome, $cognome, $file){
        $query = "UPDATE `user` SET nome = '$nome', cognome = '$cognome', imguser = '$file' WHERE username = '$username'";
        return $this->db->query($query);
        }

        public function updateAnag2($username, $nome, $cognome,){
        $query = "UPDATE `user` SET nome = '$nome', cognome = '$cognome' WHERE username = '$username'";
        if ($this->db->query($query) === TRUE) {
                return "Aggiornamento effettuato con successo";
            } else {
                return "Aggiornamento fallito: " . $this->db->error;
            };
        }

        public function removePartSg($cdl, $esame, $sg, $user){
        $query = "DELETE FROM `adesione` WHERE idcdl = $cdl AND idesame = $esame AND idstudygroup = $sg AND username = '$user'";
        if ($this->db->query($query) === TRUE) {
                return "Ti sei disiscritto con successo";
            } else {
                return "Disiscrizione fallita: " . $this->db->error;
            };
        }

        public function setPart($cdl, $esame, $sg, $user){
        $query = "INSERT INTO `adesione` (idcdl, idesame, idstudygroup, username) VALUES ($cdl, $esame, $sg, '$user')";
        if ($this->db->query($query) === TRUE) {
                return "Ti sei iscritto con successo";
            } else {
                return "Iscrizione fallita: " . $this->db->error;
            };
        }

        public function getPartecipantiByUser($cdl, $esame, $sg, $user){
            $query = "SELECT A.*, U.imguser, U.nome, U.cognome FROM adesione A, `user` U WHERE A.username = U.username AND A.idcdl = ? AND A.idesame = ? AND A.idstudygroup = ? AND A.username = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('iiis', $cdl, $esame, $sg, $user);
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

        public function getNotificaVarSgToUser($user){
            $query = "SELECT D.*, N.username mittente, S.idlingua, S.tema, S.luogo, S.dettaglioluogo, S.data, S.ora, C.nomecdl, E.nomeesame FROM destnotificavarsg D, notificavarsg N, studygroup S, cdl C, esame E WHERE D.idcdl = N.idcdl AND D.idesame = N.idesame AND D.idstudygroup = N.idstudygroup AND D.idnotifica = N.idnotifica AND D.idcdl = S.idcdl AND D.idesame = S.idesame AND D.idstudygroup = S.idstudygroup AND D.idcdl = C.idcdl AND D.idcdl = E.idcdl AND D.idesame = E.idesame AND D.username = ? AND D.letta = 0";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $user);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function updateDestNotificaVarSg($cdl, $esame, $sg, $notifica, $user){
        $query = "UPDATE `destnotificavarsg` SET letta = 1 WHERE idcdl = $cdl AND idesame = $esame AND idstudygroup = $sg AND idnotifica = $notifica AND username = '$user'";
        if ($this->db->query($query) === TRUE) {
                return "Aggiornamento destinatario notifica effettuato con successo";
            } else {
                return "Aggiornamento destinatario notifica fallito: " . $this->db->error;
            };
        }

        public function getLastIdPreferenza($cdl, $esame, $user){
            $query = "SELECT MAX(idpreferenza) lastpref FROM preferenza WHERE idcdl = ? AND idesame = ? AND username = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('iis', $cdl, $esame, $user);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function addPreferenza($user, $cdl, $esame, $pref, $luogo, $daora, $aora, $lingua){
        $query = "INSERT INTO `preferenza` (idcdl, idesame, username, idpreferenza, luogo, daora, aora, idlingua) VALUES ($cdl, $esame, '$user', $pref, '$luogo', '$daora', '$aora', '$lingua')";
        if ($this->db->query($query) === TRUE) {
                return "Preferenza aggiunta con successo";
            } else {
                return "Inserimento preferenza fallito: " . $this->db->error;
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

        public function getLastIdNotificaPreferenza($cdl, $esame, $sg){
            $query = "SELECT MAX(idnotifica) idultnot FROM notificapreferenza WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('iii', $cdl, $esame, $sg);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
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

        public function getNotificaPrefToUser($user){
            $query = "SELECT D.*, N.username mittente, S.idlingua, S.tema, S.luogo, S.dettaglioluogo, S.data, S.ora, E.nomeesame, C.nomecdl FROM destnotificapreferenza D, notificapreferenza N, studygroup S, esame E, cdl C WHERE D.idcdl = N.idcdl AND D.idesame = N.idesame AND D.idstudygroup = N.idstudygroup AND D.idnotifica = N.idnotifica AND D.idcdl = S.idcdl AND D.idesame = S.idesame AND D.idstudygroup = S.idstudygroup AND D.idcdl = E.idcdl AND D.idesame = E.idesame AND D.idcdl = C.idcdl AND D.username = ? AND D.letta = 0";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $user);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function updateDestNotificaPref($cdl, $esame, $sg, $notifica, $user){
        $query = "UPDATE `destnotificapreferenza` SET letta = 1 WHERE idcdl = $cdl AND idesame = $esame AND idstudygroup = $sg AND idnotifica = $notifica AND username = '$user'";
        if ($this->db->query($query) === TRUE) {
                return "Aggiornamento destinatario notifica effettuato con successo";
            } else {
                return "Aggiornamento destinatario notifica fallito: " . $this->db->error;
            };
        }

        public function getPreferenzaUser($user){
            $query = "SELECT P.*, E.nomeesame, E.imgesame, L.descrizionelingua, C.nomecdl FROM preferenza P, esame E, lingua L, cdl C WHERE P.idcdl = E.idcdl AND P.idesame = E.idesame AND P.idlingua = L.idlingua AND P.idcdl = C.idcdl AND P.username = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $user);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function deletePreferenza($cdl, $esame, $preferenza, $user){
        $query = "DELETE FROM `preferenza` WHERE idcdl = $cdl AND idesame = $esame AND idpreferenza = $preferenza AND username = '$user'";
        if ($this->db->query($query) === TRUE) {
                return "Preferenza eliminata con successo";
            } else {
                return "Rimozione preferenza fallita: " . $this->db->error;
            };
        }

        public function updatePreferenza($cdl, $esame, $preferenza, $user, $luogo, $daora, $aora, $lingua){
        $query = "UPDATE `preferenza` SET luogo = '$luogo', daora = '$daora', aora = '$aora', idlingua = '$lingua' WHERE idcdl = $cdl AND idesame = $esame AND idpreferenza = $preferenza AND username = '$user'";
        if ($this->db->query($query) === TRUE) {
                return "Aggiornamento preferenza effettuato con successo";
            } else {
                return "Aggiornamento preferenza fallito: " . $this->db->error;
            };
        }

        public function removeRisorsa($cdl, $esame, $sg, $risorsa){
        $query = "DELETE FROM `risorsa` WHERE idcdl = $cdl AND idesame = $esame AND idstudygroup = $sg AND idrisorsa = $risorsa";
        if ($this->db->query($query) === TRUE) {
                return "Risorsa rimossa con successo";
            } else {
                return "Rimozione risorsa fallita: " . $this->db->error;
            };
        }

        public function getEsamiByIdCdl($cdl){
            $query = "SELECT * FROM esame WHERE idcdl = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $cdl);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }
    
?>