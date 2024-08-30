document.addEventListener('DOMContentLoaded', function() {
    const elementos = {
        nombreCliente: document.getElementById('nombreCliente'),
        btnGuardarTurno: document.getElementById('btnGuardarTurno'),
        modal: document.getElementById('agregarTurnoModal'),
        clienteSeleccionado: document.getElementById('clienteSeleccionado'),
        vehiculoSelect: document.getElementById('vehiculo'),
        servicioSelect: document.getElementById('servicio')
    };

    // Agregar event listeners
    
    elementos.btnGuardarTurno?.addEventListener('click', agregarTurno);
    elementos.modal?.addEventListener('show.bs.modal', cargarServicios);
    document.addEventListener('click', cerrarResultadosBusqueda);

    cargarServicios();

    


});


