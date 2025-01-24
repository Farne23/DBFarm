DROP DATABASE DBFarm;
-- Creazione del database
CREATE DATABASE DBFarm;
USE DBFarm;

-- Creazione della tabella operatori
CREATE TABLE operatori (
    CF CHAR(16) PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    data_nascita DATE NOT NULL,
    telefono VARCHAR(15) NOT NULL
);

-- Creazione della tabella contratti_impiego, con controllo sulla non sovrapposizione dei contratti
CREATE TABLE contratti_impiego (
    CF CHAR(16), 
    data_inizio DATE NOT NULL,
    durata INT NOT NULL,
    paga_oraria DECIMAL(10, 2) NOT NULL,
    UNIQUE(CF, data_inizio),
    PRIMARY KEY (CF, data_inizio), 
    FOREIGN KEY (CF) REFERENCES operatori(CF)
);

DELIMITER $$

CREATE TRIGGER check_contract_overlap
BEFORE INSERT ON contratti_impiego
FOR EACH ROW
BEGIN
    DECLARE conflict_count INT;
    SELECT COUNT(*) INTO conflict_count
    FROM contratti_impiego
    WHERE NEW.CF = CF
      AND NEW.data_inizio < DATE_ADD(data_inizio, INTERVAL durata DAY)
      AND DATE_ADD(NEW.data_inizio, INTERVAL NEW.durata DAY) > data_inizio;
    IF conflict_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Sovrapposizione di contratti per lo stesso operatore.';
    END IF;
END;
$$

DELIMITER ;

-- Inserimento dati nella tabella operatori
INSERT INTO operatori (CF, nome, cognome, data_nascita, telefono) VALUES
('RSSMRA80A01H501Z', 'Mario', 'Rossi', '1980-01-01', '3331234567'),
('VRDLGI85B01F205K', 'Luigi', 'Verdi', '1985-02-01', '3337654321'),
('BNCLRA90C15M109N', 'Chiara', 'Bianchi', '1990-03-15', '3381234567'),
('FNTMRC95D10L222P', 'Marco', 'Fontana', '1995-04-10', '3312345678'),
('GLLFNC99E25H333Q', 'Francesca', 'Galli', '1999-05-25', '3208765432'),
('PLLMNC83F01G444R', 'Monica', 'Pallini', '1983-06-01', '3289876543');

-- Inserimento dati nella tabella contratti_impiego
INSERT INTO contratti_impiego (CF, data_inizio, durata, paga_oraria) VALUES
-- Contratti per Mario Rossi
('RSSMRA80A01H501Z', '2024-01-01', 90, 15.50),
('RSSMRA80A01H501Z', '2024-04-01', 120, 16.00),
-- Contratti per Luigi Verdi
('VRDLGI85B01F205K', '2024-02-01', 60, 14.00),
('VRDLGI85B01F205K', '2024-05-01', 90, 15.00),
-- Contratti per Chiara Bianchi
('BNCLRA90C15M109N', '2024-03-15', 180, 18.00),
-- Contratti per Marco Fontana
('FNTMRC95D10L222P', '2024-05-10', 120, 17.50),
-- Contratti per Francesca Galli
('GLLFNC99E25H333Q', '2024-06-01', 90, 16.50),
-- Contratti per Monica Pallini
('PLLMNC83F01G444R', '2024-07-01', 180, 19.00),
('PLLMNC83F01G444R', '2025-01-01', 90, 19.50);

-- Inserimento edifici
CREATE TABLE edifici (
    idEdificio INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    via VARCHAR(150) NOT NULL,
    citta VARCHAR(100) NOT NULL,
    provincia CHAR(2) NOT NULL,
    tipo_silo BOOLEAN NOT NULL, -- Indica se l'edificio è un silo
    tipo_magazzino BOOLEAN NOT NULL, -- Indica se l'edificio è un magazzino
    capacita_silo INT DEFAULT NULL, -- Capacità del silo (se applicabile)
    capacita_magazzino INT DEFAULT NULL -- Capacità del magazzino (se applicabile)
);

INSERT INTO edifici (nome, via, citta, provincia, tipo_silo, tipo_magazzino, capacita_silo, capacita_magazzino) VALUES
('Edificio A', 'Via Roma 10', 'Rimini', 'RN', TRUE, FALSE, 1000, NULL), 
('Edificio B', 'Via Milano 20', 'VIlla verucchio', 'RN', FALSE, TRUE, NULL, 2000),
('Edificio C', 'Via Torino 30', 'Santarcangelo', 'RN', TRUE, TRUE, 1500, 3000), 
('Edificio D', 'Via Torino 30', 'Santarcangelo', 'RN', TRUE, TRUE, 1500, 3000); 

-- Creazione della tabella colture
CREATE TABLE colture (
    nome_coltura VARCHAR(100) PRIMARY KEY,
    descrizione TEXT NOT NULL,
    mese_raccolta TINYINT NOT NULL,
    mese_semina TINYINT NOT NULL,
    prezzo DECIMAL(10, 2) NOT NULL,
    azoto_minimo DECIMAL(5, 2) NOT NULL,
    sostanza_organica_minima DECIMAL(5, 2) NOT NULL,
    ph_minimo DECIMAL(3, 2) NOT NULL,
    ph_massimo DECIMAL(3, 2) NOT NULL
);

-- Creazione della tabella prodotti
CREATE TABLE prodotti (
    idProdotto INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    marca VARCHAR(100) NOT NULL,
    costo DECIMAL(10, 2) NOT NULL,
    tipologia_prodotto ENUM('diserbante', 'sementi', 'fertilizzante') NOT NULL,
    varieta VARCHAR(100), -- FK su colture, ma è opzionale
    FOREIGN KEY (varieta) REFERENCES colture(nome_coltura)
);

-- Creazione della tabella infestanti
CREATE TABLE infestanti (
    nome_infestante VARCHAR(100) PRIMARY KEY,
    descrizione TEXT NOT NULL
);

-- Creazione della tabella obiettivi_diserbo
CREATE TABLE obiettivi_diserbo (
    nome_infestante VARCHAR(100),
    idProdotto INT,
    PRIMARY KEY (nome_infestante, idProdotto),
    FOREIGN KEY (nome_infestante) REFERENCES infestanti(nome_infestante),
    FOREIGN KEY (idProdotto) REFERENCES prodotti(idProdotto)
);

-- Creazione della tabella depositi
CREATE TABLE depositi (
    idProdotto INT,
    idEdificio INT,
    data_ultimo_deposito DATE NOT NULL,
    quantita INT NOT NULL,
    UNIQUE(idProdotto, idEdificio),
    FOREIGN KEY (idProdotto) REFERENCES prodotti(idProdotto),
    FOREIGN KEY (idEdificio) REFERENCES edifici(idEdificio)
);

-- Creazione della tabella nutrizioni
CREATE TABLE nutrizioni (
    nome_coltura VARCHAR(100),
    idProdotto INT,
    PRIMARY KEY (nome_coltura, idProdotto),
    FOREIGN KEY (nome_coltura) REFERENCES colture(nome_coltura),
    FOREIGN KEY (idProdotto) REFERENCES prodotti(idProdotto)
);

-- Inserimento dati nella tabella colture
INSERT INTO colture (nome_coltura, descrizione, mese_raccolta, mese_semina, prezzo, azoto_minimo, sostanza_organica_minima, ph_minimo, ph_massimo)
VALUES 
    ('Frumento', 'Cereale utilizzato per la produzione di pane e pasta.', 7, 10, 1.20, 2.5, 1.0, 6.0, 7.5),
    ('Mais', 'Cereale ampiamente coltivato per alimentazione umana e animale.', 9, 4, 0.80, 2.0, 1.5, 5.5, 7.0),
    ('Soia', 'Leguminosa ricca di proteine, utilizzata anche per olio.', 10, 5, 1.50, 3.0, 2.0, 6.5, 7.5),
    ('Riso', 'Coltura tipica di aree umide e irrigate.', 9, 4, 2.00, 1.8, 0.8, 5.0, 6.5),
    ('Girasole', 'Coltura oleaginosa per la produzione di olio di girasole.', 8, 3, 1.10, 1.5, 1.0, 6.0, 8.0),
    ('Orzo', 'Cereale impiegato nella produzione di birra e alimenti.', 7, 10, 1.00, 1.8, 1.2, 5.5, 7.0),
    ('Patata', 'Coltura tuberosa per consumo alimentare diretto.', 9, 4, 0.60, 0.5, 0.3, 5.0, 6.0),
    ('Lenticchie', ' Deliziose piantine proteiche', 9, 4, 0.60, 0.5, 0.3, 5.0, 6.0),
    ('Barbabietola', 'Coltura zuccherina per la produzione di zucchero.', 10, 4, 1.30, 2.5, 1.8, 6.5, 7.5);

-- Inserimento dati nella tabella infestanti
INSERT INTO infestanti (nome_infestante, descrizione)
VALUES 
    ('Erba Medica', 'Infestante comune nelle coltivazioni di grano e mais, cresce rapidamente e può sopprimere le colture coltivate.'),
    ('Cipero', 'Infestante che cresce in terreni sabbiosi e umidi, che compete con le colture di riso e mais.'),
    ('Crescione', 'Infestante che cresce rapidamente in terreni umidi e fertili, difficile da estirpare manualmente.'),
    ('Avena Selvatica', 'Infestante che cresce in terreni agricoli e da pascolo, con fusto rigido e ramificato.'),
    ('Gramigna', 'Erba perenne che invade terreni agricoli e prati, con radici profonde e difficili da eliminare.'),
    ('Cardo', 'Infestante spinoso, cresce soprattutto nei terreni poco coltivati o incolti, molto difficile da rimuovere.'),
    ('Amaranto', 'Infestante che cresce rapidamente e può coprire grandi aree, nota per la sua resistenza ai diserbanti.'),
    ('Centocchio', 'Infestante comune nei terreni agricoli, cresce velocemente e produce numerosi semi.'),
    ('Ortiche', 'Pianta infestante che cresce in terreni ricchi di azoto, spesso nelle aree di giardini o pascoli.'),
    ('Stellaria', 'Infestante che cresce in terreni freschi e umidi, diffusa soprattutto nelle colture di ortaggi.'),
    ('Equiseto', 'Infestante con fusto legnoso, cresce in terreni umidi, ha una crescita rapida e può soffocare le colture.'),
    ('Tiglio', 'Infestante che cresce in terreni fertili, con radici profonde che competono con le colture per acqua e nutrienti.'),
    ('Papavero', 'Infestante che cresce nelle coltivazioni di ortaggi e in terreni poco curati, nota per la sua resistenza ai diserbanti.'),
    ('Malva', 'Erba infestante che cresce in terreni umidi e ben drenati, con foglie grandi e fiori colorati.'),
    ('Germoglio', 'Infestante che compete con le colture per la luce solare, diffusa nei campi di grano e mais.'),
    ('Ortica Magnifica', 'Infestante che cresce in terreni fertili e umidi, le sue foglie pungono al contatto.'),
    ('Dente di Leone', 'Infestante perenne che cresce in prati, giardini e nei terreni agricoli, con radici molto robuste.'),
    ('Fiori di Campo', 'Infestante che cresce in terreni ricchi di nutrienti, difficile da controllare senza intervento chimico.'),
    ('Trifoglio', 'Infestante che cresce nelle coltivazioni di cereali e in prati, resistente e molto diffusa.'),
    ('Saponaria', 'Infestante che cresce in terreni poco curati, in grado di riprodursi velocemente grazie ai suoi semi.'),
    ('Veronica', 'Infestante che cresce in terreni argillosi e umidi, resiste a condizioni di crescita difficili.'),
    ('Menta', 'Infestante che cresce in terreni freschi e umidi, che può invadere facilmente le coltivazioni di ortaggi.'),
    ('Boldo', 'Infestante che cresce in terreni sabbiosi e ricchi di materia organica, difficile da controllare.'),
    ('Tarassaco', 'Infestante che cresce nei giardini e nei prati, molto comune e resistente, con radici profonde.'),
    ('Erba Coda di Cavallo', 'Infestante che cresce in terreni umidi, con radici rizomatose molto difficili da estirpare.'),
    ('Morbida', 'Infestante che cresce in terreni freschi, con foglie morbide ma forti, difficile da rimuovere.'),
    ('Basilico Selvatico', 'Infestante che cresce in terreni ricchi di azoto, comune in campi agricoli e giardini.'),
    ('Rooibos', 'Infestante che cresce in terreni poveri, con un forte sistema di radici che rende difficile la rimozione.'),
    ('Timo', 'Infestante che cresce in terreni calcarei, produce una quantità elevata di semi che si diffondono facilmente.'),
    ('Luppolo', 'Infestante che cresce rapidamente in terreni agricoli e non trattati, difficile da controllare senza interventi chimici.');

-- Inserimento dati nella tabella prodotti
INSERT INTO prodotti (nome, marca, costo, tipologia_prodotto, varieta)
VALUES
    -- Diserbanti
    ('Diserbante Rapido', 'AgriChem', 15.99, 'diserbante', NULL),
    ('Diserbante Totale', 'GreenKill', 22.50, 'diserbante', NULL),
    ('Diserbante Selectivo', 'CropSafe', 18.75, 'diserbante', NULL),
    ('Erbacide Forte', 'BioControl', 20.30, 'diserbante', NULL),
    ('ErbaStop', 'FieldMaster', 25.40, 'diserbante', NULL),
    
    -- Sementi
    ('Seme Mais Premium', 'AgriSeed', 12.50, 'sementi', 'Mais'),
    ('Seme Grano Oro', 'FarmPlus', 10.75, 'sementi', 'Frumento'),
    ('Seme Soia Bio', 'EcoGrow', 14.20, 'sementi', 'Soia'),
    ('Seme Orzo Vigor', 'SeedForce', 11.95, 'sementi', 'Orzo'),
    ('Seme Riso Alta Qualità', 'RiceMaster', 16.80, 'sementi', 'Riso'),
    ('Seme Lenticchie Bio', 'HealthyField', 13.50, 'sementi', 'Lenticchie'),
    
    -- Fertilizzanti
    ('Fertilizzante AzotoPlus', 'FertGreen', 30.00, 'fertilizzante', NULL),
    ('Fertilizzante FosfoGrow', 'NutriCrop', 28.40, 'fertilizzante', NULL),
    ('Fertilizzante Completo', 'CropBoost', 35.00, 'fertilizzante', NULL),
    ('Fertilizzante Naturale', 'EcoNutrients', 32.50, 'fertilizzante', NULL),
    ('Fertilizzante Rapido', 'QuickGrow', 26.90, 'fertilizzante', NULL),
    ('Fertilizzante Lenta Cessione', 'SlowBoost', 40.00, 'fertilizzante', NULL),
    ('Fertilizzante BioMix', 'GreenFusion', 45.00, 'fertilizzante', NULL);

-- Inserimento dati nella tabella obiettivi_diserbo
INSERT INTO obiettivi_diserbo (nome_infestante, idProdotto)
VALUES
    -- Diserbante Rapido (idProdotto = 1)
    ('Erba Medica', 1),
    ('Cipero', 1),
    -- Diserbante Totale (idProdotto = 2)
    ('Crescione', 2),
    ('Avena Selvatica', 2),
    -- Diserbante Selectivo (idProdotto = 3)
    ('Gramigna', 3),
    ('Cardo', 3),
    -- Erbacide Forte (idProdotto = 4)
    ('Amaranto', 4),
    ('Centocchio', 4),
    -- ErbaStop (idProdotto = 5)
    ('Ortiche', 5),
    ('Stellaria', 5);

-- Inserimento dati nella tabella depositi
INSERT INTO depositi (idProdotto, idEdificio, data_ultimo_deposito, quantita)
VALUES
    (1, 1, '2024-01-15', 100),
    (2, 1, '2024-02-10', 200),
    (3, 1, '2024-03-05', 150),
    (1, 2, '2024-01-15', 100),
    (2, 2, '2024-02-10', 200),
    (3, 2, '2024-03-05', 150),
    (4, 2, '2024-03-15', 180),
    (5, 2, '2024-04-01', 120),
    (6, 2, '2024-04-20', 300),
    (1, 3, '2024-02-10', 90),
    (3, 3, '2024-02-25', 250),
    (4, 3, '2024-03-12', 130),
    (7, 3, '2024-03-25', 170),
    (8, 3, '2024-04-05', 200),
    (9, 3, '2024-04-15', 50),
    (2, 4, '2024-01-20', 110),
    (4, 4, '2024-02-10', 190),
    (6, 4, '2024-03-01', 140),
    (7, 4, '2024-03-22', 300),
    (8, 4, '2024-04-02', 260),
    (10, 4, '2024-04-20', 80),
    (11, 2, '2024-05-01', 210),
    (12, 2, '2024-05-10', 120),
    (13, 3, '2024-05-15', 180),
    (14, 3, '2024-05-20', 150),
    (15, 4, '2024-06-01', 300),
    (16, 4, '2024-06-10', 220),
    (17, 2, '2024-07-01', 330),
    (18, 3, '2024-07-15', 270);

-- Inserimento dati nella tabella nutrizioni
INSERT INTO nutrizioni (nome_coltura, idProdotto)
VALUES 
    ('Barbabietola', 12),
    ('Mais', 13),
    ('Frumento', 13),
    ('Soia', 14),
    ('Orzo', 14),
    ('Riso', 15),
    ('Lenticchie', 15),
    ('Mais', 16),
    ('Frumento', 16),
    ('Soia', 16),
    ('Orzo', 17),
    ('Riso', 17),
    ('Lenticchie', 18),
    ('Mais', 18),
    ('Frumento', 18);
