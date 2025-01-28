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
</form>