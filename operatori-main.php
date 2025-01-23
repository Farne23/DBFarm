<?php
require_once 'bootstrap.php';
$Operatori = $dbh->getOperatoriListComplete()
    ?>
<section>
    <ul>
        <?php
            echo createListaOperatori($Operatori);
        ?>
    </ul>
</section>