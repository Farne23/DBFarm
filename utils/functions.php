<?php

function createListaOperatori($operatoriInfo)
{
    $oggi = new DateTime();
    $listaOperatori = "";
    foreach ($operatoriInfo as $operatore) {
        $listaOperatori .= "<li>";
        $listaOperatori .= "<div class='white-on-orange'>";
        $listaOperatori .= "<span> " . $operatore["CF"] . "</span>";
        $listaOperatori .= "<span>" . $operatore["nome"] . " " . $operatore["cognome"] . "</span>";
        $listaOperatori .= "<span> Data di nascita: " . $operatore["data_nascita"] . "</span>";
        $listaOperatori .= "<span> Telefono: " . $operatore["telefono"] . "</span>";
        $listaOperatori .= "</div>";
        $listaOperatori .= "<div>";
        $listaOperatori .= "<ul>";
        foreach ($operatore["contratti"] as $contratto) {
            $data = new DateTime($contratto["data_inizio"]);
            if ($data->modify("+{$contratto["durata"]} days") > $oggi) {
                $listaOperatori .= "<li class='active'>";
            } else {
                $listaOperatori .= "<li class='expired'>";
            }
            $listaOperatori .= "<span> Inizio: " . $contratto["data_inizio"] . "</span>";
            $listaOperatori .= "<span> Durata: " . $contratto["durata"] . " giorni </span>";
            $listaOperatori .= "<span> Paga: " . $contratto["paga_oraria"] . "</span>";
            if ($data > $oggi) {
                $listaOperatori .= "<span class='orange-on-white'> ATTIVO </span>";
            }
            $listaOperatori .= "</li>";
        }
        $listaOperatori .= "</ul>";
        $listaOperatori .= "</div>";
        $listaOperatori .= "</li>";
    }
    return $listaOperatori;
}

function createListaMagazzini($magazzini)
{
    $listaMagazzini = "";
    foreach ($magazzini as $magazzino) {
        $listaMagazzini .= "<li>";
        $listaMagazzini .= "<div class='white-on-orange'>";
        $listaMagazzini .= "<span class='three-slots'> [" . $magazzino["idEdificio"] . "] : " . $magazzino["nome"] . "</span>";
        $listaMagazzini .= "<span class='three-slots'> Capienza: " . $magazzino["giacienza"] . "/" . $magazzino["capacita_magazzino"] . "</span>";
        $listaMagazzini .= "</div>";
        $listaMagazzini .= "<div>";
        $listaMagazzini .= "<span class='entire-line end-section'> Indirizzo: " . $magazzino["via"] . " " . $magazzino["citta"] . " " . $magazzino["provincia"] . "</span>";
        $listaMagazzini .= "</div>";
        $listaMagazzini .= "<div>";
        $listaMagazzini .= "<span class='entire-line'><a class='edificio-" . $magazzino["idEdificio"] . "'  onclick='toggleHiddenByClass(\"edificio-" . $magazzino["idEdificio"] . "\")'>Visualizza contenuto</a></span>";
        $listaMagazzini .= "</div>";
        $listaMagazzini .= "<div>";
        $listaMagazzini .= "<ul class='full-list hidden edificio-" . $magazzino["idEdificio"] . "'>";
        foreach ($magazzino["content"] as $prodotto) {
            $listaMagazzini .= "<li>";
            $listaMagazzini .= "<span class='important-attribute " . htmlspecialchars($prodotto["tipologia_prodotto"]) . "'>" . htmlspecialchars($prodotto["tipologia_prodotto"]) . "</span>";
            $listaMagazzini .= "<span>" . $prodotto["marca"] . " " . $prodotto["nome"] . " </span>";
            $listaMagazzini .= "<span> Quantità: " . $prodotto["quantita"] . " </span>";
            $listaMagazzini .= "<span> Deposito: " . $prodotto["data_ultimo_deposito"] . " </span>";
            $listaMagazzini .= "</li>";
        }
        $listaMagazzini .= "</ul>";
        $listaMagazzini .= "</div>";
        $listaMagazzini .= "</li>";
    }
    return $listaMagazzini;
}

?>