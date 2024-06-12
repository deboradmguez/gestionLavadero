window.addEventListener('DOMContentLoaded', (event) => {
    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', (event) => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    // Cambiar el título de la página al hacer clic en un enlace de navegación
    document.querySelectorAll(".nav-link").forEach(link => {
        link.addEventListener("click", (event) => {
            document.title = event.target.innerText; // Obtener el texto del enlace como título
        });
    });

    // Mostrar la primera sección por defecto
    showSection('inicio');

    // Asignar event listeners iniciales
    setupEventListeners();
});

// Función para mostrar una sección específica
function showSection(sectionId) {
    fetch(`${sectionId}.php`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('main-content').innerHTML = data;

            // Actualiza la clase activa del botón
            const buttons = document.querySelectorAll('.nav-link');
            buttons.forEach(button => {
                button.classList.remove('active');
            });

            const activeButton = document.querySelector(`[href="#${sectionId}"]`);
            if (activeButton) {
                activeButton.classList.add('active');
            }

            // Vuelve a asignar los event listeners
            setupEventListeners();
        })
        .catch(error => console.error('Error al cargar la sección:', error));
}

// Función para asignar event listeners
function setupEventListeners() {
    const listaClientes = document.getElementById('listaClientes');
    const detalleCliente = document.getElementById('detalleCliente');

    if (listaClientes) {
        listaClientes.addEventListener('click', function (event) {
            if (event.target.classList.contains('cliente-nombre')) {
                const clienteSeleccionado = event.target.closest('li');

                document.querySelectorAll('.cliente-detalle').forEach(function (detalle) {
                    detalle.style.display = 'none';
                });

                detalleCliente.style.display = 'block';
            } else {
                detalleCliente.style.display = 'none';
            }
        });
    }

    document.querySelectorAll('.cliente-nombre').forEach(function (nombre) {
        nombre.addEventListener('click', function () {
            const dni = this.closest('li').dataset.dni;
            fetch(`db/obtener_cliente.php?dni=${dni}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('detalleDNI').textContent = `DNI: ${data.dni}`;
                    document.getElementById('detalleNombre').textContent = `Nombre: ${data.nombre}`;
                    document.getElementById('detalleApellido').textContent = `Apellido: ${data.apellido}`;
                    document.getElementById('detalleTelefono').textContent = `Teléfono: ${data.telefono}`;

                    //this.nextElementSibling.classList.toggle('d-none');
                })
                .catch(error => console.error('Error al obtener los detalles del cliente:', error));
        });
    });

    document.querySelectorAll('.btn-eliminar').forEach((button) => {
        button.addEventListener('click', () => {
            const dni = button.closest('.list-group-item').dataset.dni;
            if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
                fetch(`db/eliminar_cliente.php?dni=${dni}`)
                    .then(() => {
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al eliminar el cliente:', error));
            }
        });
    });
}
