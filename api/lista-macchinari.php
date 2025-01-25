<?php

$inputData = json_decode(file_get_contents('php://input'), true);

// Controlla se i parametri sono settati
if (isset($inputData['tipologia']) && isset($inputData['semovente'])) {
    require_once '../bootstrap.php';
    $macchinari = $dbh->getMacchinariListFiltered($inputData['tipologia'],$inputData['semovente']);
} else {
    require_once 'bootstrap.php';
    $macchinari = $dbh->getMacchinariList();
}
?>
<ul>
    <?php
    echo "<ul>";
    foreach ($macchinari as $macchinario) {
        echo "<li>";
        echo "<span class='white-on-orange full-line'><strong>" . htmlspecialchars($macchinario['marca']) . ' ' . htmlspecialchars($macchinario['modello']) . "</strong> (" . htmlspecialchars($macchinario['tipologia']) . ") </span>";
        echo "<ul>";
        echo "<li><strong>Costo orario:</strong> " . htmlspecialchars($macchinario['costo_orario']) . " €/h</li>";
        if (!empty($macchinario['potenza'])) {
            echo "<li><strong>Potenza:</strong> " . htmlspecialchars($macchinario['potenza']) . " CV</li>";
        }
        if (!empty($macchinario['serbatoio'])) {
            echo "<li><strong>Serbatoio:</strong> " . htmlspecialchars($macchinario['serbatoio']) . " litri</li>";
        }
        echo "<li><strong>Caratteristiche:</strong> " . htmlspecialchars($macchinario['caratteristiche']) . "</li>";
        echo "</ul>";
        echo "</li>";
    }
    echo "</ul>";
    ?>
</ul>