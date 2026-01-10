<?php
class DatabaseHelper {
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }        
    }
    
    public function getAllCdl(){
        $stmt = $this->db->prepare("SELECT idcdl as ID, nomecdl as Nome, sede as Campus FROM cdl ORDER BY nomecdl");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCdlById($id){
        $stmt = $this->db->prepare("SELECT idcdl as ID, nomecdl as Nome, sede as Campus FROM cdl WHERE idcdl = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertCdl($nome, $campus){
        $query = "INSERT INTO cdl (nomecdl, sede, img, durata) VALUES (?, ?, '', 3)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $nome, $campus);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateCdl($id, $nome, $campus){
        $query = "UPDATE cdl SET nomecdl = ?, sede = ? WHERE idcdl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssi', $nome, $campus, $id);
        return $stmt->execute();
    }

    public function deleteCdl($id){
        $query = "DELETE FROM cdl WHERE idcdl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function checkAdminLogin($username, $password){
        $query = "SELECT username as ID, nome as Name, username as Username FROM user WHERE username = ? AND password = ? AND amministratore = '1'";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAdminById($id){
        $stmt = $this->db->prepare("SELECT username as ID, nome as Name, username as Username FROM user WHERE username = ? AND amministratore = '1'");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
  
    public function getUserById($id){
        $stmt = $this->db->prepare("SELECT username as ID, nome as Nome, cognome as Cognome, username as Email, username as Username FROM user WHERE username = ?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function checkUserLogin($username, $password){
        $query = "SELECT username as ID, nome as Nome, cognome as Cognome, username as Email, username as Username FROM user WHERE username = ? AND password = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertUser($nome, $cognome, $email, $username, $password){
        $query = "INSERT INTO user (username, cognome, nome, password, attivo, amministratore, imguser, idcdl) VALUES (?, ?, ?, ?, '1', '0', '', NULL)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssss', $username, $cognome, $nome, $password);
        $stmt->execute();
        return $username;
    }

    public function updateUser($id, $nome, $cognome, $email, $username){
        $query = "UPDATE user SET nome = ?, cognome = ?, username = ? WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssss', $nome, $cognome, $username, $id);
        return $stmt->execute();
    }

    public function updateUserPassword($id, $password){
        $query = "UPDATE user SET password = ? WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $password, $id);
        return $stmt->execute();
    }

    public function deleteUser($id){
        $query = "DELETE FROM user WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }
  
    public function getAllEsami(){
        $stmt = $this->db->prepare("SELECT CONCAT(idcdl, '-', idesame) as ID, nomeesame as Nome, '' as Professore, annoesame as Anno, idcdl as cdl FROM esame ORDER BY nomeesame");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEsamiByCdl($idCdl){
        $stmt = $this->db->prepare("SELECT CONCAT(idcdl, '-', idesame) as ID, nomeesame as Nome, '' as Professore, annoesame as Anno, idcdl as cdl FROM esame WHERE idcdl = ? ORDER BY nomeesame");
        $stmt->bind_param('i', $idCdl);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEsameById($id){
        // Assumiamo che l'ID arrivi come "idcdl-idesame"
        $parts = explode('-', $id);
        if(count($parts) == 2){
            $idcdl = $parts[0];
            $idesame = $parts[1];
            $stmt = $this->db->prepare("SELECT CONCAT(idcdl, '-', idesame) as ID, nomeesame as Nome, '' as Professore, annoesame as Anno, idcdl as cdl FROM esame WHERE idcdl = ? AND idesame = ?");
            $stmt->bind_param('ii', $idcdl, $idesame);
        } else {
            // Fallback se l'ID non è nel formato atteso
            $stmt = $this->db->prepare("SELECT CONCAT(idcdl, '-', idesame) as ID, nomeesame as Nome, '' as Professore, annoesame as Anno, idcdl as cdl FROM esame WHERE idesame = ?");
            $stmt->bind_param('i', $id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertEsame($nome, $professore, $anno, $idCdl){
        $query = "INSERT INTO esame (idcdl, nomeesame, annoesame, imgesame) VALUES (?, ?, ?, '')";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('isi', $idCdl, $nome, $anno);
        $stmt->execute();
        return $idCdl . '-' . $stmt->insert_id;
    }

    public function updateEsame($id, $nome, $professore, $anno, $idCdl){
        $parts = explode('-', $id);
        if(count($parts) == 2){
            $idcdl = $parts[0];
            $idesame = $parts[1];
            $query = "UPDATE esame SET nomeesame = ?, annoesame = ?, idcdl = ? WHERE idcdl = ? AND idesame = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('siiii', $nome, $anno, $idCdl, $idcdl, $idesame);
        } else {
            $query = "UPDATE esame SET nomeesame = ?, annoesame = ?, idcdl = ? WHERE idesame = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('siii', $nome, $anno, $idCdl, $id);
        }
        return $stmt->execute();
    }

    public function deleteEsame($id){
        $parts = explode('-', $id);
        if(count($parts) == 2){
            $idcdl = $parts[0];
            $idesame = $parts[1];
            $query = "DELETE FROM esame WHERE idcdl = ? AND idesame = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii', $idcdl, $idesame);
        } else {
            $query = "DELETE FROM esame WHERE idesame = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $id);
        }
        return $stmt->execute();
    }

    public function getAllLingue(){
        $stmt = $this->db->prepare("SELECT idlingua as ID, descrizionelingua as Nome FROM lingua ORDER BY descrizionelingua");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getLinguaById($id){
        $stmt = $this->db->prepare("SELECT idlingua as ID, descrizionelingua as Nome FROM lingua WHERE idlingua = ?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllStudyGroups(){
        $query = "SELECT CONCAT(idcdl, '-', idesame, '-', idstudygroup) as ID, tema as Tema, luogo as Luogo, data as Data, ora as Ora, CONCAT(idcdl, '-', idesame) as Esame, idlingua as Lingua, 0 as Partecipanti FROM studygroup ORDER BY data DESC, ora DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStudyGroupById($id){
        $parts = explode('-', $id);
        if(count($parts) == 3){
            $idcdl = $parts[0];
            $idesame = $parts[1];
            $idstudygroup = $parts[2];
            $query = "SELECT CONCAT(idcdl, '-', idesame, '-', idstudygroup) as ID, tema as Tema, luogo as Luogo, data as Data, ora as Ora, CONCAT(idcdl, '-', idesame) as Esame, idlingua as Lingua, 0 as Partecipanti FROM studygroup WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('iii', $idcdl, $idesame, $idstudygroup);
        } else {
            $query = "SELECT CONCAT(idcdl, '-', idesame, '-', idstudygroup) as ID, tema as Tema, luogo as Luogo, data as Data, ora as Ora, CONCAT(idcdl, '-', idesame) as Esame, idlingua as Lingua, 0 as Partecipanti FROM studygroup WHERE idstudygroup = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStudyGroupsByEsame($idEsame){
        $parts = explode('-', $idEsame);
        if(count($parts) == 2){
            $idcdl = $parts[0];
            $idesame = $parts[1];
            $query = "SELECT CONCAT(idcdl, '-', idesame, '-', idstudygroup) as ID, tema as Tema, luogo as Luogo, data as Data, ora as Ora, CONCAT(idcdl, '-', idesame) as Esame, idlingua as Lingua, 0 as Partecipanti FROM studygroup WHERE idcdl = ? AND idesame = ? ORDER BY data DESC, ora DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii', $idcdl, $idesame);
        } else {
            $query = "SELECT CONCAT(idcdl, '-', idesame, '-', idstudygroup) as ID, tema as Tema, luogo as Luogo, data as Data, ora as Ora, CONCAT(idcdl, '-', idesame) as Esame, idlingua as Lingua, 0 as Partecipanti FROM studygroup WHERE idesame = ? ORDER BY data DESC, ora DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $idEsame);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStudyGroupsByDate($data){
        $query = "SELECT CONCAT(idcdl, '-', idesame, '-', idstudygroup) as ID, tema as Tema, luogo as Luogo, data as Data, ora as Ora, CONCAT(idcdl, '-', idesame) as Esame, idlingua as Lingua, 0 as Partecipanti FROM studygroup WHERE data = ? ORDER BY ora";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $data);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUpcomingStudyGroups($limit = -1){
        $today = date('Y-m-d');
        $query = "SELECT CONCAT(idcdl, '-', idesame, '-', idstudygroup) as ID, tema as Tema, luogo as Luogo, data as Data, ora as Ora, CONCAT(idcdl, '-', idesame) as Esame, idlingua as Lingua, 0 as Partecipanti FROM studygroup WHERE data >= ? ORDER BY data, ora";
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
        $parts = explode('-', $idEsame);
        if(count($parts) == 2){
            $idcdl = $parts[0];
            $idesame = $parts[1];
        } else {
            $idcdl = 1; // Default
            $idesame = $idEsame;
        }
        
        // Trova il prossimo idstudygroup disponibile
        $stmtMax = $this->db->prepare("SELECT COALESCE(MAX(idstudygroup), 0) + 1 as nextid FROM studygroup WHERE idcdl = ? AND idesame = ?");
        $stmtMax->bind_param('ii', $idcdl, $idesame);
        $stmtMax->execute();
        $resultMax = $stmtMax->get_result();
        $rowMax = $resultMax->fetch_assoc();
        $idstudygroup = $rowMax['nextid'];
        
        $query = "INSERT INTO studygroup (idcdl, idesame, idstudygroup, idlingua, tema, luogo, dettaglioluogo, data, ora, amministratoresg) VALUES (?, ?, ?, ?, ?, ?, '', ?, ?, '')";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iiisssss', $idcdl, $idesame, $idstudygroup, $idLingua, $tema, $luogo, $data, $ora);
        $stmt->execute();
        return $idcdl . '-' . $idesame . '-' . $idstudygroup;
    }

    public function updateStudyGroup($id, $tema, $luogo, $data, $ora, $idEsame, $idLingua, $partecipanti){
        $parts = explode('-', $id);
        if(count($parts) == 3){
            $idcdl = $parts[0];
            $idesame = $parts[1];
            $idstudygroup = $parts[2];
            $query = "UPDATE studygroup SET tema = ?, luogo = ?, data = ?, ora = ?, idlingua = ? WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ssssslii', $tema, $luogo, $data, $ora, $idLingua, $idcdl, $idesame, $idstudygroup);
        } else {
            $query = "UPDATE studygroup SET tema = ?, luogo = ?, data = ?, ora = ?, idlingua = ? WHERE idstudygroup = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('sssssli', $tema, $luogo, $data, $ora, $idLingua, $id);
        }
        return $stmt->execute();
    }

    public function updateStudyGroupPartecipanti($id, $partecipanti){
        // Nel nuovo schema non c'è il campo Partecipanti, quindi questa funzione non fa nulla
        return true;
    }

    public function deleteStudyGroup($id){
        $parts = explode('-', $id);
        if(count($parts) == 3){
            $idcdl = $parts[0];
            $idesame = $parts[1];
            $idstudygroup = $parts[2];
            $query = "DELETE FROM studygroup WHERE idcdl = ? AND idesame = ? AND idstudygroup = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('iii', $idcdl, $idesame, $idstudygroup);
        } else {
            $query = "DELETE FROM studygroup WHERE idstudygroup = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $id);
        }
        return $stmt->execute();
    }

    // Blocca un utente
    public function blockUser($id){
        $query = "UPDATE user SET attivo = '0' WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }

    // Sblocca un utente
    public function unblockUser($id){
        $query = "UPDATE user SET attivo = '1' WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }

    // Rendi un utente amministratore
    public function makeUserAdmin($id){
        $query = "UPDATE user SET amministratore = '1' WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }

    // Rimuovi privilegi di amministratore
    public function removeUserAdmin($id){
        $query = "UPDATE user SET amministratore = '0' WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }

    // Ottieni tutti gli utenti normali (non bloccati, non admin)
    public function getNormalUsers(){
        $stmt = $this->db->prepare("SELECT username as ID, nome as Nome, cognome as Cognome, username as Email, username as Username, CASE WHEN attivo='0' THEN 1 ELSE 0 END as Bloccato, CASE WHEN amministratore='1' THEN 1 ELSE 0 END as IsAdmin FROM user WHERE attivo = '1' AND amministratore = '0' ORDER BY cognome, nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Ottieni tutti gli utenti bloccati
    public function getBlockedUsers(){
        $stmt = $this->db->prepare("SELECT username as ID, nome as Nome, cognome as Cognome, username as Email, username as Username, CASE WHEN attivo='0' THEN 1 ELSE 0 END as Bloccato, CASE WHEN amministratore='1' THEN 1 ELSE 0 END as IsAdmin FROM user WHERE attivo = '0' ORDER BY cognome, nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Ottieni tutti gli amministratori
    public function getAdminUsers(){
        $stmt = $this->db->prepare("SELECT username as ID, nome as Nome, cognome as Cognome, username as Email, username as Username, CASE WHEN attivo='0' THEN 1 ELSE 0 END as Bloccato, CASE WHEN amministratore='1' THEN 1 ELSE 0 END as IsAdmin FROM user WHERE amministratore = '1' ORDER BY cognome, nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Modifica il metodo getAllUsers esistente per includere i nuovi campi
    public function getAllUsers(){
        $stmt = $this->db->prepare("SELECT username as ID, nome as Nome, cognome as Cognome, username as Email, username as Username, CASE WHEN attivo='0' THEN 1 ELSE 0 END as Bloccato, CASE WHEN amministratore='1' THEN 1 ELSE 0 END as IsAdmin FROM user ORDER BY cognome, nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Ottiene tutti i gruppi di studio con informazioni complete (JOIN)
    public function getStudyGroupsWithDetails(){
        $query = "SELECT 
                    CONCAT(sg.idcdl, '-', sg.idesame, '-', sg.idstudygroup) as ID, 
                    sg.tema as Tema, 
                    sg.luogo as Luogo, 
                    sg.data as Data, 
                    sg.ora as Ora, 
                    0 as Partecipanti,
                    e.nomeesame as NomeEsame,
                    c.nomecdl as NomeCdl,
                    l.descrizionelingua as NomeLingua
                  FROM studygroup sg
                  JOIN esame e ON sg.idesame = e.idesame AND sg.idcdl = e.idcdl
                  JOIN cdl c ON e.idcdl = c.idcdl
                  JOIN lingua l ON sg.idlingua = l.idlingua
                  ORDER BY sg.data DESC, sg.ora DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Cerca studygroup per keyword nel tema
    public function searchStudyGroupsByTema($keyword){
        $query = "SELECT CONCAT(idcdl, '-', idesame, '-', idstudygroup) as ID, tema as Tema, luogo as Luogo, data as Data, ora as Ora, CONCAT(idcdl, '-', idesame) as Esame, idlingua as Lingua, 0 as Partecipanti 
                  FROM studygroup 
                  WHERE tema LIKE ? 
                  ORDER BY data DESC, ora DESC";
        $stmt = $this->db->prepare($query);
        $searchTerm = "%{$keyword}%";
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Conta il numero di studygroup per esame
    public function countStudyGroupsByEsame($idEsame){
        $parts = explode('-', $idEsame);
        if(count($parts) == 2){
            $idcdl = $parts[0];
            $idesame = $parts[1];
            $stmt = $this->db->prepare("SELECT COUNT(*) as totale FROM studygroup WHERE idcdl = ? AND idesame = ?");
            $stmt->bind_param('ii', $idcdl, $idesame);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) as totale FROM studygroup WHERE idesame = ?");
            $stmt->bind_param('i', $idEsame);
        }
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