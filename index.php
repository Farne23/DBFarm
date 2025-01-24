<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "DBFarm";
$templateParams["js"] = array("js/index.js");
$templateParams["main-content"] = "Nulla";

//Index template

require 'template/base.php';
?>