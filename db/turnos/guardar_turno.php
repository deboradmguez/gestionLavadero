<?php
include ("..\db.php");

// Capturar los datos enviados desde el formulario
$clienteSeleccionado = $_POST['clienteSeleccionado'];
$vehiculo = $_POST['vehiculo'];
$servicio = $_POST['servicio'];

// Obtener la duración del servicio seleccionado
$queryServicio = $conn->query("SELECT tiempo FROM servicios WHERE idservicio = $servicio");
$servicioData = $queryServicio->fetch_assoc();
$duracionServicio = (int)$servicioData['tiempo']; // Tiempo en minutos

// Obtener la fecha actual
$fecha = date('Y-m-d');

// Calcular el tiempo total de los turnos del día y el último turno asignado
$result = $conn->query("SELECT hora, s.tiempo FROM turnos t JOIN servicios s ON t.servicio_id = s.idservicio WHERE t.fecha = '$fecha' ORDER BY t.hora DESC");
$tiempoTotal = 0;
$ultimaHoraFin = '08:00'; // Hora de inicio del primer turno del día

while ($row = $result->fetch_assoc()) {
    $tiempoTotal += $row['tiempo'];
    $ultimaHoraFin = date('H:i', strtotime($row['hora'] . ' + ' . $row['tiempo'] . ' minutes'));
}

// Definir el límite máximo de 8 horas (480 minutos)
$LIMITE_HORAS = 480;

if ($tiempoTotal + $duracionServicio > $LIMITE_HORAS) {
    echo 'No hay tiempo suficiente para asignar este servicio hoy.';
} else {
    // Asignar el nuevo turno después del último
    $horaInicioNuevoTurno = $ultimaHoraFin;

    // Insertar el turno en la base de datos
    $sql = "INSERT INTO turnos (cliente_id, vehiculo, servicio_id, fecha, hora) VALUES ('$clienteSeleccionado', '$vehiculo', '$servicio', '$fecha', '$horaInicioNuevoTurno')";
    if ($conn->query($sql) === TRUE) {
        echo "Turno guardado exitosamente a las $horaInicioNuevoTurno.";
    } else {
        echo 'Error al guardar el turno: ' . $conn->error;
    }
}

$conn->close();
