<?php
class DatabaseHelper
{
    private $db;
    public function __construct($servername, $username, $password, $dbname, $port)
    {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function getOperatori(){
        $stmt = $this->db->prepare("SELECT * FROM operatori");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getContrattiSottoscritti($CFoperatore){
        $stmt = $this->db->prepare("SELECT * FROM contratti_impiego WHERE CF = ?");
        $stmt->bind_param("s", $CFoperatore);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function getOperatoriListComplete(){
        $operatori = $this->getOperatori();
        foreach($operatori as &$operatore) { 
            $operatore["contratti"]= $this->getContrattiSottoscritti($operatore["CF"]);
        }
        return $operatori;
    }
}

?>