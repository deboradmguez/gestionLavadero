<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreusuario = $_POST['nombreusuario'];
    $contraseña = $_POST['contraseña'];

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombreusuario = ?");
    $stmt->bind_param("s", $nombreusuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña
        $contraseña_hasheada_md5 = $user['contraseña']; // Obtener la contraseña hasheada de la base de datos
        echo "Contraseña hasheada en la base de datos: " . $contraseña_hasheada_md5 . "<br>"; // Depuración

        // Hashear la contraseña ingresada con MD5 para compararla con la contraseña hasheada en la base de datos
        $contraseña_ingresada_md5 = md5($contraseña);

        if ($contraseña_ingresada_md5 === $contraseña_hasheada_md5) {
            // Iniciar sesión
            $_SESSION['idusuario'] = $user['idusuario'];
            $_SESSION['nombreusuario'] = $user['nombreusuario'];
            header("Location: ../index.php");
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "No se encontró un usuario con ese nombre de usuario";
    }

    $stmt->close();
}

$conn->close();
