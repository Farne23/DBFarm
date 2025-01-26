<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "DBFarm";
$templateParams["sezione"] = "Terreni";
$templateParams["js"] = array("js/index.js","js/terreni.js");
$templateParams["main-content"] = "terreni-main.php";

require 'template/base.php';
?>