<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

$response = ['success' => false];

if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];

    $stmt = $conn->prepare("DELETE FROM clientes WHERE dni = ?");
    $stmt->bind_param("s", $dni);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['error'] = "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    $response['error'] = "No DNI provided.";
}

$conn->close();

echo json_encode($response);