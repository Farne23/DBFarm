<?php
require_once 'bootstrap.php';
$Magazzini = $dbh->getMagazziniListComplete();
$Prodotti = $dbh->getListaProdotti();
$Colture = $dbh->getColture();
?>

<h3>
    Registra un nuovo deposito
</h3>
<form id="newDepositoForm">
    <div class="input-line" id="newDepositoInput">
        <div class="input-group">
            <label for="magazzinoSelezionato">Magazzino</label>
            <select id="magazzinoSelezionato">
                <?php
                foreach ($Magazzini as $magazzino) {
                    echo '<option value="' . $magazzino["idEdificio"] . '">[' . $magazzino["idEdificio"] . '] ' . $magazzino["nome"] . '</option>;';
                }
                ?>
            </select>
        </div>
        <div class="input-group">
            <label for="prodottoSelezionato">Prodotto</label>
            <select id="prodottoSelezionato">
                <?php
                foreach ($Prodotti as $prodotto) {
                    echo '<option value="' . $prodotto["idProdotto"] . '">[' . $prodotto["idProdotto"] . '] ' . $prodotto["marca"] . ' '. $prodotto["nome"] . '</option>;';
                }
                ?>
            </select>
        </div>
        <div class="input-group">
            <label for="quantitaSelezionata">Quantitá</label>
            <input id="quantitaSelezionata" type="text" required pattern="^[0-9]{1,5}$" maxlength="5"
                title="Cifra non accetabile " />
        </div>
        <div class="input-group">
            <input id="recordNewDeposito" type="submit" value="Registra" class="orange-on-white" />
        </div>
    </div>
</form>

<section id="listaMagazzini">
    <ul>
        <?php
        echo createListaMagazzini($Magazzini);
        ?>
    </ul>
</section>