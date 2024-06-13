<?php
require_once __DIR__ . '/db.php';


if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];

    $stmt = $conn->prepare("DELETE FROM clientes WHERE dni = ?");
    $stmt->bind_param("s", $dni);

    if ($stmt->execute()) {
        echo "<script>window.location.href = '../clientes.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
