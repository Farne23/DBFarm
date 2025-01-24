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


    function getMagazziniListComplete()
    {
        $stmt = $this->db->prepare("SELECT DISTINCT edifici.*, IFNULL(SUM(depositi.quantita), 0) as 'giacienza'  FROM edifici LEFT JOIN depositi ON depositi.idEdificio = edifici.idEdificio WHERE  edifici.tipo_magazzino=true GROUP BY edifici.idEdificio");
        $stmt->execute();
        $result = $stmt->get_result();
        $resultComplete = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($resultComplete as &$edificio) {
            $edificio["content"] = $this->getMagazzinoContent($edificio["idEdificio"]);
        }
        return $resultComplete;
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

    function registraNuovoContratto($CF, $data, $durata, $paga)
    {
        $stmt = $this->db->prepare("INSERT INTO contratti_impiego (CF, data_inizio, durata, paga_oraria) VALUES (?,?, ?,?)");
        $stmt->bind_param("ssss", $CF, $data, $durata, $paga);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function getMagazzinoContent($idMagazzino)
    {
        $stmt = $this->db->prepare("SELECT prodotti.marca, prodotti.nome, prodotti.tipologia_prodotto, depositi.quantita as 'quantita', depositi.data_ultimo_deposito FROM edifici INNER JOIN depositi on edifici.idEdificio = depositi.idEdificio INNER JOIN prodotti on depositi.idProdotto = prodotti.idProdotto WHERE edifici.idEdificio=? ORDER BY prodotti.tipologia_prodotto");
        $stmt->bind_param("i", $idMagazzino);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>