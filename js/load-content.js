// Función para cargar los componentes HTML
function loadComponent(url, elementId, callback) {
    fetch(url)
        .then(response => response.text())
        .then(data => {
            document.getElementById(elementId).innerHTML = data;
            if (callback) callback(); 
        })
        .catch(error => console.error('Error loading component:', error));
}

function setupEventListeners() {
    const toggler = document.querySelector("#toggle-btn");
    if (toggler) {
        toggler.addEventListener("click", function () {
            document.querySelector("#sidebar-container").classList.toggle("expand");
            document.querySelector(".main").classList.toggle("expand");
        });
    }

    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', (event) => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadComponent('navbar.html', 'navbar-container', () => {
        loadComponent('sidebar.html', 'sidebar-container', setupEventListeners);
    });
});
