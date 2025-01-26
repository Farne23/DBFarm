<?php
require_once 'bootstrap.php';
$Granulometrie = $dbh->getGranulometrie();
?>
<section>
    <h3>
        Registra un nuovo terreno
    </h3>
    <form id="newTerrenoForm">
        <div id="newTerrenoInput" class="input-line">
            <div class="input-group">
                <label for="nomeNewTerreno">Nome</label>
                <input id="nomeNewTerreno" type="text" required minlength="3" maxlength="50" pattern="[A-Za-z0-9\s]+"
                    title="Il nome deve contenere solo caratteri alfanumerici e spazi, da 3 a 50 caratteri." />
            </div>
            <div class="input-group">
                <label for="superficieNewTerreno">Superficie (m²)</label>
                <input id="superficieNewTerreno" type="number" required min="1" step="0.01"
                    title="Inserire un valore numerico positivo per la superficie." />
            </div>
            <div class="input-group">
                <label for="limo">Limo (%)</label>
                <input id="limo" type="number" required min="0" max="100" step="0.01"
                    title="Inserire una percentuale compresa tra 0 e 100." />
            </div>
            <div class="input-group">
                <label for="sabbia">Sabbia (%)</label>
                <input id="sabbia" type="number" required min="0" max="100" step="0.01"
                    title="Inserire una percentuale compresa tra 0 e 100." />
            </div>
            <div class="input-group">
                <label for="argilla">Argilla (%)</label>
                <input id="argilla" type="number" required min="0" max="100" step="0.01"
                    title="Inserire una percentuale compresa tra 0 e 100." />
            </div>
            <div class="input-group">
                <label for="comune">Comune</label>
                <input id="comune" type="text" required minlength="2" maxlength="50" pattern="[A-Za-z\s]+"
                    title="Inserire un nome di comune valido (solo lettere e spazi, minimo 2 caratteri)." />
            </div>
            <div class="input-group">
                <label for="particella">Particella</label>
                <input id="particella" type="text" required minlength="1" maxlength="20" pattern="[A-Za-z0-9\-]+"
                    title="Inserire un codice particella valido (caratteri alfanumerici e trattini)." />
            </div>
            <div class="input-group">
                <label for="sezione">Sezione</label>
                <input id="sezione" type="text" required minlength="1" maxlength="5" pattern="[A-Za-z0-9]+"
                    title="Inserire un codice sezione valido (caratteri alfanumerici, massimo 5 caratteri)." />
            </div>
            <div class="input-group">
                <label for="granulometriaNewTerreno">Granulometria</label>
                <select id="granulometriaNewTerreno" name="granulometria" required>
                    <?php
                    foreach ($Granulometrie as $granulometria) {
                        echo '<option value="' . $granulometria["nome_granulometria"] . '">' . $granulometria["nome_granulometria"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="input-group">
                <input id="newTerreno" type="submit" value="Registra" class="orange-on-white" />
            </div>
        </div>
    </form>

    <h3>
        Terreni registrati
    </h3>
</section>
<section id="listaOperatori">
    <ul>
        <?php
        echo createListaOperatori($Operatori);
        ?>
    </ul>
</section>