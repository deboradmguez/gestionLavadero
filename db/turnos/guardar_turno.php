<?php
include ("..\db.php");
$datos = json_decode(file_get_contents('php://input'), true);

// Validar que todos los campos necesarios estén presentes
if (!isset($datos['idservicio']) || !isset($datos['idvehiculo']) || !isset($datos['idcliente']) || !isset($datos['fecha']) || !isset($datos['hora'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
    exit;
}

try {
    // Preparar la consulta SQL
    $stmt = $conn->prepare("INSERT INTO turnos (idservicio, idvehiculo, idcliente, fecha, hora, estado) VALUES (?, ?, ?, ?, ?, ?)");

    // Establecer el estado inicial (puedes ajustarlo según tus necesidades)
    $estado = 'pendiente';

    // Ejecutar la consulta con los datos recibidos
    $stmt->bind_param("iiisss", $datos['idservicio'], $datos['idvehiculo'], $datos['idcliente'], $datos['fecha'], $datos['hora'], $estado);
    $stmt->execute();

    // Verificar si se insertó correctamente
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Turno guardado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo guardar el turno']);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el turno: ' . $e->getMessage()]);
}
