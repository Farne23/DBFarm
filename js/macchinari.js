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

document.getElementById('tipologiaInserimento').addEventListener('change', async function() {
    const tipologia = this.value; 
    console.log(tipologia);
    
    // Invia una richiesta all'API per ottenere le caratteristiche associate
    try {
        const response = await fetch('api/caratteristiche-associate.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ tipologia: tipologia }), // Passa la tipologia nel body della richiesta
        });

        console.log(await response.text());
        const result = await response.json();

        if (result.success) {
            const caratteristicheDiv = document.getElementById('caratteristiche-associate');
            caratteristicheDiv.innerHTML = ''; // Pulisci il contenuto precedente

            const ulElement = document.createElement('ul'); // Crea l'elemento <ul>

            result.caratteristiche.forEach(caratteristica => {
                // Crea un <li> per ogni caratteristica
                const liElement = document.createElement('li');
                
                // Crea il label per la caratteristica
                const labelElement = document.createElement('label');
                labelElement.setAttribute('for', caratteristica.nome_caratteristica);
                labelElement.textContent = capitalizeFirstLetter(caratteristica.nome_caratteristica); // Capitalizza la prima lettera
                
                // Crea l'input per la caratteristica
                const inputElement = document.createElement('input');
                inputElement.type = 'text';
                inputElement.id = caratteristica.nome_caratteristica;
                inputElement.name = caratteristica.nome_caratteristica;
                inputElement.placeholder = 'Inserisci ' + caratteristica.nome_caratteristica;
                inputElement.required = true;

                // Aggiungi il label e l'input all'elemento <li>
                liElement.appendChild(labelElement);
                liElement.appendChild(inputElement);

                // Aggiungi l'elemento <li> alla lista <ul>
                ulElement.appendChild(liElement);
            });

            // Aggiungi la lista <ul> al div delle caratteristiche
            caratteristicheDiv.appendChild(ulElement);

        } else {
            console.log("Errore nel recupero delle caratteristiche.");
        }
    } catch (error) {
        console.error('Errore durante la richiesta:', error);
    }
});

// Funzione per capitalizzare la prima lettera di una stringa
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}