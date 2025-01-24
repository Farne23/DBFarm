const navItems = document.querySelectorAll('body>nav>ul>li');
const mainContent = document.querySelector('main');

navItems.forEach(navItem => {
    navItem.addEventListener('click', function () {
        const pageName = this.textContent.trim().toLowerCase();
        window.location.href = `${pageName}.php`;
    });
});