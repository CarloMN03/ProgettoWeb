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
            $query = "SELECT nome FROM `user` WHERE username = ?";
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

        public function setSg($cdl, $esame, $sg, $lingua, $tema, $luogo, $data, $ora){
        $query = "INSERT INTO studygroup (idcdl, idesame, idstudygroup, idlingua, tema, luogo, data, ora) VALUES ($cdl, $esame, $sg, '$lingua', '$tema', '$luogo', '$data', '$ora')";
        
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
    }
?>