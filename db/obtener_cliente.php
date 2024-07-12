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
    $stmt = $conn->prepare("SELECT dni, nombre, apellido, telefono FROM clientes");
    $stmt->execute();
    $result = $stmt->get_result();

    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }

    $stmt->close();
    echo json_encode($clientes);
}

$conn->close();