<?php
require_once 'bootstrap.php';
$Operatori = $dbh->getMagazziniListComplete()
    ?>
    
<section id="listaOperatori">
    <ul>
        <?php
        echo createListaOperatori($Operatori);
        ?>
    </ul>
</section>