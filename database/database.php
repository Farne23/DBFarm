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

    public function getOperatori()
    {
        $stmt = $this->db->prepare("SELECT DISTINCT operatori.* FROM operatori LEFT JOIN contratti_impiego ON operatori.CF = contratti_impiego.CF ORDER BY contratti_impiego.data_inizio + contratti_impiego.durata DESC ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getContrattiSottoscritti($CFoperatore)
    {
        $stmt = $this->db->prepare("SELECT * FROM contratti_impiego WHERE CF = ? ORDER BY contratti_impiego.data_inizio + contratti_impiego.durata DESC");
        $stmt->bind_param("s", $CFoperatore);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function getOperatoriListComplete()
    {
        $operatori = $this->getOperatori();
        foreach ($operatori as &$operatore) {
            $operatore["contratti"] = $this->getContrattiSottoscritti($operatore["CF"]);
        }
        return $operatori;
    }

    function registraNuovoOperatore($CF, $nome, $cognome, $dataNascita, $telefono)
    {
        $stmt = $this->db->prepare("INSERT INTO operatori (CF, nome, cognome, data_nascita, telefono) VALUES (?,?, ?,?,?)");
        $stmt->bind_param("sssss", $CF, $nome, $cognome, $dataNascita, $telefono);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

?>