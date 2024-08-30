<?php
include ("..\db.php");

header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("SELECT idservicio, servicio FROM servicios");
    $stmt->execute();
    $result = $stmt->get_result();
    $servicios = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($servicios);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
