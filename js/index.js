const navItems = document.querySelectorAll('body>nav>ul>li');

navItems.forEach(navItem => {
    navItem.addEventListener('click', function () {
        if (!this.classList.contains('selected')) {
            navItems.forEach(item => item.classList.remove('selected'));
            this.classList.add('selected');
        }
        const pageName = this.textContent.trim().toLowerCase();

        const apiUrl = `api/${pageName}-main.php`; 
        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Errore API: ${response.status}`);
                }
                return response.text();
            })
            .then(data => {
                mainContent.innerHTML = data;
            })
            .catch(error => {
                console.error('Errore durante il fetch:', error);
                mainContent.innerHTML = `<p>Errore nel caricamento del contenuto.</p>`;
            });
    });
});