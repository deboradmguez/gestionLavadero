<?php
// Mostrar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];

    // Validar el DNI: debe tener exactamente 8 caracteres y ser numérico
    if (strlen($dni) == 8 && ctype_digit($dni)) {
        // Verificar si el DNI ya existe en la base de datos
        $stmt = $conn->prepare("SELECT dni FROM clientes WHERE dni = ?");
        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // El DNI no existe, proceder con la inserción
            $insert = $conn->prepare("INSERT INTO clientes (dni, nombre, apellido, telefono) VALUES (?, ?, ?, ?)");
            if ($insert === false) {
                die('Prepare failed: ' . $conn->error);
            }
            $insert->bind_param("ssss", $dni, $nombre, $apellido, $telefono);

            if ($insert->execute()) {
                // Cliente registrado con éxito
                echo "<script>window.location.href = 'lista-clientes.php';</script>";
            } else {
                die('Insert failed: ' . $insert->error);
            }

            $insert->close();
        }

        $stmt->close();
    } else {
        // DNI no válido
        echo "<script>alert('DNI no válido. Debe tener exactamente 8 caracteres y ser numérico.'); window.location.href = 'lista-clientes.php';</script>";
    }

    $conn->close();
}
