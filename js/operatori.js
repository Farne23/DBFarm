document.getElementById('newOperatoreForm').addEventListener('submit', async function (event) {
    event.preventDefault();
    // Raccogli i dati dai campi input
    const data = {
        CF: document.getElementById('CFnewOperatore').value,
        nome: document.getElementById('nomenewOperatore').value,
        cognome: document.getElementById('cognomenewOperatore').value,
        dataNascita: document.getElementById('datanewOperatore').value,
        telefono: document.getElementById('telefononewOperatore').value,
    };

    document.getElementById('CFnewOperatore').value = '';
    document.getElementById('nomenewOperatore').value = '';
    document.getElementById('cognomenewOperatore').value = '';
    document.getElementById('datanewOperatore').value = '';
    document.getElementById('telefononewOperatore').value = '';

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
            const newOperatoreInput = document.getElementById('newOperatoreInput');
            // Aggiungi la classe all'elemento
            newOperatoreInput.classList.add('success');
            setTimeout(() => {
                newOperatoreInput.classList.remove('success');
            }, 2000);

            fetch('api/lista-operatori.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('listaOperatori').innerHTML = data;
                })
                .catch(error => {
                    console.error('Errore nel caricare il contenuto:', error);
                });
        } else {
            const newOperatoreInput = document.getElementById('newOperatoreInput');
            newOperatoreInput.classList.add('fail');
            setTimeout(() => {
                newOperatoreInput.classList.remove('fail');
            }, 2000);
        }
    } catch (error) {
        console.error('Errore durante la richiesta:', error);
    }
});