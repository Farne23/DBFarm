<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idCicloProduttivo = intval($_GET['id']);
    require_once 'bootstrap.php';

    $templateParams["titolo"] = "DBFarm";
    $templateParams["sezione"] = "Terreni";
    $templateParams["js"] = array("js/index.js","js/ciclo.js");
    $templateParams["main-content"] = "dettagli-ciclo-main.php";

    require 'template/base.php';

} else {
    die('ID terreno non valido o mancante.');
}

?>