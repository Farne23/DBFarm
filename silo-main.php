<form id="RegistraRaccolto">
    <h4>Registra Raccolto</h4>
    <div class="input-line">
        <div class="input-group">
            <label for="ciclo">Ciclo produttivo</label>
            <select id="ciclo">
                <?php
                foreach ($cicli as $ciclo) {
                    echo '<option value="' . $ciclo["idCicloProduttivo"] . '" selected="selected">' . $ciclo["idCicloProduttivo"] . '</option>;';
                }
                ?>
            </select>
        </div>
        <div class="input-group">
            <label for="data">Data</label>
            <input id="data" type="date" required />
        </div>
        <div class="input-group">
            <label for="quantita">Quantita</label>
            <input id="quantita" type="number" min="0" required />
        </div>
        <div class="input-group">
            <label for="silo">Silo</label>
            <select id="silo">
                <?php
                foreach ($silos as $silo) {
                    echo '<option value="' . $silo["idEdificio"] . '">[' . $silo["idEdificio"] . '] ' . $silo["nome"] . '</option>;';
                }
                ?>
            </select>
        </div>

        <div class="input-group">
            <input id="RegistraRaccoltobtn" class="orange-on-white" type="submit" value="Registra" />
        </div>
    </div>
</form>

<h2>Silo</h2>
<ul class="terreni-list">
    <?php foreach ($silos as $silo): ?>
        <li>
            <div class="terreno-header orange-on-white">
                <strong>[<?= htmlspecialchars($silo['idEdificio']) ?>] :
                    <?= htmlspecialchars($silo['nome']) ?></strong>
            </div>
            <div class="terreno-details">
                <span>Riempimento :</span>
                <?= htmlspecialchars(intval($silo['giacenza'])) ?> /<?= htmlspecialchars($silo['capacita_silo']) ?>
            </div>
            <div class="terreno-details">
                <ul class="raccolti-list">
                    <?php foreach ($silo["content"] as $raccolto): ?>
                        <li>
                            <div class="raccolti-details">
                                <strong><?= htmlspecialchars($raccolto["quantita"]) ?></strong>
                                <?= htmlspecialchars($raccolto["coltura_coltivata"]) ?>
                                Depositato il <?= htmlspecialchars($raccolto["data"]) ?>
                                Da ciclo produttivo [<?= htmlspecialchars($raccolto["idCicloProduttivo"]) ?>]
                            </div>
                            <div class="input-line">
                                <div class="input-group">
                                    <label for="data-<?= htmlspecialchars($raccolto["idCicloProduttivo"]) ?>">Data</label>
                                    <input id="data-<?= htmlspecialchars($raccolto["idCicloProduttivo"]) ?>" type="date"
                                        required />
                                </div>
                                <div class="input-group">
                                    <label
                                        for="acquirente-<?= htmlspecialchars($raccolto["idCicloProduttivo"]) ?>">Acquirente</label>
                                    <input id="acquirente-<?= htmlspecialchars($raccolto["idCicloProduttivo"]) ?>" type="text"
                                        required />
                                </div>
                                <div class="input-group">
                                    <input id="RegistraRaccoltobtn-<?= htmlspecialchars($raccolto["idCicloProduttivo"]) ?>"
                                        class="orange-on-white RegistraRaccoltobtn" type="button" value="Vendi"
                                        data-id="<?= htmlspecialchars($raccolto["idCicloProduttivo"] . ',' . $raccolto["data"]) ?>" />
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </li>
    <?php endforeach; ?>
</ul>