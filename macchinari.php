<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "DBFarm";
$templateParams["sezione"] = "Macchinari";
$templateParams["js"] = array("js/index.js","js/macchinari.js");
$templateParams["main-content"] = "macchinari-main.php";

require 'template/base.php';
?>