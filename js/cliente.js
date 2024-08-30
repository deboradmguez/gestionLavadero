document.addEventListener('DOMContentLoaded', () => {
    const modalDetalleCliente = new bootstrap.Modal(document.getElementById('modalDetalleCliente'));
    const modalModificarCliente = new bootstrap.Modal(document.getElementById('modalModificarCliente'));
    const listaClientes = document.getElementById('listaClientes');
    const errorContainer = document.getElementById('errorContainer');

    // Función para manejar errores
    const mostrarError = (mensaje) => {
        if (errorContainer) {
            errorContainer.textContent = mensaje;
            errorContainer.style.display = 'block';
        } else {
            alert(mensaje);
        }
    };

    // Función para cargar y mostrar clientes
    const cargarYMostrarClientes = async (filtro = '') => {
        try {
            const response = await fetch('db/obtener_cliente.php');
            const clientes = await response.json();

            if (!Array.isArray(clientes)) {
                throw new Error('Respuesta del servidor no es un array');
            }

            const clientesFiltrados = clientes.filter(cliente => {
                const nombreCompleto = `${cliente.nombre} ${cliente.apellido}`.toLowerCase();
                const dni = cliente.dni.toString();
                return nombreCompleto.includes(filtro) || dni.includes(filtro);
            });

            mostrarClientes(clientesFiltrados);
        } catch (error) {
            console.error('Error al cargar la lista de clientes:', error);
            mostrarError('Error al cargar la lista de clientes. Por favor, revisa la consola para más detalles.');
        }
    };

    // Función para mostrar clientes
    const mostrarClientes = (clientes) => {
        listaClientes.innerHTML = clientes.map(cliente => `
            <tr>
                <td>${cliente.nombre} ${cliente.apellido}</td>
                <td>${cliente.vehiculos && cliente.vehiculos.length > 0 
                    ? cliente.vehiculos.map(v => `<div>${v.patente} (${v.tipo})</div>`).join('')
                    : '-'}</td>
                <td>
                    <div class="cliente-acciones">
                        <button class="btn btn-primary ms-2 btn-detalles" data-bs-toggle="modal" data-bs-target="#modalDetalleCliente" data-dni="${cliente.dni}">
                            <i class="fa-solid fa-circle-info"></i>
                        </button>
                        <button class="btn btn-secondary ms-2 btn-modificar" data-bs-toggle="modal" data-bs-target="#modalModificarCliente" data-dni="${cliente.dni}">
                            <i class="fa-solid fa-user-pen"></i>
                        </button>
                        <button class="btn btn-danger ms-2 btn-eliminar" data-dni="${cliente.dni}">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        asignarEventosBotones();
    };

    // Función para asignar eventos a los botones
    const asignarEventosBotones = () => {
        listaClientes.addEventListener('click', (e) => {
            const target = e.target.closest('button');
            if (!target) return;

            const dni = target.getAttribute('data-dni');
            if (target.classList.contains('btn-detalles')) {
                mostrarDetalleCliente(dni);
            } else if (target.classList.contains('btn-modificar')) {
                obtenerDetalleCliente(dni);
            } else if (target.classList.contains('btn-eliminar')) {
                confirmarEliminarCliente(dni);
            }
        });
    };

    // Función para mostrar detalles del cliente
    const mostrarDetalleCliente = async (dni) => {
        try {
            const response = await fetch(`db/obtener_cliente.php?dni=${dni}`);
            const data = await response.json();
            if (!data.dni) {
                throw new Error('No se encontraron datos para el DNI especificado.');
            }
            document.getElementById('detalleDNI').textContent = data.dni;
            document.getElementById('detalleNombre').textContent = data.nombre;
            document.getElementById('detalleApellido').textContent = data.apellido;
            document.getElementById('detalleTelefono').textContent = data.telefono;
            modalDetalleCliente.show();
        } catch (error) {
            console.error('Error al obtener los detalles del cliente:', error);
            mostrarError('Error al obtener los detalles del cliente. Por favor, revisa la consola para más detalles.');
        }
    };

    // Función para obtener detalles del cliente para modificación
    const obtenerDetalleCliente = async (dni) => {
        try {
            const response = await fetch(`db/obtener_cliente.php?dni=${dni}`);
            if (!response.ok) throw new Error('Error al obtener los detalles del cliente');
            const data = await response.json();
            if (!data.dni) {
                throw new Error('No se encontraron datos para el DNI especificado.');
            }
            document.getElementById('modificarDni').value = data.dni;
            document.getElementById('modificarNombre').value = data.nombre;
            document.getElementById('modificarApellido').value = data.apellido;
            document.getElementById('modificarTelefono').value = data.telefono;
            modalModificarCliente.show();
        } catch (error) {
            console.error('Error al obtener los detalles del cliente:', error);
            mostrarError('Error al obtener los detalles del cliente. Por favor, revisa la consola para más detalles.');
        }
    };

    // Función para confirmar y eliminar cliente
    const confirmarEliminarCliente = async (dni) => {
        if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
            try {
                const response = await fetch(`db/eliminar_cliente.php?dni=${dni}`);
                const data = await response.json();
                if (!data.success) {
                    throw new Error(data.error || 'Error desconocido al eliminar el cliente.');
                }
                alert('Cliente eliminado con éxito.');
                cargarYMostrarClientes();
            } catch (error) {
                console.error('Error al eliminar el cliente:', error);
                mostrarError('Error al eliminar el cliente. Por favor, revisa la consola para más detalles.');
            }
        }
    };

    // Event listeners
    document.getElementById('busqueda').addEventListener('input', (e) => {
        cargarYMostrarClientes(e.target.value.toLowerCase());
    });

    document.getElementById('btn-recargar').addEventListener('click', () => location.reload());

    document.getElementById('modalModificarCliente').addEventListener('shown.bs.modal', () => {
        document.getElementById('formModificarCliente').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
                const response = await fetch('db/modificar_cliente.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (!data.success) {
                    throw new Error('Error al modificar el cliente.');
                }
                modalModificarCliente.hide();
                cargarYMostrarClientes();
                mostrarModalExito('Cliente modificado correctamente', 'fas fa-check-circle text-success');
        });
    });

    // Inicializar
    cargarYMostrarClientes();
});
