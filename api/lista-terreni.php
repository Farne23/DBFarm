<?php

// $inputData = json_decode(file_get_contents('php://input'), true);

// // Controlla se i parametri sono settati
// if (isset($inputData['tipologia']) && isset($inputData['semovente'])) {
//     require_once '../bootstrap.php';
//     $macchinari = $dbh->getMacchinariListFiltered($inputData['tipologia'], $inputData['semovente']);
// } else {
//     if (file_exists('../bootstrap.php')) {
//         require_once '../bootstrap.php';
//     } else {
//         require_once 'bootstrap.php';
//     }
//     $macchinari = $dbh->getMacchinariList();
// }
if (file_exists('../bootstrap.php')) {
    require_once '../bootstrap.php';
} else {
    require_once 'bootstrap.php';
}
$terreniLavorati = $dbh->getListaTerreniLavorati();
$terreniIncolti = $dbh->getListaTerreniIncolti();
?>
<h3>Terreni lavorati</h3>
<ul class="terreni-list">
    <?php foreach ($terreniLavorati as $terreno): ?>
        <li onclick="location.href='dettagli-terreno.php?id=<?= htmlspecialchars($terreno['idTerreno']) ?>'">
            <div class="terreno-header orange-on-white">
                <strong>[<?= htmlspecialchars($terreno['idTerreno']) ?>] <?= htmlspecialchars($terreno['nome']) ?></strong>
            </div>
            <div class="terreno-details">
                <span>Ultima Lavorazione:</span> 
                <?= htmlspecialchars($terreno['categoria']) ?> 
                <?= htmlspecialchars($terreno['coltura_coltivata']) ?>
                (<?= htmlspecialchars($terreno['stato lavorazione']) ?>)
                per Ciclo Produttivo # <?= htmlspecialchars($terreno['idCicloProduttivo']) ?>
                (<?= htmlspecialchars($terreno['stato ciclo produttivo']) ?>)
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<h3>Terreni incolti</h3>
<ul class="terreni-list">
    <?php foreach ($terreniIncolti as $terreno): ?>
        <li onclick="location.href='dettagli-terreno.php?id=<?= htmlspecialchars($terreno['idTerreno']) ?>'">
            <div class="terreno-header orange-on-white">
                <strong>[<?= htmlspecialchars($terreno['idTerreno']) ?>] <?= htmlspecialchars($terreno['nome']) ?></strong>
        </li>
    <?php endforeach; ?>
</ul>