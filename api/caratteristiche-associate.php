<?php
require_once '../bootstrap.php';

// Ottieni la tipologia dalla richiesta POST
$data = json_decode(file_get_contents('php://input'), true);
$tipologia = $data['tipologia'];

// Ottieni le caratteristiche associate dalla tipologia selezionata
$caratteristiche = $dbh->getCratteristicheDellaTipologia($tipologia);
// Prepara la risposta
$response = [];
if ($caratteristiche) {
    $response['success'] = true;
    $response['caratteristiche'] = $caratteristiche;
} else {
    $response['success'] = false;
}

echo json_encode($response);