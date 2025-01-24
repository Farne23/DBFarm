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
            $data= new DateTime($contratto["data_inizio"]);
            if($data->modify("+{$contratto["durata"]} days") > $oggi){
                $listaOperatori .= "<li class='active'>";
            }else{
                $listaOperatori .= "<li class='expired'>";
            }
            $listaOperatori .= "<span> Inizio: " . $contratto["data_inizio"] . "</span>";
            $listaOperatori .= "<span> Durata: " . $contratto["durata"] . " giorni </span>";
            $listaOperatori .= "<span> Paga: " . $contratto["paga_oraria"] . "</span>";
            if($data > $oggi){
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

?>