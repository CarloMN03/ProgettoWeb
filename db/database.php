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
            $query = "SELECT username, nome FROM autore WHERE attivo=1 AND username = ? AND password = ?";
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
            $query = "UPDATE user SET cdl = '$cdl' WHERE username = '$username'";
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
            $stmt = $this->db->prepare("SELECT id, nomecdl, img, sedecdl FROM cdl");
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }
?>