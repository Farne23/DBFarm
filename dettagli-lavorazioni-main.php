<?php
    if (!empty($turni)) {
        echo "<h3>Turni</h3>";
        foreach ($turni as $turno) {
            echo '<div class="turno-cella ">';
            echo "<p><strong>ID Turno:</strong> {$turno['idTurno']}</p>";
            echo "<p><strong>CF:</strong> {$turno['CF']}</p>";
            echo "<p><strong>Data:</strong> {$turno['data']}</p>";
            echo "<p><strong>Durata:</strong> {$turno['durata']} ore</p>";
            echo "<p><strong>ID Prodotto utilizzato:</strong> {$turno['id_prodotto']}</p>";
            echo "<p><strong>Magazzino del prodotto:</strong> {$turno['magazzino_prodotto']}</p>";
            echo "<p><strong>Quantità Prodotto:</strong> {$turno['quantita_prodotto']}</p>";

            // Sezione Macchinari
            if (!empty($turno['macchinari'])) {
                echo "<h4>Macchinari Utilizzati</h4>";
                echo '<table  border="1" cellpadding="5" class="turno-tabella">';
                echo '<thead>
                <tr>
                    <th>ID Macchinario</th>
                    <th>Tipologia</th>
                    <th>Marca</th>
                    <th>Modello</th>
                    <th>Costo Orario</th>
                </tr>
            </thead>';
                echo '<tbody>';
                foreach ($turno['macchinari'] as $macchinario) {
                    echo '<tr>';
                    echo "<td>{$macchinario['idMacchinario']}</td>";
                    echo "<td>{$macchinario['tipologia']}</td>";
                    echo "<td>{$macchinario['marca']}</td>";
                    echo "<td>{$macchinario['modello']}</td>";
                    echo "<td>{$macchinario['costo_orario']} €</td>";
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            }
            echo '</div>';
        }
    } else {
        echo "<p><em>Nessun turno disponibile.</em></p>";
    }
    ?>