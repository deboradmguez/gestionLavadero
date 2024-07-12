<?php
require_once __DIR__ . '/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];

    // Preparar la consulta para actualizar el cliente
    $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, apellido = ?, telefono = ? WHERE dni = ?");
    $stmt->bind_param("ssss", $nombre, $apellido, $telefono, $dni);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Cliente actualizado correctamente';
    } else {
        $response['success'] = false;
        $response['error'] = 'Error al actualizar el cliente: ' . $stmt->error;
    }

    // Cerrar la consulta y la conexión
    $stmt->close();
} else {
    $response['success'] = false;
    $response['error'] = 'Método no permitido';
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);