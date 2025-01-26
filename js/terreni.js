document.getElementById('newTerrenoForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    // Raccogli i dati dai campi input
    const data = {
        type: "newTerreno",
        nome: document.getElementById('nomeNewTerreno').value,
        superficie: parseFloat(document.getElementById('superficieNewTerreno').value),
        percLimo: parseFloat(document.getElementById('limo').value),
        percSabbia: parseFloat(document.getElementById('sabbia').value),
        percArgilla: parseFloat(document.getElementById('argilla').value),
        comune: document.getElementById('comune').value,
        particella: document.getElementById('particella').value,
        sezione: document.getElementById('sezione').value,
        granulometria: document.getElementById('granulometriaNewTerreno').value,
    };

    // Resetta i campi input
    document.getElementById('nomeNewTerreno').value = '';
    document.getElementById('superficieNewTerreno').value = '';
    document.getElementById('limo').value = '';
    document.getElementById('sabbia').value = '';
    document.getElementById('argilla').value = '';
    document.getElementById('comune').value = '';
    document.getElementById('particella').value = '';
    document.getElementById('sezione').value = '';

    // Invia i dati al server tramite Fetch
    try {
        const response = await fetch('process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        const result = await response.json();

        if (result.success) {
            const newTerrenoInput = document.getElementById('newTerrenoInput');
            newTerrenoInput.classList.add('success');
            setTimeout(() => {
                newTerrenoInput.classList.remove('success');
            }, 2000);

            fetch('api/lista-terreni.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('listaTerreni').innerHTML = data;
                })
                .catch(error => {
                    console.error('Errore nel caricare la lista terreni:', error);
                });
        } else {
            const newTerrenoInput = document.getElementById('newTerrenoInput');
            newTerrenoInput.classList.add('fail');
            setTimeout(() => {
                newTerrenoInput.classList.remove('fail');
            }, 2000);
        }
    } catch (error) {
        console.error('Errore durante la richiesta:', error);
    }
});