<?php
require_once '../bootstrap.php';
$Operatori = $dbh->getMagazziniListComplete();
    ?>
<ul>
    <?php
    echo createListaMagazzini($Operatori);
    ?>
</ul>