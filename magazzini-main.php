<?php
require_once 'bootstrap.php';
$Magazzini = $dbh->getMagazziniListComplete();
    ?>
    
<section id="listaMagazzini">
    <ul>
        <?php
        echo createListaMagazzini($Magazzini);
        ?>
    </ul>
</section>