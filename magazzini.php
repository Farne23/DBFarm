<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "DBFarm";
$templateParams["sezione"] = "Magazzini";
$templateParams["js"] = array("js/index.js","js/magazzini.js");
$templateParams["main-content"] = "magazzini-main.php";

require 'template/base.php';
?>