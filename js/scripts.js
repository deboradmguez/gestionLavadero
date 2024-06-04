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


        document.addEventListener("DOMContentLoaded", function() {
            var links = document.querySelectorAll(".nav-link");
            links.forEach(function(link) {
                link.addEventListener("click", function(event) {
                    var title = event.target.innerText; // Obtener el texto del enlace como título
                    document.title = title;
                });
            });
        });


    // Mostrar la primera sección por defecto
    showSection('inicio');

    // Event listener para los elementos de la lista de clientes
    document.addEventListener('DOMContentLoaded', function () {
        const listaClientes = document.getElementById('listaClientes');
        const detalleCliente = document.getElementById('detalleCliente');
    
        listaClientes.addEventListener('click', function (event) {
            // Verifica si se hizo clic en un cliente
            if (event.target.classList.contains('cliente-nombre')) {
                const clienteSeleccionado = event.target.closest('li');
    
                // Oculta los detalles y botones de otros clientes
                document.querySelectorAll('.cliente-detalle').forEach(function (detalle) {
                    detalle.style.display = 'none';
                });
    
                // Muestra los detalles y botones del cliente seleccionado
                detalleCliente.style.display = 'block';
            } else {
                // Si se hace clic fuera de un cliente, oculta los detalles y botones
                detalleCliente.style.display = 'none';
            }
        });
    });
    
    //detalles del cliente
    document.addEventListener('DOMContentLoaded', function () {
        // Ver detalle del cliente
        document.querySelectorAll('.cliente-nombre').forEach(function (nombre) {
            nombre.addEventListener('click', function () {
                const dni = this.closest('li').dataset.dni;
                fetch('db\obtener_cliente.php?dni=' + dni)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('detalleDNI').textContent = 'DNI: ' + data.dni;
                        document.getElementById('detalleNombre').textContent = 'Nombre: ' + data.nombre;
                        document.getElementById('detalleApellido').textContent = 'Apellido: ' + data.apellido;
                        document.getElementById('detalleTelefono').textContent = 'Teléfono: ' + data.telefono;
    
                        this.nextElementSibling.classList.toggle('d-none');
                    });
            });
        });
    });

    // Event listener para el botón de eliminar cliente
    document.querySelectorAll('.btn-eliminar').forEach((button) => {
        button.addEventListener('click', () => {
            const dni = button.closest('.list-group-item').dataset.dni;
            if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
                fetch('db/eliminar_cliente.php?dni=' + dni)
                    .then(() => {
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al eliminar el cliente:', error));
            }
        });
    });

});

function showSection(sectionId) {
    fetch(sectionId + '.php')
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
