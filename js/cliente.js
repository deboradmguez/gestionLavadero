document.addEventListener('DOMContentLoaded', function () {
    let modalDetalleCliente = new bootstrap.Modal(document.getElementById('modalDetalleCliente'));
    let modalModificarCliente = new bootstrap.Modal(document.getElementById('modalModificarCliente'));

    cargarListaClientes();

    // Agregar el evento de búsqueda
    document.getElementById('busqueda').addEventListener('input', function () {
        const busqueda = this.value.toLowerCase();
        filtrarClientes(busqueda);
    });

    function cargarListaClientes() {
        fetch('db/obtener_cliente.php')
            .then(response => response.text())
            .then(text => {
                try {
                    const clientes = JSON.parse(text);

                    if (!Array.isArray(clientes)) {
                        throw new Error('Respuesta del servidor no es un array');
                    }

                    mostrarClientes(clientes);

                } catch (error) {
                    console.error('Error al parsear JSON:', error, 'Respuesta del servidor:', text);
                    mostrarError('Error al cargar la lista de clientes. Por favor, revisa la consola para más detalles.');
                }
            })
            .catch(error => {
                console.error('Error al cargar la lista de clientes:', error);
                mostrarError('Error al cargar la lista de clientes. Por favor, revisa la consola para más detalles.');
            });
    }

    function mostrarClientes(clientes) {
        const listaClientes = document.getElementById('listaClientes');
        listaClientes.innerHTML = '';

        clientes.forEach(cliente => {
            const row = listaClientes.insertRow();
            row.insertCell().textContent = cliente.nombre + ' ' + cliente.apellido;

            const vehiculosCell = row.insertCell();
            if (cliente.vehiculos && cliente.vehiculos.length > 0) {
                vehiculosCell.innerHTML = cliente.vehiculos.map(v => `<div>${v.patente} (${v.tipo})</div>`).join('');
            } else {
                vehiculosCell.textContent = '-';
            }

            const accionesCell = row.insertCell();
            accionesCell.innerHTML = `
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
            `;
        });

        asignarEventosBotones();
    }

    function mostrarError(mensaje) {
        const errorContainer = document.getElementById('errorContainer');
        if (errorContainer) {
            errorContainer.textContent = mensaje;
            errorContainer.style.display = 'block';
        } else {
            alert(mensaje);
        }
    }

    function asignarEventosBotones() {
        document.querySelectorAll('.btn-detalles').forEach(button => {
            button.addEventListener('click', function () {
                const dni = this.getAttribute('data-dni');
                mostrarDetalleCliente(dni);
            });
        });

        document.querySelectorAll('.btn-modificar').forEach(button => {
            button.addEventListener('click', function () {
                const dni = this.getAttribute('data-dni');
                obtenerDetalleCliente(dni);
            });
        });

        document.querySelectorAll('.btn-eliminar').forEach(button => {
            button.addEventListener('click', function () {
                const dni = this.getAttribute('data-dni');
                if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
                    fetch(`db/eliminar_cliente.php?dni=${dni}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Cliente eliminado con éxito.');
                                cargarListaClientes();
                            } else {
                                alert('Error al eliminar el cliente: ' + (data.error || 'Unknown error.'));
                            }
                        })
                        .catch(error => {
                            console.error('Error al eliminar el cliente:', error);
                        });
                }
            });
        });
    }

    function mostrarDetalleCliente(dni) {
        fetch(`db/obtener_cliente.php?dni=${dni}`)
            .then(response => response.json())
            .then(data => {
                if (data.dni) {
                    document.getElementById('detalleDNI').textContent = data.dni;
                    document.getElementById('detalleNombre').textContent = data.nombre;
                    document.getElementById('detalleApellido').textContent = data.apellido;
                    document.getElementById('detalleTelefono').textContent = data.telefono;

                    modalDetalleCliente.show();
                } else {
                    console.error('No se encontraron datos para el DNI:', dni);
                    mostrarError('No se encontraron datos para el DNI especificado.');
                }
            })
            .catch(error => {
                console.error('Error al obtener los detalles del cliente:', error);
                mostrarError('Error al obtener los detalles del cliente. Por favor, revisa la consola para más detalles.');
            });
    }

    function obtenerDetalleCliente(dni) {
        fetch(`db/obtener_cliente.php?dni=${dni}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener los detalles del cliente');
                }
                return response.json();
            })
            .then(data => {
                if (data.dni) {
                    document.getElementById('modificarDni').value = data.dni;
                    document.getElementById('modificarNombre').value = data.nombre;
                    document.getElementById('modificarApellido').value = data.apellido;
                    document.getElementById('modificarTelefono').value = data.telefono;

                    modalModificarCliente.show();
                } else {
                    console.error('No se encontraron datos para el DNI:', dni);
                }
            })
            .catch(error => console.error('Error al obtener los detalles del cliente:', error));
    }

    document.getElementById('modalModificarCliente').addEventListener('shown.bs.modal', function () {
        document.getElementById('formModificarCliente').addEventListener('submit', function (event) {
            event.preventDefault();

            const dni = document.getElementById('modificarDni').value;
            const nombre = document.getElementById('modificarNombre').value;
            const apellido = document.getElementById('modificarApellido').value;
            const telefono = document.getElementById('modificarTelefono').value;

            const formData = new FormData();
            formData.append('dni', dni);
            formData.append('nombre', nombre);
            formData.append('apellido', apellido);
            formData.append('telefono', telefono);

            fetch('db/modificar_cliente.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modalModificarCliente.hide();
                        cargarListaClientes();
                        mostrarModalExito('Cliente modificado correctamente', 'fas fa-check-circle text-success');
                    } else {
                        mostrarError('Error al modificar el cliente. Por favor, revisa la consola para más detalles.');
                    }
                })
                .catch(error => mostrarError('Error al modificar el cliente. Por favor, revisa la consola para más detalles.'));
        });
    });

    document.getElementById("btn-recargar").addEventListener("click", function() {
        location.reload();
    });

    // Función para filtrar los clientes
    function filtrarClientes(busqueda) {
        fetch('db/obtener_cliente.php')
            .then(response => response.text())
            .then(text => {
                try {
                    const clientes = JSON.parse(text);

                    if (!Array.isArray(clientes)) {
                        throw new Error('Respuesta del servidor no es un array');
                    }

                    const clientesFiltrados = clientes.filter(cliente => {
                        const nombreCompleto = (cliente.nombre + ' ' + cliente.apellido).toLowerCase();
                        const dni = cliente.dni.toString();
                        return nombreCompleto.includes(busqueda) || dni.includes(busqueda);
                    });

                    mostrarClientes(clientesFiltrados);

                } catch (error) {
                    console.error('Error al parsear JSON:', error, 'Respuesta del servidor:', text);
                    mostrarError('Error al cargar la lista de clientes. Por favor, revisa la consola para más detalles.');
                }
            })
            .catch(error => {
                console.error('Error al cargar la lista de clientes:', error);
                mostrarError('Error al cargar la lista de clientes. Por favor, revisa la consola para más detalles.');
            });
    }
});
