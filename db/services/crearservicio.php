<?php
include ("..\db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servicio = $_POST["servicio"];
    $precio = $_POST["precio"];
    $tiempo = $_POST["tiempo"];

    $sql = "INSERT INTO servicios (servicio, precio, tiempo) VALUES ('$servicio', '$precio', '$tiempo')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../servicios.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}