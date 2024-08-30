// Función para configurar los event listeners
function setupEventListeners() {
    const toggleBtn = document.querySelector("#toggle-btn");
    const sidebar = document.querySelector("#sidebar");
    const main = document.querySelector(".main");

    // Toggler para expandir/contraer el sidebar
    toggleBtn?.addEventListener("click", () => {
        sidebar?.classList.toggle("expand");
        main?.classList.toggle("expanded");

        // Guardar el estado del sidebar en localStorage
        localStorage.setItem('sidebar-expanded', sidebar?.classList.contains("expand"));
    });

    // Aplicar el estado guardado del sidebar al cargar la página
    const sidebarExpanded = localStorage.getItem('sidebar-expanded') === 'true';
    if (sidebarExpanded) {
        sidebar?.classList.add("expand");
        main?.classList.add("expanded");
    }

    // Toggler para el sidebar en dispositivos móviles (si es necesario)
    const sidebarToggle = document.querySelector('#sidebarToggle');
    sidebarToggle?.addEventListener('click', (event) => {
        event.preventDefault();
        document.body.classList.toggle('sb-sidenav-toggled');
        localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
    });
}

// Llamar a la función cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', setupEventListeners);
