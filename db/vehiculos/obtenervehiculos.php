<?php
include ("..\db.php");

if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];

    // Obtener el ID del cliente usando el DNI
    $stmtCliente = $pdo->prepare("SELECT idcliente FROM clientes WHERE dni = ?");
    $stmtCliente->execute([$dni]);
    $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        $clienteId = $cliente['idcliente'];

        // Obtener los vehículos del cliente por ID
        $stmt = $pdo->prepare("SELECT patente, tipo FROM vehiculos WHERE cliente_id = ?");
        $stmt->execute([$clienteId]);
        $vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($vehiculos);
    } else {
        echo json_encode(['error' => 'Cliente no encontrado']);
    }
} else {
    echo json_encode(['error' => 'No DNI provided']);
}
