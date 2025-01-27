<?php
include_once 'bootstrap.php';
$cicliProduttivi = $dbh->getCicliProduttiviDi($idTerreno);
$datiCatastali = $dbh->getDatiCatsataliDi($idTerreno);
$datiTerreno = $dbh->getDatiDi($idTerreno);
$readyNuovoCiclo = $dbh->readyNuovoCiclo($idTerreno);
$Colture = $dbh->getColture();

?>
<h2 class="orange-on-white"><?php echo $datiTerreno[0]['nome']; ?></h2>
<h4>Informazioni</h4>
<table class="simpletable">
    <tr>
        <td>Superficie:</td>
        <td><?php echo $datiTerreno[0]['superficie'] ?></td>
    </tr>
    <tr>
        <td>Granulometria:</td>
        <td><?php echo $datiTerreno[0]['granulometria'] ?></td>
    </tr>
    <tr>
        <td>Percentuale di argilla:</td>
        <td><?php echo $datiTerreno[0]['perc_argilla'] ?>%</td>
    </tr>
    <tr>
        <td>Percentuale di limo:</td>
        <td><?php echo $datiTerreno[0]['perc_sabbia'] ?>%</td>
    </tr>
    <tr>
        <td>Percentuale di sabbia:</td>
        <td><?php echo $datiTerreno[0]['perc_limo'] ?>%</td>
    </tr>
</table>

<h4>Dati catastali</h4>
<table class="simpletable">
    <tr>
        <td>Comune:</td>
        <td><?php echo $datiCatastali[0]['comune'] ?></td>
    </tr>
    <tr>
        <td>Particella:</td>
        <td><?php echo $datiCatastali[0]['particella'] ?></td>
    </tr>
    <tr>
        <td>Sezione:</td>
        <td><?php echo $datiCatastali[0]['sezione'] ?></td>
    </tr>
</table>

<h2>Cicli produttivi svolti</h2>
<ul class="terreni-list">
    <?php foreach ($cicliProduttivi as $ciclo): ?>
        <li onclick="location.href='dettagli-ciclo.php?id=<?= htmlspecialchars($ciclo['idCicloProduttivo']) ?>'">
            <div class="terreno-header orange-on-white">
                <strong>[<?= htmlspecialchars($ciclo['idCicloProduttivo']) ?>] Coltivato :
                    <?= htmlspecialchars($ciclo['coltura_coltivata']) ?>
                    <?php 
                        echo "(" . htmlspecialchars($ciclo['possesso']) .")";
                     ?></strong>
            </div>
            <div class="terreno-details">
                <span>Inizio:</span>
                <?= htmlspecialchars($ciclo['data_inizio']) ?>
            </div>
            <div class="terreno-details">
                <span>Fine:</span>
                <?php if (isset($ciclo['data_fine'])) {
                    echo htmlspecialchars($ciclo['data_fine']);
                } else {
                    echo "in Corso";
                } ?>
            </div>
            <div class="terreno-details <?php if ($ciclo['bilancio'] > 0) {
                echo 'positive';
            } else {
                echo 'negative';
            } ?>">
                <span>Bilancio:</span>
                <?php echo htmlspecialchars($ciclo['bilancio']) . "€"; ?>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<?php if (!$readyNuovoCiclo[0]["pronto"]): ?>
    <h3>
        Registra un nuovo ciclo produttivo
    </h3>
    <form id="newCicloForm">
        <div class="input-line" id="newCicloInput">
            <div class="input-group hidden">
                <label for="terrenoNewCiclo">Coltura</label>
                <select id="terrenoNewCiclo">
                    <?php
                    echo '<option value="' . $idTerreno . '" selected="selected">Ciclo ' . $idTerreno . '</option>;';
                    ?>
                </select>
            </div>
            <div class="input-group">
                <label for="colturaNewCiclo">Coltura</label>
                <select id="colturaNewCiclo">
                    <?php
                    foreach ($Colture as $coltura) {
                        echo '<option value="' . $coltura["nome_coltura"] . '" selected="selected">' . $coltura["nome_coltura"] . '</option>;';
                    }
                    ?>
                </select>
            </div>
            <div class="input-group">
                <label for="dataInizio">Inizio</label>
                <input id="dataInizio" type="date" required />
            </div>
            <div class="input-group">
                <label for="costoNewCiclo">Costo (Affitto)</label>
                <input id="costoNewCiclo" type="number" min="1" step="1" />
            </div>

            <div class="input-group">
                <label for="proprietario">Proprietario</label>
                <input id="proprietario" type="text" minlength="2" maxlength="50" pattern="[A-Za-z\s]+" />
            </div>
            <div class="input-group">
                <input id="recordNewCiclo" type="submit" value="Registra" class="orange-on-white" />
            </div>
        </div>
    </form>
<?php endif; ?>

<?php if (!$readyNuovoCiclo[0]["pronto"]): ?>
    <h3>
        Registra una nuova rilevazione
    </h3>

<?php endif; ?>