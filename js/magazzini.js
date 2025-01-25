function toggleHiddenByClass(className) {
    const button = document.querySelector(`a.${className}`);
    const elements = document.querySelectorAll(`ul.${className}`);
    elements.forEach(element => {
        element.classList.toggle('hidden');
        if (!element.classList.contains('hidden')) {
            button.innerHTML = "Nascondi"
        } else {
            button.innerHTML = "Visualizza contenuto"
        }
    });

};

document.getElementById('newDepositoForm').addEventListener('submit', async function (event) {
    event.preventDefault();
    const data = {
        type:"newDeposito",
        magazzino: document.getElementById('magazzinoSelezionato').value,
        prodotto: document.getElementById('prodottoSelezionato').value,
        quantita: document.getElementById('quantitaSelezionata').value,
    };

    document.getElementById('quantitaSelezionata').value = '';
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
            const newDepositoInput = document.getElementById('newDepositoInput');
            // Aggiungi la classe all'elemento
            newDepositoInput.classList.add('success');
            setTimeout(() => {
                newDepositoInput.classList.remove('success');
            }, 2000);

            fetch('api/lista-magazzini.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('listaMagazzini').innerHTML = data;
                })
                .catch(error => {
                    console.error('Errore nel caricare il contenuto:', error);
                });
        } else {
            const newDepositoInput = document.getElementById('newDepositoInput');
            newDepositoInput.classList.add('fail');
            setTimeout(() => {
                newDepositoInput.classList.remove('fail');
            }, 2000);
        }
    } catch (error) {
        console.error('Errore durante la richiesta:', error);
    }
});