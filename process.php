<?php
require_once 'bootstrap.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    if($data["type"]=="newOperatore"){
        if($dbh->registraNuovoOperatore($data['CF'], $data['nome'], $data['cognome'], $data['dataNascita'], $data['telefono'])){
            echo json_encode(['success' => true]);
        }else{
            echo json_encode(['success' => false]);
        }
    }else{
        if($dbh->registraNuovoContratto($data['CF'], $data['data'], $data['durata'], $data['paga'])){
            echo json_encode(['success' => true]);
        }else{
            echo json_encode(['success' => false]);
        }
    }
} catch (Exception $e) {
    // Gestisci eventuali errori
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>