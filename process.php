<?php
require_once 'bootstrap.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    switch ($data["type"]) {
        case "newOperatore":
            if ($dbh->registraNuovoOperatore($data['CF'], $data['nome'], $data['cognome'], $data['dataNascita'], $data['telefono'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            break;
        case "newContratto":
            if ($dbh->registraNuovoContratto($data['CF'], $data['data'], $data['durata'], $data['paga'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            break;
        case "newDeposito":
            if ($dbh->registraNuovoDeposito($data['magazzino'], $data['prodotto'], $data['quantita'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            break;
        case "registraMacchinario":
            if ($data['semovente']) {
                $id = $dbh->registraMezzoSemovente(
                    $data['tipologia'],
                    $data['marca'],
                    $data['modello'],
                    $data['costo_orario'],
                    $data['potenza'],
                    $data['telaio'],
                    $data['volume'],
                    $data['targa']
                );

                if ($id) {
                    echo json_encode(['success' => true, 'id' => $id]);
                } else {
                    echo json_encode(['success' => false]);
                }
            } else {
                $id = $dbh->registraAttrezzo(
                    $data['tipologia'],
                    $data['marca'],
                    $data['modello'],
                    $data['costo_orario']
                );

                if ($id) {
                    echo json_encode(['success' => true, 'id' => $id]);
                } else {
                    echo json_encode(['success' => false]);
                }
            }
            break;
        case "specificaValore":
            if ($dbh->registraNuovoValore($data['idMacchinario'], $data['specifica'], $data['valore'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            break;
        case "newTerreno":
            // Verifica se il terreno è stato registrato correttamente
            if ($dbh->registraNuovoTerreno($data['nome'], $data['superficie'], $data['limo'], $data['sabbia'], $data['argilla'], $data['granulometria'], $data['comune'], $data['particella'], $data['sezione'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            break;
    }
} catch (Exception $e) {
    // Gestisci eventuali errori
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>