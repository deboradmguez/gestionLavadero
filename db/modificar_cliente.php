<?php
require_once __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que todos los campos necesarios están presentes
    if (isset($_POST['dni'], $_POST['nuevo_dni'], $_POST['nombre'], $_POST['apellido'], $_POST['telefono'])) {
        $dni = $_POST['dni'];
        $nuevoDni = $_POST['nuevo_dni'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $telefono = $_POST['telefono'];

        // Verificar si el nuevo DNI ya existe en la base de datos
        $stmt = $conn->prepare("SELECT dni FROM clientes WHERE dni = ?");
        $stmt->bind_param("s", $nuevoDni);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0 && $nuevoDni != $dni) {
            echo "<script>alert('El DNI ya está registrado.'); window.location.href = 'clientes.php.php';</script>";
            exit();
        } else {
            // Modificar los datos del cliente
            $stmt->close(); // Cerrar el primer statement antes de reutilizar
            $stmt = $conn->prepare("UPDATE clientes SET dni = ?, nombre = ?, apellido = ?, telefono = ? WHERE dni = ?");
            $stmt->bind_param("sssss", $nuevoDni, $nombre, $apellido, $telefono, $dni);

            if ($stmt->execute()) {
                echo "<script>window.location.href = 'clientes.php';</script>";
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    } else {
        echo "<script>alert('Por favor, complete todos los campos requeridos.'); window.location.href = 'modificar_cliente.php';</script>";
        exit();
    }

    $conn->close();
} else {
    // Si no es POST, redirigir al formulario o manejar adecuadamente
    echo "<script>window.location.href = 'modificar_cliente.php';</script>";
    exit();
}
