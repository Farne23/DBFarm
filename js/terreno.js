document.getElementById('newCicloForm').addEventListener('submit', async function (event) {
    event.preventDefault();
    // Raccogli i dati dai campi input
    const data = {
        type: "newCiclo",
        terreno: document.getElementById('terrenoNewCiclo').value,
        coltura: document.getElementById('colturaNewCiclo').value,
        inizio: document.getElementById('dataInizio').value,
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