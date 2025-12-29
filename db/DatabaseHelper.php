<?php
class DatabaseHelper {
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }        
    }

    // ==================== CDL (Corsi di Laurea) ====================
    
    public function getAllCdl(){
        $stmt = $this->db->prepare("SELECT ID, Nome, Campus FROM cdl ORDER BY Nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCdlById($id){
        $stmt = $this->db->prepare("SELECT ID, Nome, Campus FROM cdl WHERE ID = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertCdl($nome, $campus){
        $query = "INSERT INTO cdl (Nome, Campus) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $nome, $campus);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateCdl($id, $nome, $campus){
        $query = "UPDATE cdl SET Nome = ?, Campus = ? WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssi', $nome, $campus, $id);
        return $stmt->execute();
    }

    public function deleteCdl($id){
        $query = "DELETE FROM cdl WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // ==================== ADMIN ====================
    
    public function checkAdminLogin($username, $password){
        $query = "SELECT ID, Name, Username FROM admin WHERE Username = ? AND Password = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAdminById($id){
        $stmt = $this->db->prepare("SELECT ID, Name, Username FROM admin WHERE ID = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // ==================== USER ====================
    
    public function getUserById($id){
        $stmt = $this->db->prepare("SELECT ID, Nome, Cognome, Email, Username FROM user WHERE ID = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function checkUserLogin($username, $password){
        $query = "SELECT ID, Nome, Cognome, Email, Username FROM user WHERE Username = ? AND Password = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertUser($nome, $cognome, $email, $username, $password){
        $query = "INSERT INTO user (Nome, Cognome, Email, Username, Password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssss', $nome, $cognome, $email, $username, $password);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateUser($id, $nome, $cognome, $email, $username){
        $query = "UPDATE user SET Nome = ?, Cognome = ?, Email = ?, Username = ? WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssi', $nome, $cognome, $email, $username, $id);
        return $stmt->execute();
    }

    public function updateUserPassword($id, $password){
        $query = "UPDATE user SET Password = ? WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $password, $id);
        return $stmt->execute();
    }

    public function deleteUser($id){
        $query = "DELETE FROM user WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // ==================== ESAME ====================
    
    public function getAllEsami(){
        $stmt = $this->db->prepare("SELECT ID, Nome, Professore, Anno, cdl FROM esame ORDER BY Nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEsamiByCdl($idCdl){
        $stmt = $this->db->prepare("SELECT ID, Nome, Professore, Anno, cdl FROM esame WHERE cdl = ? ORDER BY Nome");
        $stmt->bind_param('i', $idCdl);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEsameById($id){
        $stmt = $this->db->prepare("SELECT ID, Nome, Professore, Anno, cdl FROM esame WHERE ID = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertEsame($nome, $professore, $anno, $idCdl){
        $query = "INSERT INTO esame (Nome, Professore, Anno, cdl) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssii', $nome, $professore, $anno, $idCdl);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateEsame($id, $nome, $professore, $anno, $idCdl){
        $query = "UPDATE esame SET Nome = ?, Professore = ?, Anno = ?, cdl = ? WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssii', $nome, $professore, $anno, $idCdl, $id);
        return $stmt->execute();
    }

    public function deleteEsame($id){
        $query = "DELETE FROM esame WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // ==================== LINGUA ====================
    
    public function getAllLingue(){
        $stmt = $this->db->prepare("SELECT ID, Nome FROM lingua ORDER BY Nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getLinguaById($id){
        $stmt = $this->db->prepare("SELECT ID, Nome FROM lingua WHERE ID = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // ==================== STUDYGROUP ====================
    
    public function getAllStudyGroups(){
        $query = "SELECT ID, Tema, Luogo, Data, Ora, Esame, Lingua, Partecipanti FROM studygroup ORDER BY Data DESC, Ora DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStudyGroupById($id){
        $query = "SELECT ID, Tema, Luogo, Data, Ora, Esame, Lingua, Partecipanti FROM studygroup WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStudyGroupsByEsame($idEsame){
        $query = "SELECT ID, Tema, Luogo, Data, Ora, Esame, Lingua, Partecipanti FROM studygroup WHERE Esame = ? ORDER BY Data DESC, Ora DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idEsame);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStudyGroupsByDate($data){
        $query = "SELECT ID, Tema, Luogo, Data, Ora, Esame, Lingua, Partecipanti FROM studygroup WHERE Data = ? ORDER BY Ora";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $data);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUpcomingStudyGroups($limit = -1){
        $today = date('Y-m-d');
        $query = "SELECT ID, Tema, Luogo, Data, Ora, Esame, Lingua, Partecipanti FROM studygroup WHERE Data >= ? ORDER BY Data, Ora";
        if($limit > 0){
            $query .= " LIMIT ?";
        }
        $stmt = $this->db->prepare($query);
        if($limit > 0){
            $stmt->bind_param('si', $today, $limit);
        } else {
            $stmt->bind_param('s', $today);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertStudyGroup($tema, $luogo, $data, $ora, $idEsame, $idLingua, $partecipanti){
        $query = "INSERT INTO studygroup (Tema, Luogo, Data, Ora, Esame, Lingua, Partecipanti) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssiil', $tema, $luogo, $data, $ora, $idEsame, $idLingua, $partecipanti);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateStudyGroup($id, $tema, $luogo, $data, $ora, $idEsame, $idLingua, $partecipanti){
        $query = "UPDATE studygroup SET Tema = ?, Luogo = ?, Data = ?, Ora = ?, Esame = ?, Lingua = ?, Partecipanti = ? WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssiiii', $tema, $luogo, $data, $ora, $idEsame, $idLingua, $partecipanti, $id);
        return $stmt->execute();
    }

    public function updateStudyGroupPartecipanti($id, $partecipanti){
        $query = "UPDATE studygroup SET Partecipanti = ? WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $partecipanti, $id);
        return $stmt->execute();
    }

    public function deleteStudyGroup($id){
        $query = "DELETE FROM studygroup WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // Blocca un utente
    public function blockUser($id){
        $query = "UPDATE user SET Bloccato = 1 WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // Sblocca un utente
    public function unblockUser($id){
        $query = "UPDATE user SET Bloccato = 0 WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // Rendi un utente amministratore
    public function makeUserAdmin($id){
        $query = "UPDATE user SET IsAdmin = 1 WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // Rimuovi privilegi di amministratore
    public function removeUserAdmin($id){
        $query = "UPDATE user SET IsAdmin = 0 WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // Ottieni tutti gli utenti normali (non bloccati, non admin)
    public function getNormalUsers(){
        $stmt = $this->db->prepare("SELECT ID, Nome, Cognome, Email, Username, Bloccato, IsAdmin FROM user WHERE Bloccato = 0 AND IsAdmin = 0 ORDER BY Cognome, Nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Ottieni tutti gli utenti bloccati
    public function getBlockedUsers(){
        $stmt = $this->db->prepare("SELECT ID, Nome, Cognome, Email, Username, Bloccato, IsAdmin FROM user WHERE Bloccato = 1 ORDER BY Cognome, Nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Ottieni tutti gli amministratori
    public function getAdminUsers(){
        $stmt = $this->db->prepare("SELECT ID, Nome, Cognome, Email, Username, Bloccato, IsAdmin FROM user WHERE IsAdmin = 1 ORDER BY Cognome, Nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Modifica il metodo getAllUsers esistente per includere i nuovi campi
    public function getAllUsers(){
        $stmt = $this->db->prepare("SELECT ID, Nome, Cognome, Email, Username, Bloccato, IsAdmin FROM user ORDER BY Cognome, Nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // ==================== FUNZIONI AVANZATE ====================
    
    // Ottiene tutti i gruppi di studio con informazioni complete (JOIN)
    public function getStudyGroupsWithDetails(){
        $query = "SELECT 
                    sg.ID, sg.Tema, sg.Luogo, sg.Data, sg.Ora, sg.Partecipanti,
                    e.Nome as NomeEsame,
                    c.Nome as NomeCdl,
                    l.Nome as NomeLingua
                  FROM studygroup sg
                  JOIN esame e ON sg.Esame = e.ID
                  JOIN cdl c ON e.cdl = c.ID
                  JOIN lingua l ON sg.Lingua = l.ID
                  ORDER BY sg.Data DESC, sg.Ora DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Cerca studygroup per keyword nel tema
    public function searchStudyGroupsByTema($keyword){
        $query = "SELECT ID, Tema, Luogo, Data, Ora, Esame, Lingua, Partecipanti 
                  FROM studygroup 
                  WHERE Tema LIKE ? 
                  ORDER BY Data DESC, Ora DESC";
        $stmt = $this->db->prepare($query);
        $searchTerm = "%{$keyword}%";
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Conta il numero di studygroup per esame
    public function countStudyGroupsByEsame($idEsame){
        $stmt = $this->db->prepare("SELECT COUNT(*) as totale FROM studygroup WHERE Esame = ?");
        $stmt->bind_param('i', $idEsame);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['totale'];
    }

    // Chiude la connessione al database
    public function closeConnection(){
        $this->db->close();
    }
}
?>