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
    PRIMARY KEY (CF, data_inizio), 
    FOREIGN KEY (CF) REFERENCES operatori(CF),
    CONSTRAINT no_overlapping_contracts CHECK (
        NOT EXISTS (
            SELECT 1
            FROM contratti_impiego c1
            JOIN contratti_impiego c2
            ON c1.CF = c2.CF AND c1.data_inizio < DATE_ADD(c2.data_inizio, INTERVAL c2.durata DAY)
            AND DATE_ADD(c1.data_inizio, INTERVAL c1.durata DAY) > c2.data_inizio
        )
    )
);

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
