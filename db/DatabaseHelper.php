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
        $stmt = $this->db->prepare("SELECT idcdl, nomecdl, sede, img, durata FROM cdl ORDER BY sede, nomecdl");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCdlById($id){
        $stmt = $this->db->prepare("SELECT idcdl, nomecdl, sede, img, durata FROM cdl WHERE idcdl = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertCdl($nome, $campus, $img, $durata){
        $stmtMax = $this->db->prepare("SELECT COALESCE(MAX(idcdl), 0) + 1 as nextid FROM cdl");
        $stmtMax->execute();
        $resultMax = $stmtMax->get_result();
        $rowMax = $resultMax->fetch_assoc();
        $idcdl = $rowMax['nextid'];

        $query = "INSERT INTO cdl (idcdl, nomecdl, sede, img, durata) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('isssi', $idcdl, $nome, $campus, $img, $durata);
        $stmt->execute();
        return $idcdl;
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
        $query = "SELECT username, nome, cognome FROM user WHERE username = ? AND password = ? AND amministratore = '1'";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAdminById($id){
        $stmt = $this->db->prepare("SELECT username, cognome, nome FROM user WHERE username = ? AND amministratore = '1'");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getUserById($id){
        $stmt = $this->db->prepare("SELECT username, cognome, nome FROM user WHERE username = ? AND attivo = '1' AND amministratore = '0'");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function checkUserLogin($username, $password){
        $query = "SELECT username, nome, cognome FROM user WHERE username = ? AND password = ?";
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

    public function updateUser($username, $cognome, $nome){
        $query = "UPDATE user SET nome = ?, cognome = ? WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sss', $nome, $cognome, $username);
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
        $stmt = $this->db->prepare("SELECT idcdl, idesame, nomeesame, annoesame, imgesame FROM esame ORDER BY nomeesame");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEsamiByCdl($idCdl){
        $stmt = $this->db->prepare("SELECT idcdl, idesame, nomeesame, annoesame, imgesame FROM esame WHERE idcdl = ? ORDER BY nomeesame");
        $stmt->bind_param('i', $idCdl);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEsameById($idesame){
        $stmt = $this->db->prepare("SELECT idcdl, idesame, nomeesame, annoesame, imgesame FROM esame WHERE idesame = ?");
        $stmt->bind_param('i', $idesame);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
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

    public function deleteEsame($id){
        $query = "DELETE FROM esame WHERE idesame = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    
    public function getAllLingue(){
        $stmt = $this->db->prepare("SELECT idlingua, descrizionelingua FROM lingua ORDER BY descrizionelingua");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getLinguaById($id){
        $stmt = $this->db->prepare("SELECT idlingua, descrizionelingua FROM lingua WHERE idlingua = ?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getAllStudyGroups(){
        $query = "SELECT sg.idcdl, sg.idesame, sg.idstudygroup, sg.tema, sg.luogo, sg.data, sg.ora, 
                  sg.idlingua, 0 as Partecipanti 
                  FROM studygroup sg ORDER BY sg.data DESC, sg.ora DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        
        foreach ($rows as &$row) {
            $countStmt = $this->db->prepare("SELECT COUNT(*) as num FROM adesione WHERE idstudygroup = ?");
            $countStmt->bind_param('i', $row['idstudygroup']);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countRow = $countResult->fetch_assoc();
            $row['Partecipanti'] = $countRow['num'];
        }
        
        return $rows;
    }

    public function getStudyGroupById($id){
        $query = "SELECT sg.idcdl, sg.idesame, sg.idstudygroup, sg.tema, sg.luogo, sg.data, sg.ora, 
                  sg.idlingua, 0 as Partecipanti 
                  FROM studygroup sg WHERE sg.idstudygroup = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        
        if (!empty($rows)) {
            $countStmt = $this->db->prepare("SELECT COUNT(*) as num FROM adesione WHERE idstudygroup = ?");
            $countStmt->bind_param('i', $id);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countRow = $countResult->fetch_assoc();
            $rows[0]['Partecipanti'] = $countRow['num'];
        }
        
        return $rows;
    }

    public function getStudyGroupsByEsame($idEsame){
        $query = "SELECT sg.idcdl, sg.idesame, sg.idstudygroup, sg.tema, sg.luogo, sg.data, sg.ora, 
                  sg.idlingua, 0 as Partecipanti 
                  FROM studygroup sg WHERE sg.idesame = ? ORDER BY sg.data DESC, sg.ora DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idEsame);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        
        foreach ($rows as &$row) {
            $countStmt = $this->db->prepare("SELECT COUNT(*) as num FROM adesione WHERE idstudygroup = ?");
            $countStmt->bind_param('i', $row['idstudygroup']);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countRow = $countResult->fetch_assoc();
            $row['Partecipanti'] = $countRow['num'];
        }
        
        return $rows;
    }

    public function getStudyGroupsByDate($data){
        $query = "SELECT sg.idcdl, sg.idesame, sg.idstudygroup, sg.tema, sg.luogo, sg.data, sg.ora, 
                  sg.idlingua, 0 as Partecipanti 
                  FROM studygroup sg WHERE sg.data = ? ORDER BY sg.ora";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $data);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        
        foreach ($rows as &$row) {
            $countStmt = $this->db->prepare("SELECT COUNT(*) as num FROM adesione WHERE idstudygroup = ?");
            $countStmt->bind_param('i', $row['idstudygroup']);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countRow = $countResult->fetch_assoc();
            $row['Partecipanti'] = $countRow['num'];
        }
        
        return $rows;
    }

    public function getUpcomingStudyGroups($limit = -1){
        $today = date('Y-m-d');
        $query = "SELECT sg.idcdl, sg.idesame, sg.idstudygroup, sg.tema, sg.luogo, sg.data, sg.ora, 
                  sg.idlingua, 0 as Partecipanti 
                  FROM studygroup sg WHERE sg.data >= ? ORDER BY sg.data, sg.ora";
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
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        
        foreach ($rows as &$row) {
            $countStmt = $this->db->prepare("SELECT COUNT(*) as num FROM adesione WHERE idstudygroup = ?");
            $countStmt->bind_param('i', $row['idstudygroup']);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countRow = $countResult->fetch_assoc();
            $row['Partecipanti'] = $countRow['num'];
        }
        
        return $rows;
    }

    public function insertStudyGroup($tema, $luogo, $data, $ora, $idEsame, $idLingua, $partecipanti){
        
        $stmtMax = $this->db->prepare("SELECT COALESCE(MAX(idstudygroup), 0) + 1 as nextid FROM studygroup");
        $stmtMax->execute();
        $resultMax = $stmtMax->get_result();
        $rowMax = $resultMax->fetch_assoc();
        $idstudygroup = $rowMax['nextid'];
        
        $stmtCdl = $this->db->prepare("SELECT idcdl FROM esame WHERE idesame = ?");
        $stmtCdl->bind_param('i', $idEsame);
        $stmtCdl->execute();
        $resultCdl = $stmtCdl->get_result();
        $rowCdl = $resultCdl->fetch_assoc();
        $idcdl = $rowCdl ? $rowCdl['idcdl'] : 1;
        
        $query = "INSERT INTO studygroup (idcdl, idesame, idstudygroup, idlingua, tema, luogo, dettaglioluogo, data, ora, amministratoresg) VALUES (?, ?, ?, ?, ?, ?, '', ?, ?, '')";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iiisssss', $idcdl, $idEsame, $idstudygroup, $idLingua, $tema, $luogo, $data, $ora);
        $stmt->execute();
        return $idstudygroup;
    }

    public function updateStudyGroup($id, $tema, $luogo, $data, $ora, $idEsame, $idLingua, $partecipanti){
        $query = "UPDATE studygroup SET tema = ?, luogo = ?, data = ?, ora = ?, idesame = ?, idlingua = ? WHERE idstudygroup = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssisi', $tema, $luogo, $data, $ora, $idEsame, $idLingua, $id);
        return $stmt->execute();
    }

    public function updateStudyGroupPartecipanti($id, $partecipanti){
        return true;
    }

    public function deleteStudyGroup($id){
        $stmtAdesioni = $this->db->prepare("DELETE FROM adesione WHERE idstudygroup = ?");
        $stmtAdesioni->bind_param('i', $id);
        $stmtAdesioni->execute();
        
        $query = "DELETE FROM studygroup WHERE idstudygroup = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    
    public function blockUser($id){
        $query = "UPDATE user SET attivo = '0' WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }

    public function unblockUser($id){
        $query = "UPDATE user SET attivo = '1' WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }

    public function makeUserAdmin($id){
        $query = "UPDATE user SET amministratore = '1' WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }

    public function removeUserAdmin($id){
        $query = "UPDATE user SET amministratore = '0' WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }
    
    public function getNormalUsers(){
        $stmt = $this->db->prepare("SELECT username, nome, cognome, 
                                      CASE WHEN attivo='0' THEN 1 ELSE 0 END as Bloccato, 
                                      CASE WHEN amministratore='1' THEN 1 ELSE 0 END as IsAdmin 
                                      FROM user WHERE attivo = '1' AND amministratore = '0' ORDER BY cognome, nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getBlockedUsers(){
        $stmt = $this->db->prepare("SELECT username, nome, cognome, 
                                      CASE WHEN attivo='0' THEN 1 ELSE 0 END as Bloccato, 
                                      CASE WHEN amministratore='1' THEN 1 ELSE 0 END as IsAdmin 
                                      FROM user WHERE attivo = '0' ORDER BY cognome, nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAdminUsers(){
        $stmt = $this->db->prepare("SELECT username, nome, cognome, 
                                      CASE WHEN attivo='0' THEN 1 ELSE 0 END as Bloccato, 
                                      CASE WHEN amministratore='1' THEN 1 ELSE 0 END as IsAdmin 
                                      FROM user WHERE amministratore = '1' ORDER BY cognome, nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllUsers(){
        $stmt = $this->db->prepare("SELECT username, nome, cognome, 
                                      CASE WHEN attivo='0' THEN 1 ELSE 0 END as Bloccato, 
                                      CASE WHEN amministratore='1' THEN 1 ELSE 0 END as IsAdmin 
                                      FROM user ORDER BY cognome, nome");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getStudyGroupsWithDetails(){
        $query = "SELECT 
                    sg.idstudygroup,
                    sg.idcdl,
                    sg.idesame,
                    sg.tema, 
                    sg.luogo, 
                    sg.data, 
                    sg.ora, 
                    0 as Partecipanti,
                    e.nomeesame as NomeEsame,
                    e.imgesame as ImgEsame,
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
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        
        foreach ($rows as &$row) {
            $countStmt = $this->db->prepare("SELECT COUNT(*) as num FROM adesione WHERE idstudygroup = ?");
            $countStmt->bind_param('i', $row['idstudygroup']);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countRow = $countResult->fetch_assoc();
            $row['Partecipanti'] = $countRow['num'];
        }
        
        return $rows;
    }

    public function searchStudyGroupsByTema($keyword){
        $query = "SELECT sg.idcdl, sg.idesame, sg.idstudygroup, sg.tema, sg.luogo, sg.data, sg.ora, 
                  sg.idlingua, 0 as Partecipanti 
                  FROM studygroup sg 
                  WHERE sg.tema LIKE ? 
                  ORDER BY sg.data DESC, sg.ora DESC";
        $stmt = $this->db->prepare($query);
        $searchTerm = "%{$keyword}%";
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        
        foreach ($rows as &$row) {
            $countStmt = $this->db->prepare("SELECT COUNT(*) as num FROM adesione WHERE idstudygroup = ?");
            $countStmt->bind_param('i', $row['idstudygroup']);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countRow = $countResult->fetch_assoc();
            $row['Partecipanti'] = $countRow['num'];
        }
        
        return $rows;
    }

    public function countStudyGroupsByEsame($idEsame){
        $stmt = $this->db->prepare("SELECT COUNT(*) as totale FROM studygroup WHERE idesame = ?");
        $stmt->bind_param('i', $idEsame);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['totale'];
    }
    
    public function closeConnection(){
        $this->db->close();
    }

    function uploadImage($path, $image){
        $imageName = basename($image["name"]);
        $fullPath = $path.$imageName;
        
        $maxKB = 500;
        $acceptedExtensions = array("jpg", "jpeg", "png", "gif");
        $result = 0;
        $msg = "";
        //Controllo se immagine è veramente un'immagine
        $imageSize = getimagesize($image["tmp_name"]);
        if($imageSize === false) {
            $msg .= "File caricato non è un'immagine! ";
        }
        //Controllo dimensione dell'immagine < 500KB
        if ($image["size"] > $maxKB * 1024) {
            $msg .= "File caricato pesa troppo! Dimensione massima è $maxKB KB. ";
        }

        //Controllo estensione del file
        $imageFileType = strtolower(pathinfo($fullPath,PATHINFO_EXTENSION));
        if(!in_array($imageFileType, $acceptedExtensions)){
            $msg .= "Accettate solo le seguenti estensioni: ".implode(",", $acceptedExtensions);
        }

        //Controllo se esiste file con stesso nome ed eventualmente lo rinomino
        if (file_exists($fullPath)) {
            $i = 1;
            do{
                $i++;
                $imageName = pathinfo(basename($image["name"]), PATHINFO_FILENAME)."_$i.".$imageFileType;
            }
            while(file_exists($path.$imageName));
            $fullPath = $path.$imageName;
        }

        //Se non ci sono errori, sposto il file dalla posizione temporanea alla cartella di destinazione
        if(strlen($msg)==0){
            if(!move_uploaded_file($image["tmp_name"], $fullPath)){
                $msg.= "Errore nel caricamento dell'immagine.";
            }
            else{
                $result = 1;
                $msg = $imageName;
            }
        }
        return array($result, $msg);
    }
    
}
?>