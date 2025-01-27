document.getElementById('concludi').addEventListener('click', async function (event) {
    event.preventDefault();

    if (this.classList.contains("concludi-ciclo")) {
        const data = {
            type: "concludiCicloProduttivo",
            ciclo: document.getElementById('idCicloProduttivo').value,
        };

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
                window.location.reload();
            } else {
                console.log("Errore");
            }
        } catch (error) {
            console.error('Errore durante la richiesta:', error);
        }

    } else {
        // Raccogli i dati dai campi input
        const data = {
            type: "concludiLavorazione",
            ciclo: document.getElementById('idCicloProduttivo').value,
            numero: document.getElementById('numero').value
        };

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
                window.location.reload();
            } else {
                console.log("Errore");
            }
        } catch (error) {
            console.error('Errore durante la richiesta:', error);
        }
    }
});