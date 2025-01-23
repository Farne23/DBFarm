<?php

function createListaOperatori($operatoriInfo)
{
    $listaOperatori = "";
    foreach ($operatoriInfo as $operatore) {
        $listaOperatori .= "<li>";
        $listaOperatori .= "<div class='white-on-orange'>";
        $listaOperatori .= "<span> " . $operatore["CF"] . "</span>";
        $listaOperatori .= "<span> Nominativo: " . $operatore["nome"] . " " . $operatore["cognome"] . "</span>";
        $listaOperatori .= "<span> Data di nascita: " . $operatore["data_nascita"] . "</span>";
        $listaOperatori .= "<span> Telefono: " . $operatore["telefono"] . "</span>";
        $listaOperatori .= "</div>";
        $listaOperatori .= "<div>";
        $listaOperatori .= "<ul>";
        foreach ($operatore["contratti"] as $contratto) {
            $listaOperatori .= "<li>";
            $listaOperatori .= "<span> Inizio: " . $contratto["data_inizio"] . "</span>";
            $listaOperatori .= "<span> Durata: " . $contratto["durata"] . " mesi </span>";
            $listaOperatori .= "<span> Paga: " . $contratto["paga_oraria"] . "</span>";
            $listaOperatori .= "<li>";
        }
        $listaOperatori .= "</ul>";
        $listaOperatori .= "</div>";
        $listaOperatori .= "</li>";
    }
    return $listaOperatori;
}

?>