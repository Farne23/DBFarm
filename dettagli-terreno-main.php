<?php
include_once 'bootstrap.php';
$cicliProduttivi = $dbh->getCicliProduttiviDi($idTerreno);
$datiCatastali = $dbh->getDatiCatsataliDi($idTerreno);
$datiTerreno = $dbh->getDatiDi($idTerreno);
$readyNuovoCiclo = $dbh->readyNuovoCiclo($idTerreno);
$Colture = $dbh->getColture();
$infestanti = $dbh->getInfestanti();
$statoCampo = $dbh->verificaStatoCampo($idTerreno);

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

<h2 class="orange-on-white">Stato del campo</h2>
<?php
if (empty($statoCampo)) {
    echo '<div id="statoCampo">Non rilevato</div>';
} else {
    $rilevazione = $statoCampo[0];
    echo '<div class="campo-rilevazione">';
    echo '<p><strong>Data:</strong> ' . htmlspecialchars($rilevazione['data']) . '</p>';
    echo '<p><strong>pH:</strong> ' . htmlspecialchars($rilevazione['PH']) . '</p>';
    echo '<p><strong>Umidità (%):</strong> ' . htmlspecialchars($rilevazione['perc_umidita']) . '%</p>';
    echo '<p><strong>Sostanza Organica (%):</strong> ' . htmlspecialchars($rilevazione['perc_sostanzaOrganica']) . '%</p>';
    echo '<p><strong>Azoto (%):</strong> ' . htmlspecialchars($rilevazione['perc_azoto']) . '%</p>';
    $infestazione = $rilevazione['infestazione_rilevata'];
    if ($infestazione !== 'nessuna') {
        echo '<p class="alert alert-warning">Infestazione rilevata: ' . htmlspecialchars($infestazione) . '</p>';
    } else {
        echo '<p><strong>Infestazione rilevata:</strong> Nessuna</p>';
    }
    if ($rilevazione['azoto_insufficiente_coltura'] == 1) {
        echo '<p class="alert alert-warning">Azoto insufficiente per la coltura!</p>';
    }
    if ($rilevazione['so_insufficiente_coltura'] == 1) {
        echo '<p class="alert alert-warning">Sostanza organica insufficiente per la coltura!</p>';
    }
    if ($rilevazione['ph_insufficiente_coltura'] == 1) {
        echo '<p class="alert alert-warning">pH insufficiente per la coltura!</p>';
    }
    if ($rilevazione['ph_eccessivo_coltura'] == 1) {
        echo '<p class="alert alert-warning">pH eccessivo per la coltura!</p>';
    }
    if ($rilevazione['ph_insufficiente_granulometria'] == 1) {
        echo '<p class="alert alert-warning">pH insufficiente per la granulometria!</p>';
    }
    if ($rilevazione['ph_eccessivo_granulometria'] == 1) {
        echo '<p class="alert alert-warning">pH eccessivo per la granulometria!</p>';
    }
    if ($rilevazione['umidita_insufficiente_granulometria'] == 1) {
        echo '<p class="alert alert-warning">Umidità insufficiente per la granulometria!</p>';
    }
    if ($rilevazione['umidita_eccessiva_granulometria'] == 1) {
        echo '<p class="alert alert-warning">Umidità eccessiva per la granulometria!</p>';
    }
    echo '</div>';
} ?>

<h2>Cicli produttivi svolti</h2>
<ul class="terreni-list">
    <?php foreach ($cicliProduttivi as $ciclo): ?>
        <li onclick="location.href='dettagli-ciclo.php?id=<?= htmlspecialchars($ciclo['idCicloProduttivo']) ?>'">
            <div
                class="terreno-header <?php if (isset($ciclo['data_fine'])) {
                    echo 'orange-on-white';
                } else {
                    echo "white-on-orange";
                } ?>">
                <strong>[<?= htmlspecialchars($ciclo['idCicloProduttivo']) ?>] Coltivato :
                    <?= htmlspecialchars($ciclo['coltura_coltivata']) ?>
                    <?php
                    echo "(" . htmlspecialchars($ciclo['possesso']) . ")";
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

<h3 <?php if (!$readyNuovoCiclo[0]["pronto"]) {
    echo "class='hidden'";
} ?>>
    Registra un nuovo ciclo produttivo
</h3>
<form id="newCicloForm" <?php if (!$readyNuovoCiclo[0]["pronto"]) {
    echo "class='hidden'";
} ?>>
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


<h3>
    Registra una nuova rilevazione
</h3>
<form id="newRilevazioneForm">
    <div class="input-line" id="newRilevazioneInput">
        <div class="input-group">
            <label for="idTerreno">Terreno</label>
            <input type="number" id="idTerreno" name="idTerreno" value="<?= $idTerreno ?>" required disabled>
        </div>
        <div class="input-group">
            <label for="ph">pH</label>
            <input type="number" step="0.01" id="ph" name="ph" required min="0" max="14">
        </div>
        <div class="input-group">
            <label for="umidita">Umidità (%)</label>
            <input type="number" step="0.01" id="umidita" name="umidita" placeholder="Inserisci % Umidità" required
                min="0" max="100">
        </div>
        <div class="input-group">
            <label for="sostanzaOrganica">S. Organica (%)</label>
            <input type="number" step="0.01" id="sostanzaOrganica" name="sostanzaOrganica"
                placeholder="Inserisci % Sostanza Organica" required min="0" max="100">
        </div>
        <div class="input-group">
            <label for="azoto">Azoto (%)</label>
            <input type="number" step="0.01" id="azoto" name="azoto" placeholder="Inserisci % Azoto" required min="0"
                max="100">
        </div>
        <div class="input-group">
            <label for="infestante">Infestante</label>
            <select id="infestante" name="infestante">
                <option value="">Seleziona un infestante</option>
                <?php
                foreach ($infestanti as $infestante) {
                    echo '<option value="' . htmlspecialchars($infestante["nome_infestante"]) . '">' . htmlspecialchars($infestante["nome_infestante"]) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="input-group">
            <input type="submit" id="submitRilevazione" class="orange-on-white" value="Registra">
        </div>
    </div>
</form>