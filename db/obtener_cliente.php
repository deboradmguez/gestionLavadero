<?php
require_once __DIR__ . '/db.php';

if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];

    $stmt = $conn->prepare("SELECT * FROM clientes WHERE dni = ?");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode([]);
    }

    $stmt->close();
} else {
    $clientes = [];
    $stmt = $conn->prepare("SELECT idcliennte, dni, nombre, apellido FROM clientes");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cliente = [
            'idcliennte' => $row['idcliennte'],
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
}

$conn->close();