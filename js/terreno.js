document.getElementById('newCicloForm').addEventListener('submit', async function (event) {
    event.preventDefault();
    // Raccogli i dati dai campi input
    const data = {
        type: "newCiclo",
        terreno: document.getElementById('terrenoNewCiclo').value,
        coltura: document.getElementById('colturaNewCiclo').value,
        inizio: document.getElementById('dataInizio').value,
        costo : document.getElementById('costoNewCiclo').value,
        proprietario : document.getElementById('proprietario').value
    };

    document.getElementById('dataInizio').value = '';

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
            const newCicloInput = document.getElementById('newCicloInput');
            newCicloInput.classList.add('success');
            setTimeout(() => {
                newCicloInput.classList.remove('success');
            }, 2000);
            window.location.reload();

        } else {
            newCicloInput.classList.add('fail');
            setTimeout(() => {
                newCicloInput.classList.remove('fail');
            }, 2000);

        }
    } catch (error) {
        console.error('Errore durante la richiesta:', error);
    }
});

document.getElementById('newRilevazioneForm').addEventListener('submit', async function (event) {
    event.preventDefault();
    const data = {
        type: "newRilevazione",
        idTerreno: document.getElementById('idTerreno').value,
        ph: document.getElementById('ph').value,
        umidita: document.getElementById('umidita').value,
        sostanzaOrganica: document.getElementById('sostanzaOrganica').value,
        azoto: document.getElementById('azoto').value,
        infestante: document.getElementById('infestante').value,
    };

    document.getElementById('ph').value = '';
    document.getElementById('umidita').value = '';
    document.getElementById('sostanzaOrganica').value = '';
    document.getElementById('azoto').value = '';
    document.getElementById('infestante').value = '';

    try {
        const response = await fetch('process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        const result = await response.json();
        console.log(result);

        const newRilevazioneInput = document.getElementById('newRilevazioneInput');
        if (result.success) {
            newRilevazioneInput.classList.add('success');
            setTimeout(() => {
                newRilevazioneInput.classList.remove('success');
            }, 2000);
           window.location.reload();
        } else {
            newRilevazioneInput.classList.add('fail');
            setTimeout(() => {
                newRilevazioneInput.classList.remove('fail');
            }, 2000);
        }
    } catch (error) {
        console.error('Errore durante la richiesta:', error);
    }
});