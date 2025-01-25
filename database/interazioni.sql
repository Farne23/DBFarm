--Operatori -- 

SELECT DISTINCT operatori.* FROM operatori INNER JOIN contratti_impiego ON operatori.CF = contratti_impiego.CF ORDER BY contratti_impiego.data_inizio + contratti_impiego.durata DESC;
SELECT * FROM contratti_impiego WHERE CF = ? ORDER BY contratti_impiego.data_inizio + contratti_impiego.durata DESC;
INSERT INTO operatori (CF, nome, cognome, data_nascita, telefono) VALUES (?,?, ?,?,?);
INSERT INTO contratti_impiego (CF, data_inizio, durata, paga_oraria) VALUES (?,?, ?,?);

--Magazzini--
SELECT DISTINCT edifici.*, IFNULL(SUM(depositi.quantita), 0) as 'giacienza'  FROM edifici LEFT JOIN depositi ON depositi.idEdificio = edifici.idEdificio WHERE  edifici.tipo_magazzino=true GROUP BY edifici.idEdificio;

SELECT prodotti.*, depositi.quantita as 'quantita', depositi.data_ultimo_deposito FROM edifici INNER JOIN depositi on edifici.idEdificio = depositi.idEdificio INNER JOIN prodotti on depositi.idProdotto = prodotti.idProdotto WHERE edifici.idEdificio=? ORDER BY prodotti.tipologia_prodotto
SELECT varieta FROM prodotti WHERE idProdotto = ?
SELECT GROUP_CONCAT(obiettivi_diserbo.nome_infestante SEPARATOR ', ') as 'target' FROM obiettivi_diserbo WHERE idProdotto= ?
SELECT GROUP_CONCAT(nutrizioni.nome_coltura SEPARATOR ', ') as 'target' FROM nutrizioni WHERE idProdotto= ?