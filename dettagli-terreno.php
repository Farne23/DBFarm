<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idTerreno = intval($_GET['id']);
    require_once 'bootstrap.php';

    $templateParams["titolo"] = "DBFarm";
    $templateParams["sezione"] = "Terreni";
    $templateParams["js"] = array("js/index.js","js/terreno.js");
    $templateParams["main-content"] = "dettagli-terreno-main.php";

    require 'template/base.php';

} else {
    die('ID terreno non valido o mancante.');
}

?>