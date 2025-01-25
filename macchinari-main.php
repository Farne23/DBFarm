<?php
require_once 'bootstrap.php';
$tipologie= $dbh->getListaTipologie();
?>


<h3>
    Registra un macchinario
</h3>
<form id="formMacchinario" method="POST" action="salva_macchinario.php">
    <label for="tipologiaInserimento">Seleziona tipologia:</label>
    <select id="tipologiaInserimento" name="tipologia" required>
        <option value="">Seleziona tipologia</option>
        <?php foreach ($tipologie as $tipologia): ?>
            <option value="<?php echo $tipologia; ?>"><?php echo $tipologia["nome_tipologia"]; ?></option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label for="semovente">Semovente:</label>
    <select id="semovente" name="semovente" required>
        <option value="1">Sì</option>
        <option value="0">No</option>
    </select>

    <br><br>

    <div id="formFields"></div>

    <div id="caratteristiche-associate"> </div>

    <button type="submit">Salva Macchinario</button>
</form>

<h3>
    Filtra macchinari
</h3>
<form id="filtraMacchinari">
    <div class="input-line" id="newDepositoInput">
        <div class="input-group">
            <label for="filtroTipologia">Tipologia</label>
            <select id="filtroTipologia">
                <option value="qualsiasi">Qualsiasi</option>;
                <?php
                foreach ($tipologie as $tipologia) {
                    echo '<option value="' . $tipologia["nome_tipologia"] . '">' . $tipologia["nome_tipologia"] . '</option>;';
                }
                ?>
            </select>
        </div>
        <div class="input-group">
            <label for="filtroSemovente">Semovente</label>
            <select id="filtroSemovente">
                <?php
                    echo '<option value="semoventeQualsiasi">Qualsiasi</option>;';
                    echo '<option value="semoventeSi">Mezzo semovente</option>;';
                    echo '<option value="semoventeNo">Attrezzo</option>;';
                ?>
            </select>
        </div>
        <div class="input-group">
            <input id="recordNewDeposito" type="submit" value="Filtra" class="orange-on-white" />
        </div>
    </div>
</form>

<section id="listaMacchinari">
        <?php
        include("api/lista-macchinari.php");
        ?>
</section>