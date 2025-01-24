<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "DBFarm";
$templateParams["js"] = array("js/index.js","js/operatori.js");
$templateParams["main-content"] = "operatori-main.php";

//Index template

require 'template/base.php';
?>