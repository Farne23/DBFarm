<?php
require_once '../bootstrap.php';
$Operatori = $dbh->getOperatoriListComplete();
    ?>
<ul>
    <?php
    echo createListaOperatori($Operatori);
    ?>
</ul>