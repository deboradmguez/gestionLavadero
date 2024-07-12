document.addEventListener('DOMContentLoaded', function () {
    // Variables para las instancias de los modales
    let modalDetalleCliente;
    let modalModificarCliente;

    // Crear las instancias de los modales al inicio
    modalDetalleCliente = new bootstrap.Modal(document.getElementById('modalDetalleCliente'));
    modalModificarCliente = new bootstrap.Modal(document.getElementById('modalModificarCliente'));

    // Cargar lista de clientes al cargar la página
    cargarListaClientes();

    // Función para cargar la lista de clientes desde el servidor
    function cargarListaClientes() {
        fetch('db/obtener_cliente.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                const listaClientes = document.getElementById('listaClientes');
                listaClientes.innerHTML = ''; // Limpiar lista existente

                data.forEach(cliente => {
                    const row = listaClientes.insertRow();
                    row.insertCell().textContent = cliente.dni;
                    row.insertCell().textContent = cliente.nombre;
                    row.insertCell().textContent = cliente.apellido;
                    row.insertCell().textContent = cliente.telefono;

                    const accionesCell = row.insertCell();
                    accionesCell.innerHTML = `
                        <div class="cliente-acciones">
                            <button class="btn btn-primary ms-2 btn-detalles" data-bs-toggle="modal" data-bs-target="#modalDetalleCliente" data-dni="${cliente.dni}">
                                <i class="fa-solid fa-circle-info"></i>
                            </button>
                            <button class="btn btn-secondary ms-2 btn-modificar" data-bs-toggle="modal" data-bs-target="#modalModificarCliente" data-dni="${cliente.dni}">
                                <i class="fa-solid fa-user-pen"></i>
                            </button>
                            <button class="btn btn-danger ms-2 btn-eliminar" data-bs-toggle="modal" data-dni="${cliente.dni}">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    `;
                });

                asignarEventosBotones();
            })
            .catch(error => {
                console.error('Error al cargar la lista de clientes:', error);
                mostrarError('Error al cargar la lista de clientes. Por favor, revisa la consola para más detalles.');
            });
    }

    // Evento para cuando el modal de modificación se oculta
    document.getElementById('modalModificarCliente').addEventListener('hidden.bs.modal', function () {
        // Elimina manualmente el backdrop
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
    });

    // Evento para cuando el modal de detalles se oculta
    document.getElementById('modalDetalleCliente').addEventListener('hidden.bs.modal', function () {
        // Elimina manualmente el backdrop
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
    });

    // Función para asignar eventos a los botones de detalles, modificar y eliminar
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
                    eliminarCliente(dni);
                    mostrarModalExito('Cliente eliminado correctamente', 'fas fa-trash-alt text-danger'); // Ícono de eliminar
                }
            });
        });
    }

    // Función para mostrar los detalles de un cliente en el modal
    function mostrarDetalleCliente(dni) {
        fetch(`db/obtener_cliente.php?dni=${dni}`)
            .then(response => response.json())
            .then(data => {
                if (data.dni) {
                    document.getElementById('detalleDNI').textContent = data.dni;
                    document.getElementById('detalleNombre').textContent = data.nombre;
                    document.getElementById('detalleApellido').textContent = data.apellido;
                    document.getElementById('detalleTelefono').textContent = data.telefono;

                    modalDetalleCliente.show(); // Mostrar el modal usando la instancia creada
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

    // Función para obtener los detalles de un cliente y mostrarlos en el formulario de modificación
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

                    modalModificarCliente.show(); // Mostrar el modal usando la instancia creada
                } else {
                    console.error('No se encontraron datos para el DNI:', dni);
                }
            })
            .catch(error => console.error('Error al obtener los detalles del cliente:', error));
    }

    // Evento para el envío del formulario de modificación de cliente
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
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Ocultar el modal actual
                        modalModificarCliente.hide();

                        // Mostrar modal de éxito después de un breve retraso
                        setTimeout(function () {
                            mostrarModalExito('Cliente modificado correctamente', 'fas fa-check-circle text-success');
                        }, 300); // Retraso de 300 milisegundos (ajusta según tus preferencias)
                    } else {
                        mostrarError('Error al modificar el cliente: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error al modificar el cliente:', error);
                    mostrarError('Error al modificar el cliente. Por favor, revisa la consola para más detalles.');
                });
        });
    });

    // Función para mostrar errores al usuario
    function mostrarError(mensaje) {
        alert(mensaje);
        // Aquí podrías agregar código para mostrar el error en un elemento específico de la página
    }

    function mostrarModalExito(mensaje, iconoClase) {
        document.getElementById('successModalIcon').className = iconoClase;
        document.getElementById('successModalLabel').textContent = mensaje;

        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();

        setTimeout(function () {
            successModal.hide();
            location.reload();
        }, 1000);
    }

});
