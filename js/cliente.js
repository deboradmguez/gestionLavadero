document.addEventListener('DOMContentLoaded', function () {
    const listaClientes = document.getElementById('listaClientes');
    let modalDetalleCliente = new bootstrap.Modal(document.getElementById('modalDetalleCliente'));

    if (listaClientes) {
        listaClientes.addEventListener('click', function (event) {
            if (event.target.classList.contains('btn-detalles')) {
                const clienteSeleccionado = event.target.closest('li');
                mostrarDetalleCliente(clienteSeleccionado.dataset.dni);
            }
        });
    }


    // Función para mostrar los detalles del cliente
    function mostrarDetalleCliente(dni) {
        console.log(`Fetching details for DNI: ${dni}`); // Log para verificar el DNI
        fetch(`db/obtener_cliente.php?dni=${dni}`)
            .then(response => response.json())
            .then(data => {
                console.log('Data received:', data); // Log para verificar los datos recibidos
                if (data.dni) {
                    document.getElementById('detalleDNI').textContent = data.dni;
                    document.getElementById('detalleNombre').textContent = data.nombre;
                    document.getElementById('detalleApellido').textContent = data.apellido;
                    document.getElementById('detalleTelefono').textContent = data.telefono;

                    // Mostrar el modal de detalles del cliente
                    modalDetalleCliente.show();
                } else {
                    console.error('No data found for DNI:', dni);
                }
            })
            .catch(error => console.error('Error al obtener los detalles del cliente:', error));
    }

    // Evento para modificar cliente
    document.querySelectorAll('.btn-modificar').forEach(button => {
        button.addEventListener('click', function () {
            const dni = button.closest('li').dataset.dni;
            console.log('Modifying cliente with DNI:', dni); // Log para verificar el DNI antes de modificar
            obtenerDetalleCliente(dni);
        });
    });

    // Función para obtener los detalles del cliente para modificar
    function obtenerDetalleCliente(dni) {
        console.log(`Fetching details for DNI: ${dni}`); // Log para verificar el DNI
        fetch(`db/obtener_cliente.php?dni=${dni}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener los detalles del cliente');
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data); // Log para verificar los datos recibidos
                // Llenar los campos del formulario con los datos del cliente
                document.getElementById('modificarDni').value = data.dni;
                document.getElementById('modificarNombre').value = data.nombre;
                document.getElementById('modificarApellido').value = data.apellido;
                document.getElementById('modificarTelefono').value = data.telefono;
            })
            .catch(error => console.error('Error al obtener los detalles del cliente:', error));
    }

    // Evento para eliminar cliente
    document.querySelectorAll('.btn-eliminar').forEach(button => {
        button.addEventListener('click', function () {
            const dni = button.closest('li').dataset.dni;
            console.log('Deleting cliente with DNI:', dni); // Log para verificar el DNI antes de eliminar
            if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
                fetch(`db/eliminar_cliente.php?dni=${dni}`)
                    .then(() => {
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al eliminar el cliente:', error));
            }
        });
    });

    //guardar cliente
    document.getElementById('clienteForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var form = event.target;
        var formData = new FormData(form);

        fetch('db/guardar_cliente.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                var dniErrorDiv = document.getElementById('dni-error');
                if (data.error) {
                    dniErrorDiv.textContent = data.error;
                } else {
                    dniErrorDiv.textContent = '';

                    // Cerrar el modal de registro
                    var modalRegistrarClienteEl = document.getElementById('modalRegistrarCliente');
                    var modalRegistrarCliente = bootstrap.Modal.getInstance(modalRegistrarClienteEl);
                    modalRegistrarCliente.hide();

                    // Esperar un momento para asegurarse de que el modal de registro se haya cerrado
                    setTimeout(function() {
                        // Mostrar el modal de éxito
                        var successModalEl = document.getElementById('successModal');
                        var successModal = new bootstrap.Modal(successModalEl);
                        successModal.show();

                        // Ocultar el modal de éxito después de unos segundos
                        setTimeout(function() {
                            successModal.hide();
                        }, 1500); // miliseg
                    }, 500); // 500 miliseg  para cerrar el modal de registro
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

});
