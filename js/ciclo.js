const concludiButton = document.getElementById('concludi');
if (concludiButton) {
    concludiButton.addEventListener('click', async function (event) {
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
                console.log(result);

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

}



const avviaButton = document.getElementById('avviaLavorazione');
if (avviaButton) {
    avviaButton.addEventListener('click', async function (event) {
        event.preventDefault();


        // Raccogli i dati dai campi input
        const data = {
            type: "avviaLavorazione",
            ciclo: document.getElementById('idCicloProduttivo').value,
            categoria: document.getElementById('newLavorazioneTipo').value,
            inizio: document.getElementById('dataInizio').value
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

    });
}

const aggiungiTurnoButton = document.getElementById('aggiungiTurno');

if (aggiungiTurnoButton) {
    aggiungiTurnoButton.addEventListener('click', async function (event) {
        event.preventDefault();

        const data = {
            type: "aggiungiTurnoLavorativo",
            ciclo: document.getElementById('idCicloProduttivo').value,
            numero: document.getElementById('numero').value,
            operatore: document.getElementById('operatoreTurno').value,
            mezzo_semovente: document.getElementById('mezzo_semovente').value,
            attrezzi: Array.from(document.getElementById('attrezzi').selectedOptions).map(option => option.value),
            prodotto: document.getElementById('prodotti').value.split(',').map(value => {
                const [idProdotto, idMagazzino] = value.split(',');
                return { idProdotto, idMagazzino };
            }),
            prodottiQt: document.getElementById('prodottiQt').value,
            ore: document.getElementById('ore').value
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
                console.log("Turno lavorativo registrato con successo.");
                //window.location.reload(); // Ricarica la pagina
            } else {
                console.error("Errore durante la registrazione del turno:", result.error);
            }
        } catch (error) {
            console.error('Errore durante la richiesta:', error);
        }
    });
}