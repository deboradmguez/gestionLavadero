window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    function changePageTitle(title) {
        document.getElementById('page-title').innerText = title;
    }


    
    
});

function showSection(sectionId) {
    fetch(sectionId + '.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('main-content').innerHTML = data;

            // Actualiza la clase activa del botón
            const buttons = document.querySelectorAll('.nav-link');
            buttons.forEach(button => {
                button.classList.remove('active');
            });
            document.querySelector(`[href="#${sectionId}"]`).classList.add('active');
        })
        .catch(error => console.error('Error al cargar la sección:', error));
}

// Mostrar la primera sección por defecto
document.addEventListener('DOMContentLoaded', () => {
    showSection('inicio');
});
