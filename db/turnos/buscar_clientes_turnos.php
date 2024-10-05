<?php
require_once __DIR__ . '/db.php';

if (isset($_GET['q'])) {
    $busqueda = '%' . $_GET['q'] . '%';

    // Preparar la consulta para buscar clientes
    $stmt = $conn->prepare("SELECT idcliennte, dni, nombre, apellido FROM clientes WHERE nombre LIKE ? OR apellido LIKE ? OR dni LIKE ? LIMIT 10");
    $stmt->bind_param("sss", $busqueda, $busqueda, $busqueda);
    $stmt->execute();
    $result = $stmt->get_result();

    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $cliente = [
            'id' => $row['idcliennte'],
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'dni' => $row['dni'],
            'vehiculos' => []
        ];

        // Obtener los vehículos del cliente
        $stmtVehiculos = $conn->prepare('SELECT patente, modelo, tipo FROM vehiculos WHERE idcliente = ?');
        $stmtVehiculos->bind_param("i", $row['idcliennte']);
        $stmtVehiculos->execute();
        $resultVehiculos = $stmtVehiculos->get_result();

        while ($vehiculo = $resultVehiculos->fetch_assoc()) {
            $cliente['vehiculos'][] = $vehiculo;
        }

        $clientes[] = $cliente;
        $stmtVehiculos->close();
    }

    echo json_encode($clientes);
    $stmt->close();
} else {
    echo json_encode([]); // Retornar un array vacío si no hay búsqueda
}

$conn->close();
