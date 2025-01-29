const registraForm = document.getElementById('RegistraRaccolto');
if (registraForm) {
    registraForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const data = {
            type: "nuovoRaccolto",
            ciclo: document.getElementById('ciclo').value,
            silo: document.getElementById('silo').value,
            data: document.getElementById('data').value,
            quantita: document.getElementById('quantita').value
        };

        console.log(data);
        
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
    })
}

const buttons = document.querySelectorAll('.RegistraRaccoltobtn');

buttons.forEach(button => {
    button.addEventListener('click', async function(){
        const [idCicloProduttivo, dataRaccolta] =  button.getAttribute('data-id').split(',');

        const data = {
            type: "vendita",
            ciclo: idCicloProduttivo,
            dataRaccolta: dataRaccolta,
            data: document.querySelector(`#data-${idCicloProduttivo}`).value,
            acquirente: document.querySelector(`#acquirente-${idCicloProduttivo}`).value
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
    });
});
