const registraForm= document.getElementById('RegistraRaccolto');
if (registraForm) {
   registraForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        // Raccogli i dati dai campi input
        const data = {
            type: "nuovoRaccolto",
            ciclo: document.getElementById('ciclo').value,
            silo: document.getElementById('silo').value,
            data: document.getElementById('data').value,
            quantita:document.getElementById('quantita').value
        };

        console.log(data);
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
            console.log(result);

            if (result.success) {
                //window.location.reload();
            } else {
                console.log("Errore");
            }
        } catch (error) {
            console.error('Errore durante la richiesta:', error);
        }
    })
}