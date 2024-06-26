<?php
// Mostrar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/db.php';

// Comprobar si se envió un formulario POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = []; // Array para almacenar la respuesta
    
    // Obtener los datos del formulario
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];

    // Validar el DNI: debe tener exactamente 8 caracteres y ser numérico
    if (strlen($dni) == 8 && ctype_digit($dni)) {
        // Verificar si el DNI ya existe en la base de datos
        $stmt = $conn->prepare("SELECT dni FROM clientes WHERE dni = ?");
        if ($stmt === false) {
            $response['error'] = 'Prepare failed: ' . $conn->error;
        } else {
            $stmt->bind_param("s", $dni);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $response['error'] = 'El DNI ingresado ya existe en la base de datos.';
            } else {
                // El DNI no existe, proceder con la inserción
                $insert = $conn->prepare("INSERT INTO clientes (dni, nombre, apellido, telefono) VALUES (?, ?, ?, ?)");
                if ($insert === false) {
                    $response['error'] = 'Prepare failed: ' . $conn->error;
                } else {
                    $insert->bind_param("ssss", $dni, $nombre, $apellido, $telefono);
                    if ($insert->execute()) {
                        // Cliente registrado con éxito
                        $response['success'] = 'Cliente registrado correctamente.';
                    } else {
                        $response['error'] = 'Insert failed: ' . $insert->error;
                    }
                    $insert->close();
                }
            }
            $stmt->close();
        }
    } else {
        // DNI no válido
        $response['error'] = 'DNI no válido. Debe tener exactamente 8 caracteres y ser numérico.';
    }

    // Devolver la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}