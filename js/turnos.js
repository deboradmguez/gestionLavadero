// Escuchar el click del botón para guardar turno
document.getElementById('btnGuardarTurno').addEventListener('click', function () {
    const clienteSeleccionado = document.getElementById('clienteSeleccionado').value;
    const vehiculo = document.getElementById('vehiculo').value;
    const servicio = Array.from(document.getElementById('servicio').selectedOptions).map(option => option.value); // Capturar servicios seleccionados

    if (!clienteSeleccionado || !vehiculo || servicio.length === 0) {
        alert('Por favor, complete todos los campos.');
        return;
    }

    // Crear la solicitud AJAX para guardar el turno
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'guardar_turno.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert(xhr.responseText); // Mostrar respuesta del servidor
            location.reload(); // Recargar la página para ver los turnos actualizados
        } else {
            console.error('Error al guardar el turno');
        }
    };

    // Enviar los datos al PHP
    xhr.send(`clienteSeleccionado=${clienteSeleccionado}&vehiculo=${vehiculo}&servicio=${JSON.stringify(servicio)}`);
});

// Función para buscar clientes al escribir en el campo de búsqueda
document.getElementById('clienteBusqueda').addEventListener('input', buscarCliente);

function buscarCliente() {
    const busqueda = document.getElementById('clienteBusqueda').value.trim();
    const resultadosDiv = document.getElementById('resultadosBusqueda');

    if (busqueda.length < 3) {
        resultadosDiv.style.display = 'none';
        return;
    }

    // Buscar clientes con fetch
    fetch(`../db/turnos/buscar_clientes_turnos.php?query=${encodeURIComponent(busqueda)}`)
        .then(response => response.json())
        .then(data => {
            resultadosDiv.innerHTML = '';
            if (data.length > 0) {
                data.forEach(cliente => {
                    const item = document.createElement('a');
                    item.className = 'list-group-item list-group-item-action';
                    item.textContent = `${cliente.nombre} ${cliente.apellido} (DNI: ${cliente.dni})`;
                    item.onclick = () => seleccionarCliente(cliente);
                    resultadosDiv.appendChild(item);
                });
                resultadosDiv.style.display = 'block'; // Mostrar resultados
            } else {
                resultadosDiv.innerHTML = '<div class="list-group-item">No se encontraron clientes.</div>';
                resultadosDiv.style.display = 'block'; // Mostrar mensaje
            }
        })
        .catch(error => {
            console.error('Error al buscar clientes:', error);
        });
}

// Seleccionar cliente y cargar vehículos asociados
function seleccionarCliente(cliente) {
    document.getElementById('clienteSeleccionado').value = cliente.dni;
    document.getElementById('clienteBusqueda').value = `${cliente.nombre} ${cliente.apellido}`;
    document.getElementById('resultadosBusqueda').style.display = 'none'; // Ocultar resultados
    cargarVehiculos(cliente.dni); // Cargar vehículos del cliente
}

// Cargar vehículos del cliente seleccionado
function cargarVehiculos(dni) {
    const vehiculoSelect = document.getElementById('vehiculo');
    vehiculoSelect.innerHTML = '<option value="">Cargando vehículos...</option>'; // Mensaje de carga

    fetch(`../db/vehiculos/obtenervehiculos.php?dni=${encodeURIComponent(dni)}`)
        .then(response => response.json())
        .then(data => {
            vehiculoSelect.innerHTML = '<option value="">Seleccione un vehículo</option>';
            data.forEach(vehiculo => {
                const option = document.createElement('option');
                option.value = vehiculo.idvehiculo;
                option.textContent = `${vehiculo.patente} (${vehiculo.tipo})`;
                vehiculoSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar vehículos:', error);
            vehiculoSelect.innerHTML = '<option value="">Error al cargar vehículos</option>';
        });
}
