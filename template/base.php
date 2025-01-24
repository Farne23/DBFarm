<!DOCTYPE html>
<html lang="it">

<head>
    <title>DBFarm</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style/styles.css" />
</head>

<body>
    <header>
        <h1>DBFarm</h1>
    </header>
    <nav>
        <ul>
            <li class="selected">
                Lavorazioni
            </li>
            <li>
                Terreni
            </li>
            <li>
                Macchinari
            </li>
            <li>
                Magazzini
            </li>
            <li>
                Silo
            </li>
            <li>
                Operatori
            </li>
        </ul>
    </nav>
    <main>
        <?php include($templateParams["main-content"]) ?>
        <!-- <h2>Dev'esserci un errore</h2>
        <p>Nulla da vedere qui.. </p> -->
    </main>
    <?php
    if (isset($templateParams["js"]) && is_array($templateParams["js"])) {
        foreach ($templateParams["js"] as $script) {
            echo '<script src="' . htmlspecialchars($script) . '"></script>';
        }
    }
    ?>
</body>
<footer class="footer">
    <div class="footer-content">
        <!-- Due colonne per le informazioni -->
        <div class="footer-columns">
            <ul>
                <li>Email personale: <a href="mailto:michele.farneti23@gmail.com">michele.farneti23@gmail.com</a></li>
                <li>Email istituzionale: <a
                        href="mailto:michele.farneti@studio.unibo.it">michele.farneti@tstudio.unibo.it</a></li>
            </ul>
            <ul>
                <li>Profilo GitHub: <a href="https://github.com/Farne23" target="_blank">Farne23</a></li>
                <li>Repo progetto: <a href="https://github.com/Farne23/DBFarm" target="_blank">DBFarm</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2025 - Tutti i diritti riservati.
    </div>
</footer>

</html>