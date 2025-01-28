<?php
require_once 'bootstrap.php';

$cicli = $dbh->getCicliProduttivi();
$silos= $dbh->getSiloListComplete();

$templateParams["titolo"] = "DBFarm";
$templateParams["sezione"] = "Silo";
$templateParams["js"] = array("js/index.js", "js/silo.js");
$templateParams["main-content"] = "silo-main.php";

require 'template/base.php';
?>