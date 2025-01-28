<?php
if (isset($_GET['id']) && isset($_GET['numero'])) {
    require_once 'bootstrap.php';
    $idCicloProduttivo = intval($_GET['id']);
    $numero = intval($_GET['numero']);
    $turni = $dbh->getTurniLavorazioni($idCicloProduttivo,$numero);
    $templateParams["titolo"] = "DBFarm";
    $templateParams["sezione"] = "Terreni";
    $templateParams["js"] = array("js/index.js",);
    $templateParams["main-content"] = "dettagli-lavorazioni-main.php";

    require 'template/base.php';

} else {
    die('ID terreno non valido o mancante.');
}

?>