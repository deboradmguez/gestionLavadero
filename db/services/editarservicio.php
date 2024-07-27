<?php
include ("..\db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idservicio = $_POST["idservicio"];
    $servicio = $_POST["servicio"];
    $precio = $_POST["precio"];
    $tiempo = $_POST["tiempo"];

    $sql = "UPDATE servicios SET servicio='$servicio', precio='$precio', tiempo='$tiempo' WHERE idservicio=$idservicio";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../servicios.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}