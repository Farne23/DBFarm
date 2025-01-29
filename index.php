<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "DBFarm";
$templateParams["js"] = array("js/index.js");
$templateParams["main-content"] = "index-main.php";

//Index template

require 'template/base.php';
?>