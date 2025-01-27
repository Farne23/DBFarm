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
        $stmt = $this->db->prepare("SELECT prodotti.*, depositi.quantita as 'quantita', depositi.data_ultimo_deposito FROM edifici INNER JOIN depositi on edifici.idEdificio = depositi.idEdificio INNER JOIN prodotti on depositi.idProdotto = prodotti.idProdotto WHERE edifici.idEdificio=? ORDER BY prodotti.tipologia_prodotto");
        $stmt->bind_param("i", $idMagazzino);
        $stmt->execute();
        $resultBase = $stmt->get_result();
        $resultBase = $resultBase->fetch_all(MYSQLI_ASSOC);

        foreach ($resultBase as &$row) {
            $idProdotto = $row['idProdotto'];
            $tipologiaProdotto = $row['tipologia_prodotto'];
            $row['target'] = $this->getProductTargets($idProdotto, $tipologiaProdotto);
        }

        return $resultBase;
    }

    function getProductTargets($idProdotto, $tipologiaProdotto)
    {
        $result = "nessuno";
        switch ($tipologiaProdotto) {
            case 'sementi':
                $stmt = $this->db->prepare("SELECT varieta FROM prodotti WHERE idProdotto = ?");
                $stmt->bind_param("i", $idProdotto);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $result = $row['varieta'];
                break;
            case 'diserbante':
                $stmt = $this->db->prepare("SELECT GROUP_CONCAT(obiettivi_diserbo.nome_infestante SEPARATOR ', ') as 'target' FROM obiettivi_diserbo WHERE idProdotto= ?");
                $stmt->bind_param("i", $idProdotto);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $result = $row['target'];
                break;
            case 'fertilizzante':
                $stmt = $this->db->prepare("SELECT GROUP_CONCAT(nutrizioni.nome_coltura SEPARATOR ', ') as 'target' FROM nutrizioni WHERE idProdotto= ?");
                $stmt->bind_param("i", $idProdotto);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $result = $row['target'];
                break;
        }
        return $result;
    }

    function getListaProdotti()
    {
        $stmt = $this->db->prepare("SELECT DISTINCT prodotti.* FROM prodotti ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function registraNuovoDeposito($magazzino, $prodotto, $quantita)
    {
        $stmt = $this->db->prepare("SELECT 
            SUM(depositi.quantita) + ? > edifici.capacita_magazzino AS 'isFull'
        FROM 
            edifici 
        INNER JOIN 
            depositi ON edifici.idEdificio = depositi.idEdificio 
        WHERE 
            edifici.idEdificio = ? 
        GROUP BY 
            edifici.idEdificio");
        $stmt->bind_param("ii", $quantita, $magazzino);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if (!$row["isFull"]) {
            $stmt = $this->db->prepare("SELECT COUNT(*) > 0 AS 'isPresent'
                FROM depositi
                WHERE depositi.idEdificio = ?
                AND depositi.idProdotto = ?");
            $stmt->bind_param("ii", $magazzino, $prodotto);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row["isPresent"]) {
                $todayDate = date('Y-m-d');
                $stmt = $this->db->prepare("UPDATE depositi 
                            SET quantita = quantita + ?, data_ultimo_deposito = ? 
                            WHERE idEdificio = ? AND idProdotto = ?");
                $stmt->bind_param("issi", $quantita, $todayDate, $magazzino, $prodotto);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $todayDate = date('Y-m-d');
                // Prepare the statement
                $stmt = $this->db->prepare("INSERT INTO depositi (idEdificio, idProdotto, quantita, data_ultimo_deposito) VALUES (?,?,?,?)");
                $stmt->bind_param("iiis", $magazzino, $prodotto, $quantita, $todayDate);
                if ($stmt->execute()) {
                    return true;
                } else {
                    return false;
                }

            }
        } else {
            return false;
        }
    }

    function getMacchinariList()
    {
        $stmt = $this->db->prepare("SELECT macchinari.*, GROUP_CONCAT(CONCAT(nome_caratteristica, ': ', valore) SEPARATOR ', ') AS caratteristiche FROM specifiche_caratteristiche RIGHT JOIN macchinari on specifiche_caratteristiche.idMacchinario = macchinari.idMacchinario GROUP BY macchinari.idMacchinario");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function getMacchinariListFiltered($tipologia, $semovente)
    {
        switch ($semovente) {
            case "semoventeQualsiasi":
                if ($tipologia == "qualsiasi") {
                    return $this->getMacchinariList();
                } else {
                    $stmt = $this->db->prepare("SELECT macchinari.*, GROUP_CONCAT(CONCAT(nome_caratteristica, ': ', valore) SEPARATOR ', ') AS caratteristiche FROM specifiche_caratteristiche RIGHT  JOIN macchinari on specifiche_caratteristiche.idMacchinario = macchinari.idMacchinario WHERE tipologia = ? GROUP BY macchinari.idMacchinario");
                    $stmt->bind_param("s", $tipologia);
                }
                break;
            case "semoventeSi":
                if ($tipologia == "qualsiasi") {
                    $stmt = $this->db->prepare("SELECT macchinari.*, GROUP_CONCAT(CONCAT(nome_caratteristica, ': ', valore) SEPARATOR ', ') AS caratteristiche FROM specifiche_caratteristiche RIGHT  JOIN macchinari on specifiche_caratteristiche.idMacchinario = macchinari.idMacchinario WHERE semovente=true GROUP BY macchinari.idMacchinario");
                } else {
                    $stmt = $this->db->prepare("SELECT macchinari.*, GROUP_CONCAT(CONCAT(nome_caratteristica, ': ', valore) SEPARATOR ', ') AS caratteristiche FROM specifiche_caratteristiche RIGHT  JOIN macchinari on specifiche_caratteristiche.idMacchinario = macchinari.idMacchinario WHERE semovente=true AND tipologia = ? GROUP BY macchinari.idMacchinario");
                    $stmt->bind_param("s", $tipologia);
                }
                break;
            case "semoventeNo":
                if ($tipologia == "qualsiasi") {
                    $stmt = $this->db->prepare("SELECT macchinari.*, GROUP_CONCAT(CONCAT(nome_caratteristica, ': ', valore) SEPARATOR ', ') AS caratteristiche FROM specifiche_caratteristiche RIGHT JOIN macchinari on specifiche_caratteristiche.idMacchinario = macchinari.idMacchinario WHERE semovente=false GROUP BY macchinari.idMacchinario");
                } else {
                    $stmt = $this->db->prepare("SELECT macchinari.*, GROUP_CONCAT(CONCAT(nome_caratteristica, ': ', valore) SEPARATOR ', ') AS caratteristiche FROM specifiche_caratteristiche RIGHT JOIN macchinari on specifiche_caratteristiche.idMacchinario = macchinari.idMacchinario WHERE semovente=false AND tipologia = ? GROUP BY macchinari.idMacchinario");
                    $stmt->bind_param("s", $tipologia);
                }
                break;
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function getCratteristicheDellaTipologia($tipologia)
    {
        $stmt = $this->db->prepare("SELECT nome_caratteristica FROM attinenze_caratteristiche WHERE nome_tipologia = ?");
        $stmt->bind_param("s", $tipologia);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function getListaTipologie()
    {
        $stmt = $this->db->prepare("SELECT nome_tipologia FROM tipologie_macchinari");
        $stmt->execute();
        $resultBase = $stmt->get_result();
        $resultBase = $resultBase->fetch_all(MYSQLI_ASSOC);

        foreach ($resultBase as &$row) {
            $tipologia = $row['nome_tipologia'];
            $row['caratteristiche'] = $this->getCratteristicheDellaTipologia($tipologia);
        }
        return $resultBase;
    }

    public function registraMezzoSemovente($tipologia, $marca, $modello, $costo_orario, $potenza, $telaio, $volume, $targa)
    {
        $stmt = $this->db->prepare("INSERT INTO macchinari (semovente, tipologia, marca, modello, costo_orario, potenza, telaio, volume,targa)
                VALUES (true, ?, ?, ?, ?, ?, ?,?)");
        $stmt->bind_param("ssssss", $tipologia, $marca, $modello, $costo_orario, $potenza, $telaio, $volume, $targa);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        } else {
            return false;
        }
    }

    public function registraAttrezzo($tipologia, $marca, $modello, $costo_orario)
    {
        $stmt = $this->db->prepare("INSERT INTO macchinari (semovente,tipologia, marca, modello, costo_orario)
                VALUES (true,?, ?, ?, ?)");
        $stmt->bind_param("ssss", $tipologia, $marca, $modello, $costo_orario);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        } else {
            return false;
        }
    }

    public function registraNuovoValore($idMacchinario, $specifica, $valore)
    {
        $stmt = $this->db->prepare("SELECT * 
        FROM caratteristiche_macchinari 
        INNER JOIN attinenze_caratteristiche ON caratteristiche_macchinari.nome_caratteristica = attinenze_caratteristiche.nome_caratteristica 
        INNER JOIN macchinari ON macchinari.tipologia = attinenze_caratteristiche.nome_tipologia
        WHERE caratteristiche_macchinari.nome_caratteristica = ?
        AND macchinari.idMacchinario = ?");
        $stmt->bind_param("si", $specifica, $idMacchinario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $stmt = $this->db->prepare("
                INSERT INTO specifiche_caratteristiche (idMacchinario, nome_caratteristica, valore) 
                VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $idMacchinario, $specifica, $valore);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getGranulometrie()
    {
        $stmt = $this->db->prepare("SELECT nome_granulometria FROM granulometrie");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function registraNuovoTerreno($nome, $superficie, $limo, $sabbia, $argilla, $granulometria, $comune, $particella, $sezione)
    {
        $stmt = $this->db->prepare("INSERT INTO terreni (nome, superficie, perc_limo, perc_sabbia, perc_argilla, granulometria) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdddds", $nome, $superficie, $limo, $sabbia, $argilla, $granulometria);
        if ($stmt->execute()) {
            $idTerreno = $this->db->insert_id;
            $stmt = $this->db->prepare("INSERT INTO dati_catastali (idTerreno, comune, particella, sezione) 
                                    VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $idTerreno, $comune, $particella, $sezione);
            return $stmt->execute();
        }
        return false;
    }

    public function getListaTerreniLavorati()
    {
        $stmt = $this->db->prepare("SELECT 
            terreni.idTerreno, 
            terreni.nome, 
            lavorazioni.categoria, 
            cicli_produttivi.coltura_coltivata,
            lavorazioni.idCicloProduttivo,
            IF(lavorazioni.data_fine IS NULL, 'In corso', 'Completata') AS 'stato lavorazione',
            IF(cicli_produttivi.data_fine IS NULL, 'in corso', 'Completato') AS 'stato ciclo produttivo'
        FROM 
            terreni 
        INNER JOIN 
            lavorazioni ON (terreni.idCicloProduttivo = lavorazioni.idCicloProduttivo AND terreni.numero_lavorazione = lavorazioni.numero_lavorazione)
        INNER JOIN 
            cicli_produttivi ON lavorazioni.idCicloProduttivo = cicli_produttivi.idCicloProduttivo;");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getListaTerreniIncolti()
    {
        $stmt = $this->db->prepare("SELECT 
        terreni.nome,
        terreni.idTerreno
        FROM terreni 
        WHERE terreni.idTerreno NOT IN(SELECT 
            terreni.idTerreno
            FROM 
            terreni 
            INNER JOIN 
            lavorazioni ON (terreni.idCicloProduttivo = lavorazioni.idCicloProduttivo AND terreni.numero_lavorazione = lavorazioni.numero_lavorazione)
            INNER JOIN 
            cicli_produttivi ON lavorazioni.idCicloProduttivo = cicli_produttivi.idCicloProduttivo);");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCicliProduttiviDi($id)
    {
        $stmt = $this->db->prepare("SELECT idCicloProduttivo,coltura_coltivata,data_inizio,data_fine,bilancio,IF(ISNULL(costo),'Posseduto','Affittato') as 'possesso' FROM cicli_produttivi WHERE idTerreno = ? ORDER BY data_inizio DESC");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDatiCatsataliDi($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM dati_catastali WHERE idTerreno = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDatiDi($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM terreni WHERE idTerreno = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readyNuovoCiclo($id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) = 0 AS pronto
            FROM cicli_produttivi
            WHERE idTerreno = ? AND data_fine IS NULL;");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getColture()
    {
        $stmt = $this->db->prepare("SELECT nome_coltura, mese_semina, ABS(MONTH(CURDATE()) - mese_semina) as 'differenza' FROM colture ORDER BY differenza ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getInfestanti()
    {
        $stmt = $this->db->prepare("SELECT nome_infestante FROM infestanti");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function registraNuovoCiclo($idTerreno, $coltura, $datainizio, $costo, $proprietario)
    {
        // Query per verificare che la data di inizio non sia inferiore alla data di fine di nessun ciclo esistente
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS count 
            FROM cicli_produttivi 
            WHERE idTerreno = ? AND data_fine IS NOT NULL AND data_fine > ?"
        );
        $stmt->bind_param("is", $idTerreno, $datainizio);
        $stmt->execute();
        $checkResult = $stmt->get_result();
        $row = $checkResult->fetch_assoc();

        if ($row['count'] > 0) {
            return false;
        }

        if ($costo == "" || $proprietario == "") {
            $stmt = $this->db->prepare("INSERT INTO cicli_produttivi (idTerreno, coltura_coltivata, data_inizio) 
                                        VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $idTerreno, $coltura, $datainizio);
        } else {
            $stmt = $this->db->prepare("INSERT INTO cicli_produttivi (idTerreno, coltura_coltivata, data_inizio, costo, proprietario) 
                                        VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issds", $idTerreno, $coltura, $datainizio, $costo, $proprietario);
        }

        return $stmt->execute();
    }

    public function registraNuovaRilevazione($idTerreno, $ph, $umidita, $sostanzaOrganica, $azoto, $infestante)
    {
        $oggi = date('Y-m-d');
        $stmt = $this->db->prepare("INSERT INTO rilevazioni (data, idTerreno, Ph, perc_umidita, perc_sostanzaOrganica, perc_azoto, infestazione) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sidddds", $oggi, $idTerreno, $ph, $umidita, $sostanzaOrganica, $azoto, $infestante);
        return $stmt->execute();
    }

    public function verificaStatoCampo($idTerreno){
        $stmt = $this->db->prepare("SELECT 
            rilevazioni.data, 
            rilevazioni.PH, 
            rilevazioni.perc_umidita, 
            rilevazioni.perc_sostanzaOrganica, 
            rilevazioni.perc_sostanzaOrganica, rilevazioni.perc_azoto, IF(ISNULL(rilevazioni.infestazione),'nessuna',rilevazioni.infestazione) as 'infestazione_rilevata',
            (rilevazioni.perc_azoto < colture.azoto_minimo) as 'azoto_insufficiente_coltura',
            (rilevazioni.perc_sostanzaOrganica < colture.sostanza_organica_minima) as 'so_insufficiente_coltura',
            (rilevazioni.PH < colture.ph_minimo)  as 'ph_insufficiente_coltura',
            (rilevazioni.PH > colture.ph_massimo) as 'ph_eccessivo_coltura',
            (rilevazioni.PH < granulometrie.ph_minimo)  as 'ph_insufficiente_granulometria',
            (rilevazioni.PH > granulometrie.ph_massimo) as 'ph_eccessivo_granulometria',
            (rilevazioni.perc_umidita < granulometrie.umidita_minima)  as 'umidita_insufficiente_granulometria',
            (rilevazioni.perc_umidita > granulometrie.umidita_massima) as 'umidita_eccessiva_granulometria'
            FROM cicli_produttivi 
            INNER JOIN rilevazioni ON rilevazioni.idTerreno = cicli_produttivi.idTerreno 
            INNER JOIN colture ON cicli_produttivi.coltura_coltivata = colture.nome_coltura
            INNER JOIN terreni ON terreni.idTerreno = rilevazioni.idTerreno
            INNER JOIN granulometrie ON terreni.granulometria = granulometrie.nome_granulometria
            WHERE ISNULL(cicli_produttivi.data_fine)
            AND rilevazioni.data = CURRENT_DATE 
            AND cicli_produttivi.idTerreno = ? ");
            $stmt->bind_param("i", $idTerreno);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>