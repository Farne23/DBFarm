<?php
include_once 'bootstrap.php';
$lavorazioni = $dbh->getLavorazioni($idCicloProduttivo);
$ciclo = $dbh->getCiclo($idCicloProduttivo)[0];
$categorie = $dbh->getCategorie();
$Operatori = $dbh->getOperatori();
$Mezzi_semoventi = $dbh->getMacchinariListFiltered("qualsiasi", "semoventeSi");
$Attrezzi = $dbh->getMacchinariListFiltered("qualsiasi", "semoventeNo");
$Magazzini = $dbh->getMagazziniListComplete();
?>
<h1>Ciclo produttivo <?= $ciclo["idCicloProduttivo"] ?> sul terreno <?= $ciclo["idTerreno"] ?>
    (<?= $ciclo["coltura_coltivata"] ?>) </h1>
<div>
    <h3>Bilancio: <?= $ciclo["bilancio"] ?>€</h3>
</div>
<?php
if (count($lavorazioni) > 0) {
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
        <div class="input-group">
            <label for="concludiLavorazioneData">Data fine lavorazione</label>
            <input id="concludiLavorazioneData" type="date" required />
        </div>
        <div class="lavorazione-pulsanti">
            <input type="submit" id="concludi" class="concludi-lavorazione orange-on-white" value="Concludi lavorazione" />
        </div>
        <h3>Aggiungi turno lavorativo</h3>
        <form id="newTurnoLavorativo">
            <div class="input-group">
                <label for="operatoreTurno">Seleziona Operatore:</label>
                <select id="operatoreTurno" name="operatore" required>
                    <option value="">-- Seleziona un Operatore --</option>
                    <?php foreach ($Operatori as $operatore) { ?>
                        <option value="<?= htmlspecialchars($operatore['CF']) ?>">
                            <?= htmlspecialchars($operatore['CF']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="input-group">
                <label for="mezzo_semovente">Seleziona Mezzo Semovente:</label>
                <select id="mezzo_semovente" name="mezzo_semovente" required>
                    <option value="">-- Seleziona un Mezzo Semovente --</option>
                    <?php foreach ($Mezzi_semoventi as $mezzo) { ?>
                        <option value="<?= htmlspecialchars($mezzo['idMacchinario']) ?>">
                            [<?= htmlspecialchars($mezzo['idMacchinario']) ?>]
                            <?= htmlspecialchars($mezzo['tipologia']) ?>
                            <?= htmlspecialchars($mezzo['marca']) ?>
                            <?= htmlspecialchars($mezzo['modello']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="input-group">
                <label for="attrezzi">Seleziona Attrezzi (puoi selezionarne più di uno):</label>
                <select id="attrezzi" class="select-multiple" name="attrezzi[]" multiple required>
                    <?php foreach ($Attrezzi as $attrezzo) { ?>
                        <option value="<?= htmlspecialchars($attrezzo["idMacchinario"]) ?>">
                            [<?= htmlspecialchars($attrezzo["idMacchinario"]) ?>]
                            <?= htmlspecialchars($attrezzo['tipologia']) ?>
                            <?= htmlspecialchars($attrezzo['marca']) ?>
                            <?= htmlspecialchars($attrezzo['modello']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Selezione Prodotti -->
            <div class="input-group">
                <label for="prodotti">Seleziona Prodotti</label>
                <select id="prodotti" name="prodotti[]" required>
                    <?php foreach ($Magazzini as $magazzino) { ?>
                        <optgroup
                            label="[<?= htmlspecialchars($magazzino["idEdificio"]) ?>] <?= htmlspecialchars($magazzino["nome"]) ?>">
                            <?php foreach ($magazzino["content"] as $prodotto) { ?>
                                <option
                                    value="<?= htmlspecialchars($prodotto['idProdotto']) ?>,<?= htmlspecialchars($magazzino["idEdificio"]) ?>">
                                    <?= htmlspecialchars($prodotto['nome']) ?>
                                    (<?= htmlspecialchars($prodotto['quantita']) ?>)
                                </option>
                            <?php } ?>
                        </optgroup>
                    <?php } ?>
                </select>
            </div>

            <div class="input-group">
                <label for="prodottiQt">Quantità di prodotto </label>
                <input id="prodottiQt" type="number" required min="1" step="1" />
            </div>

            <div class="input-group">
                <label for="ore">Durata (ore)</label>
                <input id="ore" type="number" required min="1" step="1" />
            </div>

            <!-- Submit -->
            <div class="input-group">
                <button type="submit" id="aggiungiTurno" class="orange-on-white">Registra Turno</button>
            </div>
        </form>
    <?php } else { ?>
        <?php if (isset($ciclo['data_fine'])) { ?>
            <h3>Ciclo produttivo concluso</h3>
        <?php } else { ?>
            <h3>Lavorazione in corso</h3>
            <div class="terreno-details">
                <span>Nessuna lavorazione in corso</span>
            </div>
            <div class="lavorazione-pulsanti">
                <form id="newLavorazioneForm">
                    <div class="input-line" id="newLavorazioneInput">
                        <div class="input-group">
                            <label for="newLavorazioneTipo">Categoria</label>
                            <select id="newLavorazioneTipo">
                                <?php
                                foreach ($categorie as $categoria) {
                                    echo '<option value="' . $categoria["nome_categoria"] . '" selected="selected">' . $categoria["nome_categoria"] . '</option>;';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="dataInizio">Inizio</label>
                            <input id="dataInizio" type="date" required />
                        </div>
                        <div class="input-group">
                            <input type="submit" id="avviaLavorazione" class="orange-on-white " value="Avvia" />
                        </div>

                        <div class="input-group" id="dataFineCicloInput">
                            <label for="concludiCicloData">Data Fine Ciclo</label>
                            <input id="concludiCicloData" type="date" />
                        </div>
                        <div class="input-group" id="dataFineCicloButton">
                            <input type="submit" id="concludi" class="orange-on-white concludi-ciclo" value="Chiudi Ciclo" />
                        </div>
                    </div>
                </form>
            </div>
        <?php } ?>
    <?php } ?>

    <h3>Tutte le lavorazioni</h3>
    <ul class="terreni-list">
        <?php foreach ($lavorazioni as $lavorazione): ?>
            <li
                onclick="location.href='dettagli-lavorazioni.php?id=<?= htmlspecialchars($idCicloProduttivo) ?>&numero=<?= htmlspecialchars($lavorazione['numero_lavorazione']) ?>'">
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
<?php } else { ?>
    <input type="hidden" id="idCicloProduttivo" value="<?php echo $idCicloProduttivo ?>">
    <input type="hidden" id="numero" value="<?php if (isset($ultima_lavorazione['numero_lavorazione']))
        echo $ultima_lavorazione['numero_lavorazione'] ?>">

        <div class="lavorazione-pulsanti">
            <form id="newLavorazioneForm">
                <div class="input-line" id="newLavorazioneInput">
                    <div class="input-group">
                        <label for="newLavorazioneTipo">Categoria</label>
                        <select id="newLavorazioneTipo">
                            <?php
    foreach ($categorie as $categoria) {
        echo '<option value="' . $categoria["nome_categoria"] . '" selected="selected">' . $categoria["nome_categoria"] . '</option>;';
    }
    ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="dataInizio">Inizio</label>
                    <input id="dataInizio" type="date" required />
                </div>
                <div class="input-group">
                    <input type="submit" id="avviaLavorazione" class="orange-on-white " value="Avvia" />
                </div>
            </div>
        </form>
    <?php } ?>