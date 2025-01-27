<?php
include_once 'bootstrap.php';
$lavorazioni = $dbh->getLavorazioni($idCicloProduttivo);
$ciclo = $dbh->getCiclo($idCicloProduttivo)[0];
if (count($lavorazioni) > 0):
    $ultima_lavorazione = $lavorazioni[0];
    ?>

    <input type="hidden" id="idCicloProduttivo" value="<?php if (isset($ultima_lavorazione['idCicloProduttivo']))
        echo $ultima_lavorazione['idCicloProduttivo'] ?>">
        <input type="hidden" id="numero" value="<?php if (isset($ultima_lavorazione['numero_lavorazione']))
        echo $ultima_lavorazione['numero_lavorazione'] ?>">

    <?php if (!isset($ultima_lavorazione['data_fine'])) { ?>

        <h3>Lavorazione in corso</h3>
        <div class="terreno-header orange-on-white">
            <strong>[<?= htmlspecialchars($ultima_lavorazione['numero_lavorazione']) ?>]
                <?= htmlspecialchars($ultima_lavorazione['categoria']) ?></strong>
        </div>
        <div class="terreno-details">
            <span>Data inizio: <?= htmlspecialchars($ultima_lavorazione['data_inizio']) ?> </span>
        </div>
        <div class="lavorazione-pulsanti">
            <input type="submit" id="concludi" class="orange-on-white" value="Concludi lavorazione" />
            <input type="submit" id="concludi" class="concludi-lavorazione orange-on-white" value="Concludi lavorazione" />
        </div>
    <?php } else { ?>
        <?php if (isset($ciclo['data_fine'])) { ?>
            <h3>Ciclo produttivo concluso</h3>
        <?php } else { ?>
            <h3>Lavorazione in corso</h3>
            <div class="terreno-details">
                <span>Nessuna lavorazione in corso</span>
            </div>
            <div class="lavorazione-pulsanti">
                <input type="submit" id="concludi" class="orange-on-white concludi-ciclo" value="Concludi ciclo produttivo" />
            </div>
        <?php } ?>
    <?php } ?>

    <h3>Tutte le lavorazioni</h3>
    <ul class="terreni-list">
        <?php foreach ($lavorazioni as $lavorazione): ?>
            <li>
                <div class="terreno-header <?php if (isset($lavorazione['data_fine'])) {
                    echo "orange-on-white";
                } else {
                    echo "white-on-orange";
                } ?>">
                    <strong>[<?= htmlspecialchars($lavorazione['numero_lavorazione']) ?>]
                        <?= htmlspecialchars($lavorazione['categoria']) ?></strong>
                </div>
                <div class="terreno-details">
                    <span>Data inizio: <?= htmlspecialchars($lavorazione['data_inizio']) ?> </span>
                    <?php if (isset($lavorazione['data_fine'])) { ?>
                        <span>Data fine: <?= htmlspecialchars($lavorazione['data_fine']) ?> </span>
                    <?php } ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>