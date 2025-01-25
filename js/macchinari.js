document.getElementById('filtraMacchinari').addEventListener('submit', async function (event) {
    event.preventDefault();
    const data = {
        tipologia: document.getElementById('filtroTipologia').value,
        semovente: document.getElementById('filtroSemovente').value,
    };

    try {
        const response = await fetch('api/lista-macchinari.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const result = await response.text();
        document.getElementById('listaMacchinari').innerHTML = result;
    } catch (error) {
        console.error('Errore durante la richiesta:', error);
    }
});