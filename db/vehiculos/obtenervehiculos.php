<?php
include ("..\db.php");

if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];

    $stmt = $pdo->prepare("SELECT patente, tipo FROM vehiculos WHERE cliente_id = ?");
    $stmt->execute([$dni]);
    $vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($vehiculos);
} else {
    echo json_encode(['error' => 'No DNI provided']);
}
