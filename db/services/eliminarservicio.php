<?php
include ("..\db.php");

if (isset($_GET['id'])) {
    $idservicio = $_GET['id'];
    $sql = "DELETE FROM servicios WHERE idservicio=$idservicio";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../servicios.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}