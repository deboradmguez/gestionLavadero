<?php
// Mostrar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/db.php'; // Asegúrate de que la ruta sea correcta

// Comprobar si se envió un formulario POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = []; // Array para almacenar la respuesta

    // Obtener los datos del formulario
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $patente = $_POST['patente'];
    $modelo = $_POST['modelo'];
    $tipo = $_POST['tipo'];

    // Validación de datos
    if (empty($dni) || empty($nombre) || empty($apellido) || empty($telefono) || empty($patente) || empty($modelo) || empty($tipo)) {
        $response['error'] = 'Todos los campos son obligatorios.';
    } elseif (!ctype_digit($dni) || strlen($dni) !== 8) {
        $response['error'] = 'DNI no válido. Debe tener exactamente 8 dígitos.';
    } else {
        // Verificar si el DNI ya existe en la base de datos
        $stmt = $conn->prepare("SELECT dni FROM clientes WHERE dni = ?");
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['error'] = 'El DNI ingresado ya existe en la base de datos.';
        } else {
            // Verificar si la patente ya existe
            $stmt = $conn->prepare("SELECT patente FROM vehiculos WHERE patente = ?");
            $stmt->bind_param("s", $patente);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $response['error'] = 'La patente ingresada ya existe en la base de datos.';
            } else {
                // El DNI y la patente no existen, proceder con la inserción
                $conn->begin_transaction(); // Iniciar transacción

                try {
                    // Insertar cliente
                    $insertCliente = $conn->prepare("INSERT INTO clientes (dni, nombre, apellido, telefono) VALUES (?, ?, ?, ?)");
                    $insertCliente->bind_param("ssss", $dni, $nombre, $apellido, $telefono);
                    $insertCliente->execute();
                    $cliente_id = $conn->insert_id;

                    // Insertar vehículo
                    $insertVehiculo = $conn->prepare("INSERT INTO vehiculos (patente, modelo, tipo, idcliente) VALUES (?, ?, ?, ?)");
                    $insertVehiculo->bind_param("sssi", $patente, $modelo, $tipo, $cliente_id);
                    $insertVehiculo->execute();

                    $conn->commit(); // Confirmar transacción
                    $response['success'] = 'Cliente y vehículo registrados correctamente.';
                } catch (Exception $e) {
                    $conn->rollback(); // Revertir transacción en caso de error
                    $response['error'] = 'Error al registrar el cliente y vehículo: ' . $e->getMessage();
                }
            }
        }
    }

    // Devolver la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
