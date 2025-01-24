<?php
require_once 'bootstrap.php';
$Operatori = $dbh->getOperatoriListComplete()
    ?>
<section>
    <h3>
        Registra un nuovo operatore
    </h3>
    <form id="newOperatoreForm">
        <div id="newOperatoreInput" class="input-line">
            <div class="input-group">
                <label for="CFnewOperatore">Codice Fiscale</label>
                <input id="CFnewOperatore" name="CF" type="text" required pattern="[A-Za-z0-9]{16}"
                    title="Il Codice Fiscale deve contenere 16 caratteri alfanumerici" />
            </div>
            <div class="input-group">
                <label for="nomenewOperatore">Nome</label>
                <input id="nomenewOperatore" name="nome" type="text" required minlength="2" maxlength="50" />
            </div>
            <div class="input-group">
                <label for="cognomenewOperatore">Cognome</label>
                <input id="cognomenewOperatore" name="cognome" type="text" required minlength="2" maxlength="50" />
            </div>
            <div class="input-group">
                <label for="datanewOperatore">Data di Nascita</label>
                <input id="datanewOperatore" name="dataNascita" type="date" required />
            </div>
            <div class="input-group">
                <label for="telefononewOperatore">Telefono</label>
                <input id="telefononewOperatore" name="telefono" type="tel" required pattern="^[0-9]{10}$"
                    title="Il numero di telefono deve contenere 10 cifre" />
            </div>
            <div class="input-group">
                <input id="newOperatore" type="submit" value="Registra" class="orange-on-white" />
            </div>
        </div>
    </form>
    <h3>
        Registra un contratto
    </h3>
    <div class="input-line">
        <div class="input-group">
            <label for="CFnewContratto">Operatore</label>
            <select id="CFnewContratto" name="siti">
                <?php
                foreach ($Operatori as $operatore) {
                    echo '<option value="' . $operatore["CF"] . '" selected="selected">' . $operatore["CF"] . '</option>;';
                }
                ?>
            </select>
        </div>
        <div class="input-group">
            <label for="dataInizio">Inizio</label>
            <input id="dataInizio" type="date" />
        </div>
        <div class="input-group">
            <label for="durataNewContratto">Durata</label>
            <input id="durataNewContratto" type="text" />
        </div>
        <div class="input-group">
            <label for="pagaNewContratto">Paga</label>
            <input id="pagaNewContratto" type="text" />
        </div>
        <div class="input-group">

            <input id="recordNewContratto" type="button" value="Aggiungi" class="orange-on-white" />
        </div>
    </div>
    <h3>
        Operatori registrati
    </h3>
</section>
<section id="listaOperatori">
    <ul>
        <?php
        echo createListaOperatori($Operatori);
        ?>
    </ul>
</section>